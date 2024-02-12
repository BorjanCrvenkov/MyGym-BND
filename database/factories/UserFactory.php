<?php

namespace Database\Factories;

use App\Enums\UserRolesEnum;
use App\Models\File;
use App\Models\Gym;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * @return UserFactory
     */
    public function configure(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $imageType = random_int(1,2) == 1 ? 'men' : 'women';

            $randomImageNumber = random_int(0,70);

            File::factory()->create([
                'link' => "https://randomuser.me/api/portraits/{$imageType}/{$randomImageNumber}.jpg",
                'model_id' => $user->getKey()
            ]);

            if ($user->is_business || $user->is_employee) {
                File::factory()->userIdentificationFile()->create([
                    'model_id' => $user->getKey()
                ]);

                if (!$user->working_schedule_id) {
                    $user->update([
                        'working_schedule_id' => WorkingSchedule::factory()->create([
                            'user_id' => $user->getKey(),
                        ])->getKey(),
                    ]);
                }
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = Role::query()->where('name', '=', UserRolesEnum::MEMBER->value)->first();
        $roleId = $role ? $role->getKey() : Role::factory()->create();

        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'email'             => $this->faker->unique()->email(),
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'username'          => $this->faker->unique()->userName(),
            'role_id'           => $roleId,
            'date_of_birth'     => $this->faker->date(max: now()->subYears(20)),
            'bio'               => $this->faker->paragraph(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * @return $this
     */
    public function administrator(): static
    {
        $role = Role::query()->where('name', '=', UserRolesEnum::ADMINISTRATOR->value)->first();
        $roleId = $role ? $role->getKey() : Role::factory()->create();

        return $this->state(function (array $attributes) use ($roleId) {
            return [
                'date_of_birth'      => $this->faker->date(max: now()->subYears(20)),
                'date_of_employment' => now(),
                'bio'                => $this->faker->paragraph(),
                'role_id'            => $roleId,
            ];
        });
    }

    /**
     * @return $this
     */
    public function business(): static
    {
        $role = Role::query()->where('name', '=', UserRolesEnum::BUSINESS->value)->first();
        $roleId = $role ? $role->getKey() : Role::factory()->create();

        $planId = Plan::query()->inRandomOrder()->first()->getKey() ?? Plan::factory()->create();

        return $this->state(function (array $attributes) use ($roleId, $planId) {
            return [
                'date_of_birth' => $this->faker->date(max: now()->subYears(20)),
                'role_id'       => $roleId,
                'plan_id'       => $planId,
            ];
        });
    }

    /**
     * @return $this
     */
    public function employee(): static
    {
        $role = Role::query()->where('name', '=', UserRolesEnum::EMPLOYEE->value)->first();
        $roleId = $role ? $role->getKey() : Role::factory()->create();

        return $this->state(function (array $attributes) use ($roleId) {
            return [
                'date_of_birth'      => $this->faker->date(max: now()->subYears(20)),
                'date_of_employment' => now(),
                'bio'                => $this->faker->paragraph(),
                'role_id'            => $roleId,
                'gym_id'             => Gym::factory(),
            ];
        });
    }
}
