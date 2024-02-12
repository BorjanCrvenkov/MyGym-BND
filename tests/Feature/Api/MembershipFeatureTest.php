<?php

namespace Tests\Feature\Api;

use App\Models\Membership;
use Closure;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Tests\Feature\BaseTests\BaseFeatureTest;

class MembershipFeatureTest extends BaseFeatureTest
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->model = App::make(Membership::class);
        $this->endpoint = $this->resolveModelEndpoint();
        $this->shouldAssert = true;
    }

    /**
     * @param array $expectedData
     * @param bool $singleResult
     * @return array
     */
    protected function getExpectedJsonStructure(array $expectedData, bool $singleResult = false): array
    {
        if($singleResult){
            unset($expectedData['charge_id']);
            $data = array_keys($expectedData);
        }else{
            unset($expectedData[0]['charge_id']);
            $data = [
                '*' => array_keys($expectedData[0])
            ];
        }

        return [
            'meta' => [
                'code',
                'message',
            ],
            'data' => $data,
        ];
    }


    /**
     * @param Closure|null $data
     * @return void
     */
    public function testStoreRoute(Closure $data = null): void
    {
        $createData = $this->createModel()->first()->toArray();

        $expectedStructure = $this->getExpectedJsonStructure($createData, true);

        $id = $createData['id'];

        unset($createData['id'], $createData['created_at'], $createData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $createData['payment_method_id'] = 'test_1';

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
        $updateData = $this->createModel()->first()->toArray();

        $id = $updateData['id'];

        unset($updateData['id'], $updateData['created_at'], $updateData['updated_at']);

        $this->model->query()->find($id)->forceDelete();

        $model = $this->createModel()->first();

        $this->be($this->user)
            ->put("{$this->endpoint}/{$model->getKey()}", $updateData)
            ->assertStatus(HTTPResponse::HTTP_METHOD_NOT_ALLOWED);
    }
}
