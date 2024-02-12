<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseType;
use Exception;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(ExpenseType $expenseType): void
    {
        $paid = (bool)random_int(0, 1);

        if ($paid) {
            Expense::factory()->paid()->create([
                'expense_type_id' => $expenseType->getKey(),
            ]);
        } else {
            Expense::factory()->create([
                'expense_type_id' => $expenseType->getKey(),
            ]);
        }
    }
}
