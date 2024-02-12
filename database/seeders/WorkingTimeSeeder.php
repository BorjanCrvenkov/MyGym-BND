<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkingTime;
use Illuminate\Database\Seeder;

class WorkingTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(User $user): void
    {
        for ($i = random_int(1, 2); $i < 30; $i += 2) {
            $hour = 8 * random_int(1, 2) == 8 ? 8 : 14;
            $startTime = now()->addDays($i)->setTime($hour, 0, 0);
            $endTime = $startTime->copy()->addHours(6);

            WorkingTime::factory()->create([
                'start_time'          => $startTime,
                'end_time'            => $endTime,
                'working_schedule_id' => $user->working_schedule_id,
            ]);
        }
    }
}
