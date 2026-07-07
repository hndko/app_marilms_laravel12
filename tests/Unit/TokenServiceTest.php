<?php

namespace Tests\Unit;

use App\Services\TokenService;
use Tests\TestCase;

class TokenServiceTest extends TestCase
{
    public function test_calculate_cost_returns_correct_tokens_per_question()
    {
        $service = new TokenService();
        $cost = $service->calculateCost(10); // 10 questions * 5 tokens = 50

        $this->assertEquals(50, $cost);
    }
}
