<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreGymRequest;
use App\Http\Requests\UpdateGymRequest;
use App\Http\Resources\GymCollection;
use App\Http\Resources\GymResource;
use App\Models\Gym;
use App\Services\GymService;
use Illuminate\Http\JsonResponse;

class GymController extends Controller
{
    /**
     * @param Gym $model
     * @param GymService $service
     * @param CustomResponse $response
     */
    public function __construct(Gym $model, GymService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, GymResource::class, GymCollection::class, 'gym');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Gym store
     *
     * @param StoreGymRequest $request
     * @return JsonResponse
     */
    public function store(StoreGymRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Gym show
     *
     * @param Gym $gym
     * @return JsonResponse
     */
    public function show(Gym $gym)
    {
        return $this->showHelper($gym);
    }

    /**
     * Gym update
     *
     * @param UpdateGymRequest $request
     * @param Gym $gym
     * @return JsonResponse
     */
    public function update(UpdateGymRequest $request, Gym $gym)
    {
        return $this->updateHelper($request, $gym);
    }

    /**
     * Gym delete
     *
     * @param Gym $gym
     * @return JsonResponse
     */
    public function destroy(Gym $gym)
    {
        return $this->destroyHelper($gym);
    }
}
