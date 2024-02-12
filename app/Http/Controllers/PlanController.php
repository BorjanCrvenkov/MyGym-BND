<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\SubscribeToPlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\PlanCollection;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    /**
     * @param Plan $model
     * @param PlanService $service
     * @param CustomResponse $response
     */
    public function __construct(Plan $model, PlanService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, PlanResource::class, PlanCollection::class, 'plan');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Plan store
     *
     * @param StorePlanRequest $request
     * @return JsonResponse
     */
    public function store(StorePlanRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Plan show
     *
     * @param Plan $plan
     * @return JsonResponse
     */
    public function show(Plan $plan)
    {
        return $this->showHelper($plan);
    }

    /**
     * Plan update
     *
     * @param UpdatePlanRequest $request
     * @param Plan $plan
     * @return JsonResponse
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        return $this->updateHelper($request, $plan);
    }

    /**
     * Plan delete
     *
     * @param Plan $plan
     * @return JsonResponse
     */
    public function destroy(Plan $plan)
    {
        return $this->destroyHelper($plan);
    }

    /**
     * @param SubscribeToPlanRequest $request
     * @return JsonResponse
     */
    public function subscribe(SubscribeToPlanRequest $request)
    {
        $validatedData = $request->validated();

        $this->service->subscribe($validatedData);

        return $this->response->success();
    }
}
