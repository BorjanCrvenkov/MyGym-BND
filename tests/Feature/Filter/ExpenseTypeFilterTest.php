<?php

namespace Tests\Feature\Filter;

use App\Models\ExpenseType;
use App\Models\Gym;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class ExpenseTypeFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(ExpenseType::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gym = Gym::factory()->create();

        $expectedIds = ExpenseType::factory(2)->create([
            'gym_id' => $gym->getKey(),
        ])->modelKeys();

        ExpenseType::factory()->create();

        $filters = [
            'gym_id' => $gym->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
