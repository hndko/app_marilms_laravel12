<?php

namespace App\Services;

use App\Contracts\WaGatewayContract;
use App\Models\Central\SystemSetting;
use App\Services\WaGateways\FonnteDriver;
use App\Services\WaGateways\LogDriver;
use App\Services\WaGateways\WablastDriver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send a notification for a specific event type.
     *
     * @param string $eventType Event name (e.g. participant_invited, attempt_submitted, etc.)
     * @param mixed $recipient User/Participant model or object with email & phone properties
     * @param array $data Dynamic data payload for templates
     * @param bool $sendWa Whether to send WhatsApp message
     * @param bool $sendEmail Whether to send Email
     */
    public function send(string $eventType, $recipient, array $data = [], bool $sendWa = true, bool $sendEmail = false): bool
    {
        $phone = $recipient->phone ?? $recipient->whatsapp ?? '';
        $email = $recipient->email ?? '';
        $name = $recipient->name ?? 'Pengguna';

        $waMessage = $this->formatWaTemplate($eventType, $name, $data);
        $emailSubject = $this->getEmailSubject($eventType, $data);
        $emailBody = $this->formatEmailTemplate($eventType, $name, $data);

        $success = true;

        // 1. Send WhatsApp
        if ($sendWa && !empty($phone)) {
            $driver = $this->resolveWaDriver();
            $waResult = $driver->send($phone, $waMessage);
            if (!$waResult) {
                $success = false;
            }
        }

        // 2. Send Email (Optional/Fallback)
        if ($sendEmail && !empty($email)) {
            try {
                // Using simple raw email or logging for now
                Log::info("Email sent to {$email} | Subject: {$emailSubject}\nBody: {$emailBody}");
            } catch (\Exception $e) {
                Log::error("Failed sending email to {$email}: " . $e->getMessage());
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Resolve the active WhatsApp gateway driver.
     */
    protected function resolveWaDriver(): WaGatewayContract
    {
        $driverName = SystemSetting::getValue('wa_gateway_driver', 'log');
        $apiKey = SystemSetting::getValue('wa_gateway_key', '');

        return match (strtolower($driverName)) {
            'fonnte' => new FonnteDriver($apiKey),
            'wablast' => new WablastDriver($apiKey),
            default => new LogDriver(),
        };
    }

    /**
     * Format WhatsApp text template in clean Indonesian.
     */
    protected function formatWaTemplate(string $eventType, string $name, array $data): string
    {
        $tenantName = strtoupper(tenant('slug') ?? 'MARILMS');

        return match ($eventType) {
            'participant_invited' => "🎉 *Selamat Datang di Portal Ujian {$tenantName}!*\n\n"
                . "Halo *{$name}*, Anda telah didaftarkan sebagai peserta ujian.\n\n"
                . "🔑 *Kredensial Login Anda:*\n"
                . "• Email: " . ($data['email'] ?? '-') . "\n"
                . "• Password: " . ($data['password'] ?? 'password123') . "\n\n"
                . "🌐 *Link Portal:* " . ($data['url'] ?? url('/')) . "\n\n"
                . "Silakan login dan ubah password Anda demi keamanan. Selamat belajar dan semoga sukses!",

            'quiz_published' => "📢 *Kuis Baru Telah Tersedia!*\n\n"
                . "Halo *{$name}*, pengajar baru saja menerbitkan kuis baru di portal {$tenantName}:\n\n"
                . "📝 *Judul Kuis:* " . ($data['quiz_title'] ?? 'Ujian') . "\n"
                . "⏱️ *Durasi:* " . ($data['time_limit'] ?? '-') . " Menit\n"
                . "🎯 *Passing Score:* " . ($data['passing_score'] ?? '70') . "%\n\n"
                . "Silakan login ke portal untuk mengerjakan kuis sebelum batas waktu berakhir.",

            'quiz_reminder' => "⏰ *Pengingat Pengerjaan Kuis!*\n\n"
                . "Halo *{$name}*, ini adalah pengingat ramah bahwa kuis *" . ($data['quiz_title'] ?? 'Ujian') . "* masih menunggu untuk dikerjakan.\n\n"
                . "Segera login ke portal {$tenantName} dan selesaikan ujian Anda sebelum batas waktu.",

            'attempt_submitted' => "📊 *Hasil Evaluasi Ujian*\n\n"
                . "Halo *{$name}*, Anda telah menyelesaikan kuis *" . ($data['quiz_title'] ?? '-') . "*.\n\n"
                . "📈 *Skor Akhir:* *" . ($data['score'] ?? 0) . " / 100*\n"
                . "🏆 *Status:* *" . (($data['passed'] ?? false) ? 'LULUS ✅' : 'BELUM LULUS ⚠️') . "*\n\n"
                . "Lihat pembahasan dan rincian jawaban selengkapnya di portal peserta {$tenantName}.",

            'token_purchased' => "💰 *Pembelian Token AI Berhasil!*\n\n"
                . "Halo *{$name}*, pembayaran Anda telah dikonfirmasi oleh sistem.\n\n"
                . "💎 *Token Ditambahkan:* +" . number_format($data['tokens'] ?? 0) . " Token\n"
                . "💳 *Total Saldo Sekarang:* " . number_format($data['total_balance'] ?? 0) . " Token\n\n"
                . "Terima kasih telah menggunakan layanan MariLMS AI!",

            'token_low_warning' => "⚠️ *Peringatan: Saldo Token AI Menipis!*\n\n"
                . "Halo *{$name}*, sisa saldo token AI Anda di portal {$tenantName} saat ini tinggal *" . number_format($data['balance'] ?? 0) . " Token*.\n\n"
                . "Segera lakukan Top Up token agar pembuatan kuis otomatis dengan AI tidak terhambat.",

            'password_reset' => "🔐 *Reset Password Akun*\n\n"
                . "Halo *{$name}*, password akun Anda di portal {$tenantName} telah direset.\n\n"
                . "🔑 *Password Baru:* " . ($data['new_password'] ?? 'password123') . "\n\n"
                . "Silakan login dan segera ganti password Anda.",

            default => "🔔 *Notifikasi Sistem {$tenantName}*\n\nHalo *{$name}*, ada pembaruan baru untuk akun Anda. Silakan cek portal untuk info lebih lanjut."
        };
    }

    /**
     * Get email subject line.
     */
    protected function getEmailSubject(string $eventType, array $data): string
    {
        return match ($eventType) {
            'participant_invited' => 'Undangan & Kredensial Login Portal Ujian',
            'quiz_published' => 'Kuis Baru Tersedia: ' . ($data['quiz_title'] ?? ''),
            'quiz_reminder' => 'Pengingat Kuis: ' . ($data['quiz_title'] ?? ''),
            'attempt_submitted' => 'Hasil Nilai Ujian: ' . ($data['quiz_title'] ?? ''),
            'token_purchased' => 'Konfirmasi Pembelian Token AI Berhasil',
            'token_low_warning' => 'Peringatan Saldo Token AI Menipis',
            'password_reset' => 'Password Akun Anda Telah Direset',
            default => 'Notifikasi Sistem MariLMS'
        };
    }

    /**
     * Format email HTML body template.
     */
    protected function formatEmailTemplate(string $eventType, string $name, array $data): string
    {
        $waText = $this->formatWaTemplate($eventType, $name, $data);
        return "<div style='font-family: Arial, sans-serif; padding: 20px; color: #333; line-height: 1.6;'>"
            . "<h2 style='color: #6366F1;'>MariLMS AI Notification</h2>"
            . "<div style='background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; white-space: pre-line;'>"
            . $waText
            . "</div>"
            . "<p style='font-size: 12px; color: #64748b; margin-top: 20px;'>Pesan ini dikirim secara otomatis oleh sistem MariLMS AI.</p>"
            . "</div>";
    }
}
