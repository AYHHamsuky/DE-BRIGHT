<?php

namespace Database\Factories;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fee>
 */
class FeeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['School fees', 'Acceptance fee', 'Examination fee']),
            'session' => '2026/2027',
            'course_id' => null,
            'amount' => 5_000_000,
            'is_active' => true,
        ];
    }
}
