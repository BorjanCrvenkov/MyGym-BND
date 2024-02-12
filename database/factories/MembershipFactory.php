<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * @return MembershipFactory
     */
    public function configure(): MembershipFactory
    {
        return $this->afterCreating(function (Membership $membership) {
            $membershipType = MembershipType::query()->find($membership->membership_type_id);
            $gym = $membershipType->gym;
            $endDate = Carbon::parse($membership->start_date)->addWeeks($membershipType->duration_weeks)->toDateString();

            $membership->update([
                'name'                           => $gym->name . ' - ' . $membershipType->name,
                'end_date'                       => $endDate,
                'original_membership_type_model' => json_encode($membershipType),
                'gym_id'                         => $membershipType->gym_id,
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
            'start_date'         => $this->faker->dateTimeThisYear()->format('Y-m-d'),
            'end_date'           => $this->faker->date(),
            'user_id'            => User::factory(),
            'membership_type_id' => MembershipType::factory(),
            'active_session_id'  => null,
        ];
    }
}
