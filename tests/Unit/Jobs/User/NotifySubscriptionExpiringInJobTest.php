<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\NotifySubscriptionExpiringInJob;
use App\Models\Plan;
use App\Models\User;
use App\Notifications\Users\NotifyBusinessUsersAboutSubscriptionExpiringNotification;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifySubscriptionExpiringInJobTest extends TestCase
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

        [$users, $expiresInAWeek] = $data();

        (new NotifySubscriptionExpiringInJob($expiresInAWeek))->handle();

        foreach ($users as $user) {
            Notification::assertSentTo($user, NotifyBusinessUsersAboutSubscriptionExpiringNotification::class);
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

                    $planId = Plan::factory()->create()->getKey();

                    $user1 = User::factory()->create([
                        'plan_id' => $planId
                    ]);
                    DB::statement("INSERT INTO subscriptions (user_id, name, ends_at, stripe_id, stripe_status)
                             VALUES({$user1->getKey()}, 'test', '{$endDate}', 'test_1', '0')");

                    $user2 = User::factory()->create([
                        'plan_id' => $planId
                    ]);
                    DB::statement("INSERT INTO subscriptions (user_id, name, ends_at, stripe_id, stripe_status)
                             VALUES({$user2->getKey()}, 'test', '{$endDate}', 'test_2', '0')");

                    return [
                        [
                            $user1,
                            $user2,
                        ],
                        true,
                    ];
                }
            ],
            'Scenario 2: Expires in a day job'  => [
                'data' => function () {
                    $endDate = now()->addDay()->toDateString();

                    $planId = Plan::factory()->create()->getKey();

                    $user1 = User::factory()->create([
                        'plan_id' => $planId
                    ]);
                    DB::statement("INSERT INTO subscriptions (user_id, name, ends_at, stripe_id, stripe_status)
                             VALUES({$user1->getKey()}, 'test', '{$endDate}', 'test_1', '0')");

                    $user2 = User::factory()->create([
                        'plan_id' => $planId
                    ]);
                    DB::statement("INSERT INTO subscriptions (user_id, name, ends_at, stripe_id, stripe_status)
                             VALUES({$user2->getKey()}, 'test', '{$endDate}', 'test_2', '0')");

                    return [
                        [
                            $user1,
                            $user2,
                        ],
                        false,
                    ];
                }
            ],
        ];
    }
}
