<?php
namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Deduct amount from wallet safely with DB transaction and pessimistic locking
     */
    public function deduct(int $userId, float $amount, string $description, ?string $reference = null)
    {
        if ($amount <= 0) return true;

        return DB::transaction(function () use ($userId, $amount, $description, $reference) {
            $user = User::lockForUpdate()->find($userId);

            if (!$user) {
                throw new \Exception("User not found.");
            }

            if ($user->wallet_balance < $amount) {
                throw new \Exception("Insufficient wallet balance.");
            }

            $user->wallet_balance -= $amount;
            $user->save();

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $amount,
                'description' => $description,
                'reference_id' => $reference,
                'balance_after' => $user->wallet_balance
            ]);

            return true;
        });
    }

    /**
     * Credit amount to wallet safely
     */
    public function credit(int $userId, float $amount, string $description, ?string $reference = null)
    {
        if ($amount <= 0) return true;

        return DB::transaction(function () use ($userId, $amount, $description, $reference) {
            $user = User::lockForUpdate()->find($userId);

            if (!$user) {
                throw new \Exception("User not found.");
            }

            $user->wallet_balance += $amount;
            $user->save();

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'description' => $description,
                'reference_id' => $reference,
                'balance_after' => $user->wallet_balance
            ]);

            return true;
        });
    }
}
