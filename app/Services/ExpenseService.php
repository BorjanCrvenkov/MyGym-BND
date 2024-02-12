<?php

namespace App\Services;

use App\Enums\ExpenseStatusEnum;
use App\Models\BaseModel;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ExpenseService extends BaseService
{
    /**
     * @param Expense $model
     */
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $data = $this->resolveFields($data);

        $expense = $this->model->create($data);

        return $this->show($expense);
    }

    /**
     * @param BaseModel|User $expense
     * @param array $data
     * @return Builder|array|Collection|Model|Builder[]
     */
    public function update(BaseModel|User $expense, array $data): Builder|array|Collection|Model
    {
        $data = $this->resolveFields($data, $expense);

        $expense->update($data);

        return $this->show($expense);
    }


    /**
     * @param array $data
     * @param Expense|null $expense
     * @return array
     */
    public function resolveFields(array $data, ?Expense $expense = null): array
    {
        $expenseType = $expense ? $expense->expense_type : ExpenseType::query()->find($data['expense_type_id']);

        $data = $this->resolveNameField($data, $expenseType);
        $data = $this->resolvePaidAtField($data);

        return $data;
    }

    /**
     * @param array $data
     * @param ExpenseType|null $expenseType
     * @return array
     */
    public function resolveNameField(array $data, ?ExpenseType $expenseType = null): array
    {
        if(!$expenseType){
            return $data;
        }
        $data['name'] = $expenseType->name;

        return $data;
    }

    /**
     * @param array $data
     * @param Expense|null $expense
     * @return array
     */
    public function resolvePaidAtField(array $data, ?Expense $expense = null): array
    {
        if(!Arr::has($data, 'status') || $data['status'] != ExpenseStatusEnum::PAID->value){
            return $data;
        }

        if(!$expense){
            $data['paid_at'] = now()->toDateTimeString();
        }

        if($expense && $expense->status == ExpenseStatusEnum::NOT_PAID->value){
            $data['paid_at'] = now()->toDateTimeString();
        }

        return $data;
    }
}
