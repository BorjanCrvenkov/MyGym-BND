<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $this->call([
                UserSeeder::class,
                GymSeeder::class
            ]);
        }catch (Exception $exception){
            dd($exception->getMessage());
        }


        App::make(UserSeeder::class)->seedMembers();
    }
}
