<?php

namespace Tests\Feature\Filter;

use App\Models\Review;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class ReviewFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Review::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testReviewerFilter(): void
    {
        $this->createModel();
        $review = $this->createModel()->first();

        $filters = [
            'reviewer_id' => $review->reviewer_id,
        ];

        $expectedIds = [
            $review->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $this->createModel();
        $review = $this->createModel()->first();

        $filters = [
            'gym_id' => $review->model_id,
        ];

        $expectedIds = [
            $review->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
