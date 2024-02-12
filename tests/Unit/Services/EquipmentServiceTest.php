<?php

namespace Tests\Unit\Services;

use App\Models\Equipment;
use App\Services\EquipmentService;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Tests\Unit\BaseTests\BaseServiceTest;

class EquipmentServiceTest extends BaseServiceTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->service = App::make(EquipmentService::class);
        $this->model = App::make(Equipment::class);
        $this->shouldAssert = true;
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStore(Closure $data = null): void
    {
        $createData = $this->createModel()->first()->toArray();

        $image = UploadedFile::fake()->create('test_file.jpg', 100);
        $createData['image'] = $image;

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $createdModel = $this->service->store($createData);
        $createData['id'] = $createdModel->getKey();
        unset($createData['image']);

        $this->assertDatabaseHas($this->model->getTable(), $createData);
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at'], $updateData['image']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $updatedModel = $this->service->update($model, $updateData);
        $updateData['id'] = $updatedModel->getKey();

        $this->assertDatabaseHas($this->model->getTable(), $updateData);
    }
}
