<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\NotifyMembershipExpiringInJob;
use App\Models\Membership;
use App\Notifications\Users\NotifyMemberUsersAboutMembershipExpiringInNotification;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyMembershipExpiringInJobTest  extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     * @dataProvider handleDataProvider
     */
    public function testHandleMethod(Closure $data)
    {
        Carbon::setTestNow(now());
        Notification::fake();

        [$memberships, $expiresInAWeek] = $data();

        (new NotifyMembershipExpiringInJob($expiresInAWeek))->handle();

        foreach ($memberships as $membership) {
            Notification::assertSentTo($membership->user, NotifyMemberUsersAboutMembershipExpiringInNotification::class);
        }
    }

    /**
     * @return array[]
     */
    public static function handleDataProvider(): array
    {
        return [
            'Scenario 1: Expires in a week job' => [
                'data' => function () {
                    $endDate = now()->addWeek()->toDateString();

                    $membership1 = Membership::factory()->create();
                    $membership1->update([
                        'end_date' => $endDate,
                    ]);

                    $membership2 = Membership::factory()->create();
                    $membership2->update([
                        'end_date' => $endDate,
                    ]);

                    return [
                        [
                            $membership1,
                            $membership2,
                        ],
                        true,
                    ];
                }
            ],
            'Scenario 2: Expires in a day job' => [
                'data' => function () {
                    $endDate = now()->addDay()->toDateString();

                    $membership1 = Membership::factory()->create();
                    $membership1->update([
                        'end_date' => $endDate,
                    ]);

                    $membership2 = Membership::factory()->create();
                    $membership2->update([
                        'end_date' => $endDate,
                    ]);

                    return [
                        [
                            $membership1,
                            $membership2,
                        ],
                        false,
                    ];
                }
            ],
        ];
    }
}
