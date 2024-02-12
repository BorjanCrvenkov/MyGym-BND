<?php

namespace Tests\Unit\Services;

use App\Models\WorkingTime;
use App\Services\WorkingTimeService;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class WorkingTimeServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(WorkingTimeService::class);
        $this->model = App::make(WorkingTime::class);
        $this->shouldAssert = true;
    }
}
