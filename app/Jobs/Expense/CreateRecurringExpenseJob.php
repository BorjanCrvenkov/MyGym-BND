<?php

namespace App\Jobs\Expense;

use App\Enums\ExpenseStatusEnum;
use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateRecurringExpenseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $expenseTypes = $this->getExpenseTypesWithTodayNextRecurringDate();

        foreach ($expenseTypes as $expenseType) {
            Expense::query()->create([
                'name'            => $expenseType->name,
                'status'          => ExpenseStatusEnum::NOT_PAID,
                'paid_at'         => null,
                'expense_type_id' => $expenseType->getKey(),
            ]);

            $expenseType->update([
                'next_recurring_date' => now()->addDays($expenseType->recurring_every_number_of_days)->toDateString(),
            ]);
        }
    }

    /**
     * @return Collection|array
     */
    public function getExpenseTypesWithTodayNextRecurringDate(): Collection|array
    {
        return ExpenseType::query()
            ->where('next_recurring_date', '=', now()->toDateString())
            ->get();
    }
}
