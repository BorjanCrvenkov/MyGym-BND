<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\File;
use App\Models\Gym;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => $this->faker->word(),
            'description'       => $this->faker->paragraph(),
            'price'             => $this->faker->numberBetween(5000, 50000),
            'last_service_date' => $this->faker->date(),
            'next_service_date' => $this->faker->dateTimeBetween('+0 days', '+10 years'),
            'gym_id'            => Gym::factory(),
        ];
    }
}
