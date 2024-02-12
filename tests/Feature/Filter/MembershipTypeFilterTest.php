<?php

namespace Tests\Feature\Filter;

use App\Models\Gym;
use App\Models\MembershipType;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class MembershipTypeFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(MembershipType::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gym = Gym::factory()->create();

        $expectedIds = MembershipType::factory(2)->create([
            'gym_id' => $gym->getKey(),
        ])->modelKeys();

        MembershipType::factory()->create();

        $filters = [
            'gym_id' => $gym->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
