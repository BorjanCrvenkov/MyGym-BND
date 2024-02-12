<?php

namespace Tests\Unit\Jobs\Expense;

use App\Jobs\Expense\CreateRecurringExpenseJob;
use App\Models\ExpenseType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRecurringExpenseJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testHandleMethod(): void
    {
        $expenseType = ExpenseType::factory()->create([
            'recurring_every_number_of_days' => 3,
            'next_recurring_date'            => now()->toDateString(),
        ]);

        (new CreateRecurringExpenseJob())->handle();

        $this->assertDatabaseHas('expenses', ['expense_type_id' => $expenseType->getKey()]);

        $expenseType->refresh();

        $this->assertSame($expenseType->next_recurring_date, now()->addDays(3)->toDateString());
    }
}
