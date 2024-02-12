<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreExpenseTypeRequest;
use App\Http\Requests\UpdateExpenseTypeRequest;
use App\Http\Resources\ExpenseTypeCollection;
use App\Http\Resources\ExpenseTypeResource;
use App\Models\ExpenseType;
use App\Services\ExpenseTypeService;
use Illuminate\Http\JsonResponse;

class ExpenseTypeController extends Controller
{
    /**
     * @param ExpenseType $model
     * @param ExpenseTypeService $service
     * @param CustomResponse $response
     */
    public function __construct(ExpenseType $model, ExpenseTypeService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, ExpenseTypeResource::class, ExpenseTypeCollection::class, 'expense_type');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * ExpenseType store
     *
     * @param StoreExpenseTypeRequest $request
     * @return JsonResponse
     */
    public function store(StoreExpenseTypeRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * ExpenseType show
     *
     * @param ExpenseType $expenseType
     * @return JsonResponse
     */
    public function show(ExpenseType $expenseType)
    {
        return $this->showHelper($expenseType);
    }

    /**
     * ExpenseType update
     *
     * @param UpdateExpenseTypeRequest $request
     * @param ExpenseType $expenseType
     * @return JsonResponse
     */
    public function update(UpdateExpenseTypeRequest $request, ExpenseType $expenseType)
    {
        return $this->updateHelper($request, $expenseType);
    }

    /**
     * ExpenseType delete
     *
     * @param ExpenseType $expenseType
     * @return JsonResponse
     */
    public function destroy(ExpenseType $expenseType)
    {
        return $this->destroyHelper($expenseType);
    }
}
