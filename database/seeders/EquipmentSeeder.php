<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Gym;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * @param ExpenseTypeSeeder $expenseTypeSeeder
     * @param FileSeeder $fileSeeder
     */
    public function __construct(public ExpenseTypeSeeder $expenseTypeSeeder, public FileSeeder $fileSeeder)
    {
    }


    /**
     * Run the database seeds.
     */
    public function run(Gym $gym, int $count = 10): void
    {
        $equipments = Equipment::factory($count)->create([
            'gym_id' => $gym->getKey()
        ]);

        foreach ($equipments as $equipment){
            $this->expenseTypeSeeder->seedEquipmentExpenseType($equipment);
            $this->fileSeeder->createEquipmentImage($equipment);
        }
    }
}
