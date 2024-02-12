<?php

namespace Database\Factories;

use App\Enums\FileTypeEnum;
use App\Models\File;
use App\Models\Gym;
use App\Models\SocialMediaLinks;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gym>
 */
class GymFactory extends Factory
{
    /**
     * @return GymFactory
     */
    public function configure(): GymFactory
    {
        return $this->afterCreating(function (Gym $gym) {
             SocialMediaLinks::factory()->create([
                'gym_id' => $gym->getKey(),
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
        $workingHours = '
        {
	        "Monday": {
		        "start_time": "08:00",
		        "end_time": "17:00"
	        },
            "Tuesday": {
                "start_time": "08:00",
                "end_time": "17:00"
            },
            "Wednesday": {
                "start_time": "08:00",
                "end_time": "17:00"
            },
            "Thursday": {
                "start_time": "08:00",
                "end_time": "17:00"
            },
            "Friday": {
                "start_time": "08:00",
                "end_time": "17:00"
            },
	        "Saturday": {},
	        "Sunday": {}
        }';

        return [
            'name'          => $this->faker->company(),
            'address'       => $this->faker->streetAddress(),
            'date_opened'   => $this->faker->dateTimeThisDecade(),
            'working_times' => $workingHours,
            'phone_number'  => $this->faker->phoneNumber(),
            'owner_id'      => User::factory()->business(),
            'email'         => $this->faker->email(),
            'shutdown'     => false,
            'shutdown_date' => null,
        ];
    }
}
