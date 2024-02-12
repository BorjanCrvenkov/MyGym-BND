<?php

namespace Tests\Unit\Services;

use App\Models\WorkingSchedule;
use App\Services\WorkingScheduleService;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class WorkingScheduleServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(WorkingScheduleService::class);
        $this->model = App::make(WorkingSchedule::class);
        $this->shouldAssert = true;
    }
}
