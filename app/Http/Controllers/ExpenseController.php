<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    /**
     * @param Expense $model
     * @param ExpenseService $service
     * @param CustomResponse $response
     */
    public function __construct(Expense $model, ExpenseService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, ExpenseResource::class, ExpenseCollection::class, 'expense');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Expense store
     *
     * @param StoreExpenseRequest $request
     * @return JsonResponse
     */
    public function store(StoreExpenseRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Expense show
     *
     * @param Expense $expense
     * @return JsonResponse
     */
    public function show(Expense $expense)
    {
        return $this->showHelper($expense);
    }

    /**
     * Expense update
     *
     * @param UpdateExpenseRequest $request
     * @param Expense $expense
     * @return JsonResponse
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        return $this->updateHelper($request, $expense);
    }

    /**
     * Expense delete
     *
     * @param Expense $expense
     * @return JsonResponse
     */
    public function destroy(Expense $expense)
    {
        return $this->destroyHelper($expense);
    }
}
