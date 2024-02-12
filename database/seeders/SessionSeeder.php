<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\Session;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Membership $membership): void
    {
        Session::factory()->create([
            'membership_id' => $membership->getKey()
        ]);
    }
}
