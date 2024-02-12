<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Http\Resources\EquipmentCollection;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Services\EquipmentService;
use Illuminate\Http\JsonResponse;

class EquipmentController extends Controller
{
    /**
     * @param Equipment $model
     * @param EquipmentService $service
     * @param CustomResponse $response
     */
    public function __construct(Equipment $model, EquipmentService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, EquipmentResource::class, EquipmentCollection::class, 'equipment');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Equipment store
     *
     * @param StoreEquipmentRequest $request
     * @return JsonResponse
     */
    public function store(StoreEquipmentRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Equipment show
     *
     * @param Equipment $equipment
     * @return JsonResponse
     */
    public function show(Equipment $equipment)
    {
        return $this->showHelper($equipment);
    }

    /**
     * Equipment update
     *
     * @param UpdateEquipmentRequest $request
     * @param Equipment $equipment
     * @return JsonResponse
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        return $this->updateHelper($request, $equipment);
    }

    /**
     * Equipment delete
     *
     * @param Equipment $equipment
     * @return JsonResponse
     */
    public function destroy(Equipment $equipment)
    {
        return $this->destroyHelper($equipment);
    }
}
