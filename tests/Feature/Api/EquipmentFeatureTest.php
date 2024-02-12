<?php

namespace Tests\Feature\Api;

use App\Models\Equipment;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Tests\Feature\BaseTests\BaseFeatureTest;

class EquipmentFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Equipment::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStoreRoute(Closure $data = null): void
    {
        Storage::fake();

        $createData = $this->createModel()->first()->toArray();

        $expectedStructure = $this->getExpectedJsonStructure($createData, true);

        $image = UploadedFile::fake()->create('test_file.jpg', 100);
        $createData['image'] = $image;

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $response = $this->be($this->user)
            ->post("{$this->endpoint}", $createData)
            ->assertStatus(HTTPResponse::HTTP_CREATED)
            ->assertJsonStructure($expectedStructure);

        $id = $response->json()['data']['id'];

        $this->assertDatabaseHas($this->model->getTable(), [
            'id' => $id,
        ]);
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testUpdateRoute(Closure $data = null): void
    {
        $updateData = $this->createModel()->first()->toArray();

        $expectedStructure = $this->getExpectedJsonStructure($updateData, true);

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at'], $updateData['image']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $this->be($this->user)
            ->post("{$this->endpoint}/{$model->getKey()}", $updateData)
            ->assertStatus(HTTPResponse::HTTP_OK)
            ->assertJsonStructure($expectedStructure);
    }
}
