<?php

namespace Tests\Feature\Filter;

use App\Models\File;
use App\Models\Gym;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class FileFilterTest extends BaseFilterTest
{
    use RefreshDatabase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(File::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }
    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gym = Gym::factory()->create();

        $expectedIds = $gym->images()->get()->modelKeys();

        File::factory(2)->gymCoverImage()->create([
            'model_id' => $gym->getKey(),
        ]);
        File::factory(2)->create([
            'model_id' => $gym->getKey(),
        ]);

        $filters = [
            'gym_id' => $gym->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
