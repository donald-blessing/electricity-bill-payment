<?php

namespace Database\Factories;

use App\Enums\BillProvider;
use App\Enums\BillStatus;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bill>
 */
class BillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = BillStatus::toArray();

        $provider = BillProvider::toArray();
        return [
            'amount'   => $this->faker->randomFloat(2, 1000, 100000),
            'status'   => $status[array_rand($status)],
            'user_id'  => User::factory(),
            'provider' => $provider[array_rand($provider)],
        ];
    }
}
