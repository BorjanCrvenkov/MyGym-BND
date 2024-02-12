<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                        => $this->faker->word(),
            'stripe_plan'                 => $this->faker->word(),
            'price'                       => $this->faker->randomNumber(),
            'description'                 => $this->faker->realText,
            'number_of_allowed_gyms'      => $this->faker->randomDigit(),
            'number_of_allowed_employees' => $this->faker->randomDigit(),
            'duration_months'             => 1,
        ];
    }
}
