<?php

namespace Database\Factories;

use App\Enums\ExpenseStatusEnum;
use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * @return ExpenseFactory
     */
    public function configure(): ExpenseFactory
    {
        return $this->afterCreating(function (Expense $expense) {
            $expenseType = $expense->expense_type;

            $expense->update([
                'name' => $expenseType->name,
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status'          => ExpenseStatusEnum::NOT_PAID->value,
            'expense_type_id' => ExpenseType::factory(),
        ];
    }

    /**
     * @return $this
     */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status'  => ExpenseStatusEnum::PAID->value,
            'paid_at' => now(),
        ]);
    }

}
