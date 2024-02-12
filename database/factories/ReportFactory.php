<?php

namespace Database\Factories;

use App\Enums\ReportTypeEnum;
use App\Models\Gym;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_id'    => Gym::factory(),
            'model_type'  => ReportTypeEnum::GYM_PROBLEM->value,
            'reason'      => $this->faker->realText(),
            'heading'     => $this->faker->word(),
            'reporter_id' => User::factory(),
        ];
    }
}
