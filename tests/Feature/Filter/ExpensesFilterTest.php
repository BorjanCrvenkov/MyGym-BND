<?php

namespace Tests\Feature\Filter;

use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Gym;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class ExpensesFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Expense::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gym = Gym::factory()->create();

        $expenseType = ExpenseType::factory()->create([
            'gym_id' => $gym->getKey(),
        ]);

        $expectedIds = Expense::factory(2)->create([
            'expense_type_id' => $expenseType->getKey()
        ])->modelKeys();

        Expense::factory()->create();

        $filters = [
            'gym_id' => $gym->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
