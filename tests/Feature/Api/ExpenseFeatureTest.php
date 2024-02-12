<?php

namespace Tests\Feature\Api;

use App\Models\Expense;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFeatureTest;

class ExpenseFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Expense::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }
}
