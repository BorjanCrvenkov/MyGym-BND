<?php

use App\Enums\UserRolesEnum;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roleNames = UserRolesEnum::getAllValuesAsArray();

        foreach ($roleNames as $name){
            Role::factory()->create([
                'name' => $name,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
