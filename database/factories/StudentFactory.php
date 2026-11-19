<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'reg_number' => 'DBC/2026/'.fake()->unique()->numerify('####'),
            'course_id' => null,
            'level' => 'Year 1',
            'admission_session' => '2026/2027',
            'phone' => fake()->numerify('080########'),
            'gender' => fake()->randomElement(['male', 'female']),
            'status' => 'active',
        ];
    }
}
