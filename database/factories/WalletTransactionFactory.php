<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletTransactionFactory extends Factory
{
    protected $model = WalletTransaction::class;

    public function definition()
    {
        return [
            'wallet_id' => Wallet::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'description' => $this->faker->sentence(4),
        ];
    }
}
