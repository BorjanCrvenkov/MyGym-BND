<?php

namespace Tests\Feature\Filter;

use App\Models\Gym;
use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Support\Facades\App;
use Tests\Feature\BaseTests\BaseFilterTest;

class WorkingScheduleFilterTest extends BaseFilterTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(WorkingSchedule::class);
        $this->endpoint = $this->resolveModelEndpoint();
    }

    /**
     * @return void
     */
    public function testGymFilter(): void
    {
        $gymId = Gym::factory()->create()->getKey();

        $user1 = User::factory()->employee()->create([
            'gym_id'              => $gymId,
        ]);

        $user2 = User::factory()->employee()->create([
            'gym_id'              => $gymId,
        ]);

        $expectedIds= [
            $user1->working_schedule_id,
            $user2->working_schedule_id,
        ];

        WorkingSchedule::factory()->create();

        $filters = [
            'gym_id' => $gymId,
        ];

        $this->testBulkFilters($filters, $expectedIds);
    }
}
