<?php

namespace Database\Factories;

use App\Models\ExpenseType;
use App\Models\Gym;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpenseType>
 */
class ExpenseTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $recurringEveryNumberOfDays = $this->faker->numberBetween(1,365);

        return [
            'name'                           => $this->faker->word(),
            'description'                    => $this->faker->realText(),
            'cost'                           => $this->faker->numberBetween(10, 10000),
            'recurring'                      => true,
            'recurring_every_number_of_days' => $recurringEveryNumberOfDays,
            'next_recurring_date'            => now()->addDays($recurringEveryNumberOfDays)->toDateString(),
            'gym_id'                         => Gym::factory(),
        ];
    }

    /**
     * @return $this
     */
    public function nonRecurring(): static
    {
        return $this->state(fn(array $attributes) => [
            'recurring'                      => false,
            'recurring_every_number_of_days' => null,
            'next_recurring_date'            => null,
        ]);
    }
}
