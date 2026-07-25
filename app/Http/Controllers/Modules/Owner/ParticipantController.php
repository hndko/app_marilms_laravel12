<?php

namespace App\Http\Controllers\Modules\Owner;

use App\Http\Controllers\Controller;
use App\Models\Tenant\User as Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    protected function getTenant()
    {
        return tenant('slug') ?? tenant('id') ?? request()->segment(1);
    }

    /**
     * Display listing of participants with search and filter.
     */
    public function index(Request $request)
    {
        $tenant = $this->getTenant();
        $query = Participant::withCount('quizAttempts');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $participants = $query->latest()->paginate(15)->withQueryString();

        return view('modules.owner.participants.index', compact('tenant', 'participants'));
    }

    /**
     * Show form for creating a new participant manually.
     */
    public function create()
    {
        $tenant = $this->getTenant();
        return view('modules.owner.participants.create', compact('tenant'));
    }

    /**
     * Store a newly created participant in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        $defaultPassword = $request->password ?: 'password123';

        $participant = Participant::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone,
            'password' => $defaultPassword,
            'status' => $request->status,
        ]);

        if ($request->boolean('send_invite')) {
            Log::info("Invitation email/WA queued for participant: {$participant->email}");
        }

        return redirect()->route('tenant.owner.participants.index')
            ->with('success', "Peserta '{$participant->name}' berhasil ditambahkan! Password awal: {$defaultPassword}");
    }

    /**
     * Display specified participant details & quiz history.
     */
    public function show($id)
    {
        $tenant = $this->getTenant();
        $participant = Participant::with(['quizAttempts.quiz'])->findOrFail($id);

        return view('modules.owner.participants.show', compact('tenant', 'participant'));
    }

    /**
     * Show form for editing specified participant.
     */
    public function edit($id)
    {
        $tenant = $this->getTenant();
        $participant = Participant::findOrFail($id);

        return view('modules.owner.participants.edit', compact('tenant', 'participant'));
    }

    /**
     * Update specified participant in storage.
     */
    public function update(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $participant->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $participant->update($data);

        return redirect()->route('tenant.owner.participants.index')
            ->with('success', "Data peserta '{$participant->name}' berhasil diperbarui!");
    }

    /**
     * Import participants from CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle);
        $count = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $name = trim($row[0] ?? '');
            $email = strtolower(trim($row[1] ?? ''));
            $phone = trim($row[2] ?? '');
            $password = trim($row[3] ?? 'password123');

            if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            if (Participant::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            Participant::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone ?: null,
                'password' => $password ?: 'password123',
                'status' => 'active',
            ]);

            $count++;
        }

        fclose($handle);

        $msg = "Berhasil mengimpor {$count} peserta baru!";
        if ($skipped > 0) {
            $msg .= " ({$skipped} data dilewati karena duplikat atau format email tidak valid).";
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Reset participant password to default.
     */
    public function resetPassword($id)
    {
        $participant = Participant::findOrFail($id);
        $newPass = 'password123';
        $participant->update(['password' => $newPass]);

        return redirect()->back()->with('success', "Password untuk '{$participant->name}' berhasil direset menjadi: {$newPass}");
    }

    /**
     * Remove specified participant from storage.
     */
    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);
        $name = $participant->name;
        $participant->delete();

        return redirect()->route('tenant.owner.participants.index')
            ->with('success', "Peserta '{$name}' berhasil dihapus dari sistem!");
    }
}
