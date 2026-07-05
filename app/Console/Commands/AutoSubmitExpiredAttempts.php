<?php

namespace App\Console\Commands;

use App\Models\Central\Tenant;
use App\Models\Tenant\QuizAttempt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoSubmitExpiredAttempts extends Command
{
    protected $signature = 'quiz:auto-submit-expired';
    protected $description = 'Auto-submit quiz attempts that have expired but are still in_progress (safety net)';

    public function handle(): int
    {
        $totalSubmitted = 0;

        // Iterate through all active tenants
        $tenants = Tenant::where('is_active', true)->get();

        foreach ($tenants as $tenant) {
            try {
                tenancy()->initialize($tenant);

                $expiredAttempts = QuizAttempt::expiredNotSubmitted()->get();

                foreach ($expiredAttempts as $attempt) {
                    $attempt->submit('time_up');
                    $totalSubmitted++;

                    Log::info("Auto-submitted expired attempt", [
                        'tenant' => $tenant->slug,
                        'attempt_id' => $attempt->id,
                        'user_id' => $attempt->user_id,
                        'quiz_id' => $attempt->quiz_id,
                    ]);
                }

                tenancy()->end();
            } catch (\Exception $e) {
                Log::error("Error auto-submitting for tenant {$tenant->slug}: " . $e->getMessage());
                tenancy()->end();
            }
        }

        if ($totalSubmitted > 0) {
            $this->info("Auto-submitted {$totalSubmitted} expired attempt(s).");
        }

        return Command::SUCCESS;
    }
}
