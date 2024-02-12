<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\NotifyGymClosingDownJob;
use App\Models\Gym;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Notifications\Users\GymClosingDownNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyGymClosingDownJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testHandleMethod(): void
    {
        Notification::fake();

        $gym = Gym::factory()->create();

        $membershipTypeId = MembershipType::factory()->create([
            'gym_id' => $gym->getKey(),
        ])->getKey();

        $membershipsWithExpectedNotification = Membership::factory(2)->create([
            'start_date'         => now(),
            'membership_type_id' => $membershipTypeId,
        ]);

        $membershipWithNotExpectedNotification = Membership::factory()->create([
            'start_date'         => now()->subYear(),
            'membership_type_id' => $membershipTypeId,
        ]);

        (new NotifyGymClosingDownJob($gym))->handle();

        foreach ($membershipsWithExpectedNotification as $membership) {
            Notification::assertSentTo($membership->user, GymClosingDownNotification::class);
        }

        Notification::assertNotSentTo($membershipWithNotExpectedNotification->user, GymClosingDownNotification::class);
    }
}
