<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\User as Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    protected function getTenant()
    {
        return tenant('slug') ?? tenant('id') ?? request()->segment(1);
    }

    /**
     * Display overall analytics and reporting dashboard.
     */
    public function index()
    {
        $tenant = $this->getTenant();

        $totalQuizzes = Quiz::count();
        $totalParticipants = Participant::count();
        $totalAttempts = QuizAttempt::count();

        $submittedAttempts = QuizAttempt::with('quiz')->where('status', 'submitted')->get();
        
        $passedAttemptsCount = $submittedAttempts->filter(function ($att) {
            $passingScore = $att->quiz?->passing_score ?: 70;
            return $att->score >= $passingScore;
        })->count();

        $passRate = $submittedAttempts->count() > 0 
            ? round(($passedAttemptsCount / $submittedAttempts->count()) * 100, 1) 
            : 0;

        $topQuizzes = Quiz::withCount('attempts')
            ->orderByDesc('attempts_count')
            ->take(5)
            ->get();

        $recentAttempts = QuizAttempt::with(['user', 'quiz'])
            ->latest()
            ->take(15)
            ->get();

        return view('owner.reports.index', compact(
            'tenant', 'totalQuizzes', 'totalParticipants', 'totalAttempts', 
            'passRate', 'topQuizzes', 'recentAttempts', 'passedAttemptsCount'
        ));
    }

    /**
     * Display detailed report and analytics for a specific quiz.
     */
    public function quizReport($quizId)
    {
        $tenant = $this->getTenant();
        $quiz = Quiz::with(['questions', 'attempts.user'])->findOrFail($quizId);

        $attempts = $quiz->attempts()->with('user')->latest()->get();
        $submitted = $attempts->where('status', 'submitted');

        $stats = [
            'total_attempts' => $attempts->count(),
            'submitted_count' => $submitted->count(),
            'passed_count' => $submitted->filter(fn($a) => $a->score >= $quiz->passing_score)->count(),
            'failed_count' => $submitted->filter(fn($a) => $a->score < $quiz->passing_score)->count(),
            'highest_score' => $submitted->max('score') ?? 0,
            'lowest_score' => $submitted->min('score') ?? 0,
            'avg_score' => $submitted->count() > 0 ? round($submitted->avg('score'), 1) : 0,
        ];

        $stats['pass_rate'] = $stats['submitted_count'] > 0 
            ? round(($stats['passed_count'] / $stats['submitted_count']) * 100, 1) 
            : 0;

        return view('owner.reports.quiz', compact('tenant', 'quiz', 'attempts', 'stats'));
    }

    /**
     * Export quiz reports to CSV / Excel.
     */
    public function export($type, Request $request)
    {
        $quizId = $request->query('quiz_id');
        $query = QuizAttempt::with(['user', 'quiz'])->latest();

        if ($quizId) {
            $query->where('quiz_id', $quizId);
            $quizTitle = Quiz::find($quizId)?->title ?: 'quiz';
            $filename = "laporan_hasil_ujian_" . str_replace(' ', '_', strtolower($quizTitle)) . "_" . date('Ymd_His') . ".csv";
        } else {
            $filename = "laporan_semua_ujian_tenant_" . date('Ymd_His') . ".csv";
        }

        $attempts = $query->get();

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($attempts) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fgetcsv($file, [
                'ID Percobaan',
                'Nama Peserta',
                'Email Peserta',
                'No WhatsApp',
                'Judul Kuis',
                'Kategori',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Durasi (Menit)',
                'Status Pengerjaan',
                'Skor Akhir',
                'Status Kelulusan',
                'Metode Selesai'
            ], ';');

            // CSV Rows
            foreach ($attempts as $att) {
                $isPassed = $att->score >= ($att->quiz?->passing_score ?: 70);
                $duration = $att->started_at && $att->finished_at ? $att->started_at->diffInMinutes($att->finished_at) : 0;

                fputcsv($file, [
                    $att->id,
                    $att->user?->name ?: 'Terhapus',
                    $att->user?->email ?: '-',
                    $att->user?->phone ?: '-',
                    $att->quiz?->title ?: 'Terhapus',
                    $att->quiz?->category ?: 'Umum',
                    $att->started_at ? $att->started_at->format('Y-m-d H:i:s') : '-',
                    $att->finished_at ? $att->finished_at->format('Y-m-d H:i:s') : '-',
                    $duration,
                    $att->status === 'submitted' ? 'Selesai' : ($att->status === 'in_progress' ? 'Sedang Mengerjakan' : 'Expired'),
                    $att->score ?? 0,
                    $att->status === 'submitted' ? ($isPassed ? 'LULUS' : 'BELUM LULUS') : '-',
                    $att->end_reason ?: 'manual'
                ], ';');
            }

            fclose($file);
        };

        return Response::streamDownload($callback, $filename, $headers);
    }
}
