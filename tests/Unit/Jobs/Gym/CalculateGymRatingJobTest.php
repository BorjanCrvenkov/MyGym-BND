<?php

namespace Tests\Unit\Jobs\Gym;

use App\Jobs\Gym\CalculateGymRatingJob;
use App\Models\Gym;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculateGymRatingJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testHandleMethod(): void
    {
        $gymId = Gym::factory()->create()->getKey();

        Review::factory()->create([
            'rating'   => 5,
            'model_id' => $gymId,
        ]);

        Review::factory()->create([
            'rating'   => 2,
            'model_id' => $gymId,
        ]);

        (new CalculateGymRatingJob($gymId))->handle();

        $gym = Gym::query()->find($gymId);

        $this->assertSame(3.5, (float)$gym->rating);
    }
}
