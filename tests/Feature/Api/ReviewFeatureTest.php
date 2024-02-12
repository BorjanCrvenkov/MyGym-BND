<?php

namespace Tests\Feature\Api;

use App\Models\Review;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFeatureTest;
use Tests\TestCase;

class ReviewFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Review::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }
}
