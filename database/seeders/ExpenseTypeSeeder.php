<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\ExpenseType;
use App\Models\Gym;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * @param ExpenseSeeder $expenseSeeder
     */
    public function __construct(public ExpenseSeeder $expenseSeeder)
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    }

    /**
     * @param Equipment $equipment
     * @return void
     * @throws Exception
     */
    public function seedEquipmentExpenseType(Equipment $equipment): void
    {
        $expenseType = ExpenseType::factory()->nonRecurring()->create([
            'name'   => "{$equipment->name} expense",
            'gym_id' => $equipment->gym_id,
        ]);

        $this->expenseSeeder->run($expenseType);
    }

    /**
     * @param User $employee
     * @return void
     * @throws Exception
     */
    public function seedEmployeeExpenseType(User $employee): void
    {
        $expenseType = ExpenseType::factory()->create([
            'name'   => "{$employee->full_name} salary",
            'description' => '',
            'gym_id' => $employee->gym_id,
        ]);

        $this->expenseSeeder->run($expenseType);
    }
}
