<?php

namespace Tests\Unit\Services;

use App\Models\Report;
use App\Models\User;
use App\Services\ReportService;
use Closure;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class ReportServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(ReportService::class);
        $this->model = App::make(Report::class);
        $this->shouldAssert = true;
        $this->user = User::factory()->administrator()->create();
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStore(Closure $data = null): void
    {
        $this->be($this->user);
        $createData = $this->createModel()->first()->toArray();

        $id = $createData['id'];
        $createData['reporter_id'] = $this->user->getKey();

        unset($createData['id'], $createData['created_at'], $createData['updated_at'], $createData['reporter']);

        $this->model->query()->find($id)->forceDelete();

        $createdModel = $this->service->store($createData);
        $createData['id'] = $createdModel->getKey();

        $this->assertDatabaseHas($this->model->getTable(), $createData);
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at'], $updateData['reporter']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $updatedModel = $this->service->update($model, $updateData);
        $updateData['id'] = $updatedModel->getKey();

        $this->assertDatabaseHas($this->model->getTable(), $updateData);
    }
}
