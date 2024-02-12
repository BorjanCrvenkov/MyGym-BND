<?php

namespace App\Services;

use App\Models\BaseModel;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseService
{
    /**
     * @param BaseModel|User $model
     */
    public function __construct(public BaseModel|User $model)
    {
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        $model = $this->model;

        return QueryBuilder::for($model::class)
            ->allowedIncludes($model->allowedIncludes())
            ->allowedFilters($model->allowedFilters())
            ->defaultSorts($model->defaultSorts())
            ->allowedSorts($model->allowedSorts())
            ->get();
    }

    /**
     * @param BaseModel|User $model
     * @return BaseModel|User
     */
    public function show(BaseModel|User $model): BaseModel|User
    {
        return QueryBuilder::for($model::class)
            ->allowedIncludes($model->allowedIncludes())
            ->allowedFilters($model->allowedFilters())
            ->findOrFail($model->getKey());
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $model = $this->model->create($data);

        return $this->show($model);
    }

    /**
     * @param BaseModel|User $model
     * @param array $data
     * @return Builder|Model|Collection|Builder[]
     */
    public function update(BaseModel|User $model, array $data): Builder|array|Collection|Model
    {
        $model->update($data);

        return $this->show($model);
    }

    /**
     * @param BaseModel|User $model
     * @return bool|null
     */
    public function destroy(BaseModel|User $model): bool|null
    {
        return $model->delete();
    }
}
