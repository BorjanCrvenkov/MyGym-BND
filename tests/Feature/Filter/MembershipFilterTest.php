<?php

namespace Tests\Feature\Filter;

use App\Models\Gym;
use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\Session;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class MembershipFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Membership::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testUserFilter(): void
    {
        $userId = User::factory()->create()->getKey();

        $expectedIds = Membership::factory(2)->create([
            'user_id' => $userId,
        ])->modelKeys();

        Membership::factory()->create();

        $filters = [
            'user_id' => $userId,
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }

    /**
     * @return void
     */
    public function testMembershipTypeFilter(): void
    {
        $membershipTypeId = MembershipType::factory()->create()->getKey();

        $expectedIds = Membership::factory(2)->create([
            'membership_type_id' => $membershipTypeId,
        ])->modelKeys();

        Membership::factory()->create();

        $filters = [
            'membership_type_id' => $membershipTypeId,
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }

    /**
     * @param Closure $data
     * @return void
     * @dataProvider startedSessionsFilterDataProvider
     */
    public function testStartedSessionsFilter(Closure $data): void
    {
        [$expectedIds, $filterValue] = $data();

        $filters = [
            'started_sessions' => $filterValue,
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }

    /**
     * @return array[]
     */
    public static function startedSessionsFilterDataProvider(): array
    {
        return [
            'Scenario 1: started_sessions is true'  => [
                'data' => function () {
                    $membershipId = Membership::factory()->create()->getKey();

                    Membership::factory()->create();

                    Session::factory()->active()->create([
                        'membership_id' => $membershipId,
                        'time_end'      => null,
                    ]);

                    return [
                        [$membershipId],
                        true,
                    ];
                }
            ],
            'Scenario 2: started_sessions is false' => [
                'data' => function () {
                    $membershipId = Membership::factory()->create()->getKey();

                    Session::factory()->create([
                        'membership_id' => $membershipId,
                    ]);

                    $membership = Membership::factory()->create();

                    Session::factory()->active()->create([
                        'membership_id' => $membership->getKey(),
                    ]);

                    return [
                        [$membershipId],
                        false,
                    ];
                }
            ],
        ];
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gymId = Gym::factory()->create()->getKey();

        $membershipTypeId = MembershipType::factory()->create([
            'gym_id' => $gymId,
        ])->getKey();

        $expectedIds = Membership::factory(2)->create([
            'membership_type_id' => $membershipTypeId,
        ])->modelKeys();

        Membership::factory()->create();

        $filters = [
            'gym_id' => $gymId,
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
