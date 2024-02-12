<?php

namespace App\Services;

use App\Models\BaseModel;
use App\Models\ExpenseType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ExpenseTypeService extends BaseService
{
    /**
     * @param ExpenseType $model
     */
    public function __construct(ExpenseType $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $data = $this->resolveNextRecurringDateField($data);

        $model = $this->model->create($data);

        return $this->show($model);
    }

    /**
     * @param User|BaseModel $model
     * @param array $data
     * @return Builder|array|Collection|Model|Builder[]
     */
    public function update(User|BaseModel $model, array $data): Builder|array|Collection|Model
    {
        $data = $this->resolveNextRecurringDateField($data);

        $model->update($data);

        return $this->show($model);
    }

    /**
     * @param array $data
     * @return array
     */
    public function resolveNextRecurringDateField(array $data): array
    {
        if(!$data['recurring']){
            return $data;
        }

        $data['next_recurring_date'] = now()->addDays($data['recurring_every_number_of_days'])->toDateString();

        return $data;
    }
}
