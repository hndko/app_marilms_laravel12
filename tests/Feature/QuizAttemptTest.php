<?php

namespace Tests\Feature;

use App\Models\Tenant\Quiz;
use App\Models\Tenant\QuizAttempt;
use App\Models\Tenant\User as Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizAttemptTest extends TestCase
{
    public function test_server_authoritative_timer_calculation()
    {
        $attempt = new QuizAttempt([
            'started_at' => now()->subSeconds(100),
            'total_duration_seconds' => 600, // 10 minutes = 600s
            'status' => 'in_progress',
        ]);

        $remaining = $attempt->getRemainingSeconds();

        // Should be around 500 seconds remaining
        $this->assertGreaterThanOrEqual(498, $remaining);
        $this->assertLessThanOrEqual(500, $remaining);
        $this->assertFalse($attempt->isExpired());
    }

    public function test_expired_attempt_returns_zero_remaining_seconds()
    {
        $attempt = new QuizAttempt([
            'started_at' => now()->subSeconds(700),
            'total_duration_seconds' => 600,
            'status' => 'in_progress',
        ]);

        $this->assertEquals(0, $attempt->getRemainingSeconds());
        $this->assertTrue($attempt->isExpired());
    }
}
