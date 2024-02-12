<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreWorkingTimeRequest;
use App\Http\Requests\UpdateWorkingTimeRequest;
use App\Http\Resources\WorkingTimeCollection;
use App\Http\Resources\WorkingTimeResource;
use App\Models\WorkingTime;
use App\Services\WorkingTimeService;
use Illuminate\Http\JsonResponse;

class WorkingTimeController extends Controller
{
    /**
     * @param WorkingTime $model
     * @param WorkingTimeService $service
     * @param CustomResponse $response
     */
    public function __construct(WorkingTime $model, WorkingTimeService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, WorkingTimeResource::class,WorkingTimeCollection::class, 'working_time');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->indexHelper();
    }

    /**
     * @param StoreWorkingTimeRequest $request
     * @return JsonResponse
     */
    public function store(StoreWorkingTimeRequest $request): JsonResponse
    {
        return $this->storeHelper($request);
    }

    /**
     * @param WorkingTime $workingTime
     * @return JsonResponse
     */
    public function show(WorkingTime $workingTime): JsonResponse
    {
        return $this->showHelper($workingTime);
    }

    /**
     * @param UpdateWorkingTimeRequest $request
     * @param WorkingTime $workingTime
     * @return JsonResponse
     */
    public function update(UpdateWorkingTimeRequest $request, WorkingTime $workingTime): JsonResponse
    {
        return $this->updateHelper($request, $workingTime);
    }

    /**
     * @param WorkingTime $workingTime
     * @return JsonResponse
     */
    public function destroy(WorkingTime $workingTime): JsonResponse
    {
        return $this->destroyHelper($workingTime);
    }
}
