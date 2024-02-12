<?php

namespace Tests\Feature\Filter;

use App\Models\Equipment;
use App\Models\Gym;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class EquipmentFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Equipment::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gym = Gym::factory()->create();

        $expectedIds = Equipment::factory(2)->create([
            'gym_id' => $gym->getKey(),
        ])->modelKeys();

        Equipment::factory()->create();

        $filters = [
            'gym_id' => $gym->getKey(),
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
