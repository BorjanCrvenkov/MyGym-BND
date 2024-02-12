<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(MembershipType $membershipType, User $user): void
    {
        $membership = Membership::factory()->create([
            'membership_type_id' => $membershipType->getKey(),
            'user_id'            => $user->getKey(),
        ]);

        App::make(SessionSeeder::class)->run($membership);
    }
}
