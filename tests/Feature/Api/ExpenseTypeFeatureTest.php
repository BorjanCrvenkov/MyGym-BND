<?php

namespace Tests\Feature\Api;

use App\Models\ExpenseType;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFeatureTest;

class ExpenseTypeFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(ExpenseType::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }
}
