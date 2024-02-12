<?php

namespace Tests\Feature\Filter;

use App\Models\Gym;
use App\Models\Report;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class ReportFilterTest  extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Report::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gymId = Gym::factory()->create()->getKey();

        $expectedIds = Report::factory(2)->create([
            'model_id' => $gymId,
        ])->modelKeys();

        Report::factory()->create();

        $filters = [
            'gym_id' => $gymId,
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
