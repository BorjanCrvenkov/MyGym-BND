<?php

namespace App\Traits;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait TestTrait
{
    protected string $endpoint;

    protected Model $model;

    protected bool $shouldAssert = false;

    protected User $user;

    protected BaseService $service;

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
     * @return string
     */
    public function resolveModelEndpoint(): string
    {
        $modelEndpoint = '/api/' . (new $this->model())->getTable();

        return Str::plural($modelEndpoint);
    }

    /**
     * @param array $expectedData
     * @param bool $singleResult
     * @return array
     */
    protected function getExpectedJsonStructure(array $expectedData, bool $singleResult = false): array
    {
        $data = $singleResult ? array_keys($expectedData) :
            [
                '*' => array_keys($expectedData[0])
            ];

        return [
            'meta' => [
                'code',
                'message',
            ],
            'data' => $data,
        ];
    }
}
