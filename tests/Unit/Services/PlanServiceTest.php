<?php

namespace Tests\Unit\Services;

use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class PlanServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(PlanService::class);
        $this->model = App::make(Plan::class);
        $this->shouldAssert = true;
    }

    /**
     * @return void
     */
    public function testIndex(): void
    {
        $expectedIds = [
            1,
            2,
            3,
            4,
            5,
            6,
        ];

        $actualIds = $this->service->index()->modelKeys();

        $this->assertEqualsCanonicalizing($expectedIds, $actualIds);
    }
}
