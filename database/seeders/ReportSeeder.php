<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use App\Models\Gym;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Gym $gym): void
    {
        $memberRoleId = Role::query()->where('name', '=', UserRolesEnum::MEMBER->value)->first()->getKey();

        $userId = User::query()
            ->where('role_id', '=', $memberRoleId)
            ->inRandomOrder()->first()->getKey();

        Report::factory(5)->create([
            'model_id'      => $gym->getKey(),
            'reporter_id' => $userId,
        ]);
    }
}
