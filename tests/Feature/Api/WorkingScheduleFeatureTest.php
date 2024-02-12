<?php

namespace Tests\Feature\Api;

use App\Models\WorkingSchedule;
use Closure;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Tests\Feature\BaseTests\BaseFeatureTest;

class WorkingScheduleFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(WorkingSchedule::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStoreRoute(Closure $data = null): void
    {
        $model = WorkingSchedule::factory()->create();

        $this->be($this->user)
            ->put("{$this->endpoint}/{$model->getKey()}", [])
            ->assertStatus(HTTPResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testUpdateRoute(Closure $data = null): void
    {
        $model = WorkingSchedule::factory()->create();

        $this->be($this->user)
            ->put("{$this->endpoint}/{$model->getKey()}", [])
            ->assertStatus(HTTPResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @return void
     */
    public function testDestroyRoute(): void
    {
        $model = WorkingSchedule::factory()->create();

        $this->be($this->user)
            ->put("{$this->endpoint}/{$model->getKey()}", [])
            ->assertStatus(HTTPResponse::HTTP_METHOD_NOT_ALLOWED);
    }
}
