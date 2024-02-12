<?php

namespace Database\Factories;

use App\Models\Gym;
use App\Models\MembershipType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MembershipType>
 */
class MembershipTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => $this->faker->word(),
            'description'    => $this->faker->paragraph(),
            'price'          => $this->faker->numberBetween(50, 1000),
            'duration_weeks' => 4,
            'gym_id'         => Gym::factory(),
        ];
    }
}
