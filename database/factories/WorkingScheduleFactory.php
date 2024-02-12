<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkingSchedule>
 */
class WorkingScheduleFactory extends Factory
{
    /**
     * @return WorkingScheduleFactory
     */
    public function configure(): WorkingScheduleFactory
    {
        return $this->afterCreating(function (WorkingSchedule $workingSchedule) {
            $user = $workingSchedule->user;
            $workingSchedule->update([
                'name' => $user->full_name . ' Shift',
                'gym_id' => $user->gym_id,
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->employee(),
        ];
    }
}
