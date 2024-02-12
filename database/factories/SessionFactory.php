<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\Session;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Session>
 */
class SessionFactory extends Factory
{
    /**
     * @return SessionFactory
     */
    public function configure(): SessionFactory
    {
        return $this->afterCreating(function (Session $session) {
            if(!$session->time_end){
                Membership::query()
                    ->find($session->membership_id)
                    ->update([
                        'active_session_id' => $session->getKey()
                    ]);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'time_end'      => now()->addHours(random_int(1, 2))->toDateString(),
            'membership_id' => Membership::factory(),
        ];
    }

    /**
     * @return $this
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'time_end' => null,
        ]);
    }
}
