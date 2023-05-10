<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Revenue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Revenue>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->text(),
            'value' => fake()->randomFloat(2, 0.1, 10000),
            'paid_at' => now()->format('Y-m-d'),
            'category_id' => 8,
        ];
    }
}
