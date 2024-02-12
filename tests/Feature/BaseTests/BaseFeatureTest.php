<?php

namespace Tests\Feature\BaseTests;

use App\Models\Role;
use App\Models\User;
use App\Traits\TestTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Tests\TestCase;

class BaseFeatureTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = App::make(Role::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->user = User::factory()->administrator()->create();
    }

    /**
     * @return void
     */
    public function testIndexRoute(): void
    {
        if (!$this->shouldAssert) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $expectedData = $this->createModel(2)->toArray();

        $expectedStructure = $this->getExpectedJsonStructure($expectedData);

        $this->be($this->user)
            ->get("{$this->endpoint}")
            ->assertStatus(HTTPResponse::HTTP_OK)
            ->assertJsonStructure($expectedStructure);
    }

    /**
     * @return void
     */
    public function testShowRoute(): void
    {
        if (!$this->shouldAssert) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $model = $this->createModel()->first();

        $expectedData = $model->toArray();
        $expectedStructure = $this->getExpectedJsonStructure($expectedData, true);

        $this->be($this->user)
            ->get("{$this->endpoint}/{$model->id}")
            ->assertStatus(HTTPResponse::HTTP_OK)
            ->assertJsonStructure($expectedStructure);
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStoreRoute(Closure $data = null): void
    {
        if (!$this->shouldAssert) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $createData = $this->createModel()->first()->toArray();

        $expectedStructure = $this->getExpectedJsonStructure($createData, true);

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $this->be($this->user)
            ->post("{$this->endpoint}", $createData)
            ->assertStatus(HTTPResponse::HTTP_CREATED)
            ->assertJsonStructure($expectedStructure);
    }

    /**
     * @param Closure|null $data
     * @return void
     */
    public function testUpdateRoute(Closure $data = null): void
    {
        if (!$this->shouldAssert) {
            $this->expectNotToPerformAssertions();
            return;
        }

        $updateData = $this->createModel()->first()->toArray();

        $expectedStructure = $this->getExpectedJsonStructure($updateData, true);

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $this->be($this->user)
            ->put("{$this->endpoint}/{$model->getKey()}", $updateData)
            ->assertStatus(HTTPResponse::HTTP_OK)
            ->assertJsonStructure($expectedStructure);
    }

    /**
     * @return void
     */
    public function testDestroyRoute(): void
    {
        if (!$this->shouldAssert) {
            $this->expectNotToPerformAssertions();
            return;
        }

        Carbon::setTestNow(now());

        $model = $this->createModel()->first();

        $id = $model->getKey();

        $this->be($this->user)
            ->delete("{$this->endpoint}/{$id}")
            ->assertStatus(HTTPResponse::HTTP_NO_CONTENT);

        $this->assertDatabaseHas($model->getTable(), [
            'id'         => $id,
            'deleted_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
