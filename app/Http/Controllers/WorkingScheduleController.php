<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Resources\WorkingScheduleCollection;
use App\Http\Resources\WorkingScheduleResource;
use App\Models\WorkingSchedule;
use App\Services\WorkingScheduleService;
use Illuminate\Http\JsonResponse;

class WorkingScheduleController extends Controller
{
    /**
     * @param WorkingSchedule $model
     * @param WorkingScheduleService $service
     * @param CustomResponse $response
     */
    public function __construct(WorkingSchedule $model, WorkingScheduleService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, WorkingScheduleResource::class, WorkingScheduleCollection::class, 'working_schedule');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkingSchedule $workingSchedule)
    {
        return $this->showHelper($workingSchedule);
    }
}
