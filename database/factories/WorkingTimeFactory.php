<?php

namespace Database\Factories;

use App\Models\WorkingSchedule;
use App\Models\WorkingTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkingTime>
 */
class WorkingTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = Carbon::parse($this->faker->dateTimeBetween('now', '+100 days'));

        return [
            'start_time'          => $startTime,
            'end_time'            => $startTime->addHours(8),
            'working_schedule_id' => WorkingSchedule::factory(),
        ];
    }
}
