<?php

namespace App\Services;

use App\Models\Central\Owner;
use App\Models\Central\OwnerTokenBalance;
use App\Models\Central\TokenTransaction;
use Illuminate\Support\Facades\DB;

class TokenService
{
    /**
     * Check if owner has enough tokens.
     */
    public function checkBalance(Owner $owner, int $required): bool
    {
        if ($this->isUnlimited($owner)) {
            return true;
        }

        return $this->getBalance($owner) >= $required;
    }

    /**
     * Calculate token cost based on number of questions.
     */
    public function calculateCost(int $questionCount, int $costPerQuestion = 5): int
    {
        return $questionCount * $costPerQuestion;
    }

    /**
     * Deduct tokens from owner's balance.
     *
     * @throws \Exception if insufficient balance
     */
    public function deduct(Owner $owner, int $amount, string $source, string $referenceId, string $note = ''): void
    {
        if ($this->isUnlimited($owner)) {
            // Log the transaction but don't change balance
            $this->logTransaction($owner, 'debit', $amount, $source, $referenceId, $note . ' (unlimited - tidak dikurangi)');
            return;
        }

        DB::transaction(function () use ($owner, $amount, $source, $referenceId, $note) {
            // Lock the row to prevent race conditions
            $balance = OwnerTokenBalance::where('owner_id', $owner->id)->lockForUpdate()->first();

            if (!$balance || $balance->balance < $amount) {
                throw new \Exception("Saldo token tidak mencukupi. Diperlukan: {$amount}, Tersedia: " . ($balance->balance ?? 0));
            }

            $balance->decrement('balance', $amount);

            $this->logTransaction($owner, 'debit', $amount, $source, $referenceId, $note);
        });
    }

    /**
     * Credit tokens to owner's balance.
     */
    public function credit(Owner $owner, int $amount, string $source, string $referenceId, string $note = ''): void
    {
        DB::transaction(function () use ($owner, $amount, $source, $referenceId, $note) {
            $balance = OwnerTokenBalance::firstOrCreate(
                ['owner_id' => $owner->id],
                ['balance' => 0, 'is_unlimited' => false]
            );

            $balance->increment('balance', $amount);

            $this->logTransaction($owner, 'credit', $amount, $source, $referenceId, $note);
        });
    }

    /**
     * Check if owner has unlimited tokens.
     */
    public function isUnlimited(Owner $owner): bool
    {
        return $owner->type === 'unlimited_token' || ($owner->tokenBalance?->is_unlimited ?? false);
    }

    /**
     * Get current balance.
     */
    public function getBalance(Owner $owner): int
    {
        return $owner->tokenBalance?->balance ?? 0;
    }

    /**
     * Toggle unlimited token status.
     */
    public function toggleUnlimited(Owner $owner, bool $unlimited): void
    {
        $balance = OwnerTokenBalance::firstOrCreate(
            ['owner_id' => $owner->id],
            ['balance' => 0]
        );

        $balance->update(['is_unlimited' => $unlimited]);

        $owner->update(['type' => $unlimited ? 'unlimited_token' : 'regular']);
    }

    /**
     * Manual top-up by SuperAdmin.
     */
    public function manualTopUp(Owner $owner, int $amount, string $note = 'Top-up manual oleh SuperAdmin'): void
    {
        $this->credit($owner, $amount, 'manual_topup', 'admin', $note);
    }

    /**
     * Log a token transaction.
     */
    private function logTransaction(Owner $owner, string $type, int $amount, string $source, string $referenceId, string $note): void
    {
        TokenTransaction::create([
            'owner_id' => $owner->id,
            'type' => $type,
            'amount' => $amount,
            'source' => $source,
            'reference_id' => $referenceId,
            'note' => $note,
            'created_at' => now(),
        ]);
    }
}
