<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{

    protected $model = Payment::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 24),
            'assessor_one_id' => 7,
            'assessor_two_id' => 24,
            'proof_of_payment' => '1697043830.png',
            'amount' => $this->faker->numberBetween(100000,500000),
            'status_accessor_one' => $this->faker->numberBetween(1, 3),
            'status_accessor_two' => $this->faker->numberBetween(1, 3),
            'description' => $this->faker->sentence,
        ];
    }
}
