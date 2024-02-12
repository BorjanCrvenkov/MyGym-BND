<?php

namespace Tests\Unit\Jobs\Gym;

use App\Jobs\Gym\GymShutsDownJob;
use App\Jobs\Membership\RefundMembershipsJob;
use App\Jobs\User\NotifyGymClosingDownJob;
use App\Models\Gym;
use App\Models\Membership;
use App\Models\MembershipType;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GymShutsDownJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param Closure $data
     * @return void
     * @dataProvider handleDataProvider
     */
    public function testHandleMethod(Closure $data): void
    {
        Queue::fake();

        [$gym, $expectedJobs] = $data();

        (new GymShutsDownJob($gym, $gym->shutdown_date))->handle();

        if (!$expectedJobs) {
            Queue::assertNotPushed(RefundMembershipsJob::class);
            Queue::assertNotPushed(NotifyGymClosingDownJob::class);
            return;
        }

        foreach ($expectedJobs as $job) {
            Queue::assertPushed($job);
        }
    }

    /**
     * @return array[]
     */
    public static function handleDataProvider(): array
    {
        return [
            'Scenario 1: Gym is already shut down'                                                       => [
                'data' => function () {
                    $gym = Gym::factory()->create([
                        'shutdown'     => true,
                        'shutdown_date' => now(),
                    ]);

                    return [
                        $gym,
                        null,
                    ];
                }
            ],
            'Scenario 2: Gym shut down date is more than 30 days'                                        => [
                'data' => function () {
                    $gym = Gym::factory()->create([
                        'shutdown'     => true,
                        'shutdown_date' => now()->addYear(),
                    ]);

                    return [
                        $gym,
                        null,
                    ];
                }
            ],
            "Scenario 3: Gym shut down date is in less then 30 days and doesn't have active memberships" => [
                'data' => function () {
                    $gym = Gym::factory()->create([
                        'shutdown'     => false,
                        'shutdown_date' => now()->addDay(),
                    ]);

                    return [
                        $gym,
                        [
                            NotifyGymClosingDownJob::class,
                        ],
                    ];
                }
            ],
            "Scenario 4: Gym shut down date is in less then 30 days and has active memberships"          => [
                'data' => function () {
                    $gym = Gym::factory()->create([
                        'shutdown'     => false,
                        'shutdown_date' => now()->addDay(),
                    ]);

                    $membershipTypeId = MembershipType::factory()->create([
                        'gym_id'         => $gym->getKey(),
                        'duration_weeks' => 2,
                    ])->getKey();

                    Membership::factory()->create([
                        'start_date'         => now(),
                        'membership_type_id' => $membershipTypeId,
                    ]);

                    return [
                        $gym,
                        [
                            RefundMembershipsJob::class,
                            NotifyGymClosingDownJob::class,
                        ],
                    ];
                }
            ],
        ];
    }
}
