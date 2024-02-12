<?php

namespace Tests\Unit\BaseTests;

use App\Traits\TestTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BaseServiceTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    /**
     * @param int $count
     * @return Model|Collection
     */
    public function createModel(int $count = 1): Model|Collection
    {
        $modelIds = $this->model::factory($count)->create()->modelKeys();

        return $this->model->query()->whereIn('id', $modelIds)->get();
    }

    /**
     * @return void
     */
    public function testIndex(): void
    {
        if(!$this->shouldAssert){
            $this->expectNotToPerformAssertions();
            return;
        }

        $models = $this->createModel(2);

        $expectedIds = $models->modelKeys();

        $actualIds = $this->service->index()->modelKeys();

        $this->assertEqualsCanonicalizing($expectedIds, $actualIds);
    }

    /**
     * @return void
     */
    public function testShow(): void
    {
        if(!$this->shouldAssert){
            $this->expectNotToPerformAssertions();
            return;
        }

        $expectedModel = $this->createModel()->first();

        $actualModel = $this->service->show($expectedModel)->toArray();

        $this->assertSame($expectedModel->toArray(), $actualModel);
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStore(Closure $data = null): void
    {
        if(!$this->shouldAssert){
            $this->expectNotToPerformAssertions();
            return;
        }

        $createData = $this->createModel()->first()->toArray();

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at']);

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
        if(!$this->shouldAssert){
            $this->expectNotToPerformAssertions();
            return;
        }

        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $updatedModel = $this->service->update($model, $updateData);
        $updateData['id'] = $updatedModel->getKey();

        $this->assertDatabaseHas($this->model->getTable(), $updateData);
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        if(!$this->shouldAssert){
            $this->expectNotToPerformAssertions();
            return;
        }

        Carbon::setTestNow(now());

        $model = $this->createModel()->first();

        $this->service->destroy($model);

        $this->assertDatabaseHas($model->getTable(), [
            'id' => $model->id,
            'deleted_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
