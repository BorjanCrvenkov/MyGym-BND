<?php

namespace Tests\Feature\Api;

use App\Enums\FileTypeEnum;
use App\Models\File;
use App\Models\Gym;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Tests\Feature\BaseTests\BaseFeatureTest;

class FileFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(File::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }


    /**
     * @param Closure|null $data
     * @return void
     * @dataProvider storeDataProvider
     */
    public function testStoreRoute(Closure $data = null): void
    {
        Storage::fake();

        $createData = $data();

        $this->be($this->user)
            ->post("{$this->endpoint}", $createData)
            ->assertStatus(HTTPResponse::HTTP_OK);
    }

    /**
     * @return array[]
     */
    public static function storeDataProvider(): array
    {
        return [
            'Gym image is being created' => [
                'data' => function () {
                    return [
                        'file'      => UploadedFile::fake()->image('test.png'),
                        'file_type' => FileTypeEnum::GYM_IMAGE->value,
                        'model_id'  => Gym::factory()->create()->getKey(),
                    ];
                }
            ],
        ];
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testUpdateRoute(Closure $data = null): void
    {
        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $this->be($this->user)
            ->put("{$this->endpoint}/{$model->getKey()}", $updateData)
            ->assertStatus(HTTPResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @return void
     */
    public function testShowRoute(): void
    {
        $model = $this->createModel()->first();

        $this->be($this->user)
            ->get("{$this->endpoint}/{$model->id}")
            ->assertStatus(HTTPResponse::HTTP_METHOD_NOT_ALLOWED);
    }
}
