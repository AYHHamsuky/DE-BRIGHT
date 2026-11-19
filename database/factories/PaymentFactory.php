<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'fee_id' => Fee::factory(),
            'reference' => Payment::newReference(),
            'amount' => 5_000_000,
            'method' => PaymentMethod::Paystack,
            'status' => PaymentStatus::Pending,
        ];
    }

    public function successful(): static
    {
        return $this->state(fn (array $attributes) => ['status' => PaymentStatus::Successful, 'paid_at' => now()]);
    }
}
