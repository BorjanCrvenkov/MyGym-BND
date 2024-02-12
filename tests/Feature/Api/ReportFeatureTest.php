<?php

namespace Tests\Feature\Api;

use App\Models\Report;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFeatureTest;

class ReportFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Report::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }
}
