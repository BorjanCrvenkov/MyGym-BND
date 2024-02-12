<?php

namespace Tests\Feature\Filter;

use App\Models\Membership;
use App\Models\Session;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class SessionFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Session::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testEmailFilter(): void
    {
        $membershipId = Membership::factory()->create()->getKey();

        $expectedIds = Session::factory(2)->create([
            'membership_id' => $membershipId,
        ])->modelKeys();

        Session::factory()->create();

        $filters = [
            'membership_id' => $membershipId,
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
