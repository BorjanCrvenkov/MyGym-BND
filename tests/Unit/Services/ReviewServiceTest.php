<?php

namespace Tests\Unit\Services;

use App\Jobs\Gym\CalculateGymRatingJob;
use App\Models\Review;
use App\Services\ReviewService;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Queue;
use Tests\Unit\BaseTests\BaseServiceTest;

class ReviewServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(ReviewService::class);
        $this->model = App::make(Review::class);
        $this->shouldAssert = true;
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStore(Closure $data = null): void
    {
        Queue::fake();

        parent::testStore($data);

        Queue::assertPushed(CalculateGymRatingJob::class);
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        Queue::fake();

        parent::testUpdate();

        Queue::assertPushed(CalculateGymRatingJob::class);
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        Queue::fake();

        parent::testDelete();

        Queue::assertPushed(CalculateGymRatingJob::class);
    }


}
