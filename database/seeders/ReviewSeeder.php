<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $userId, int $gymId): void
    {
        $randomStars = random_int(1, 5);

        $methodPrefix = match ($randomStars) {
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            default => 'five',
        };

        $methodPrefix .= 'Star';

        Review::factory()->{$methodPrefix}()->create([
            'reviewer_id' => $userId,
            'model_id'    => $gymId,
        ]);
    }
}
