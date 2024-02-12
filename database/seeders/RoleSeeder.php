<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleNames = UserRolesEnum::getAllValuesAsArray();

        foreach ($roleNames as $name){
            $exists = Role::query()->where('name', '=', $name)->exists();

            if(!$exists){
                User::factory()->create([
                    'name' => $name,
                ]);
            }
        }
    }
}
