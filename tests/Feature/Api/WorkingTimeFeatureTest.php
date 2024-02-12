<?php

namespace Tests\Feature\Api;

use App\Models\WorkingTime;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFeatureTest;

class WorkingTimeFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(WorkingTime::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }
}
