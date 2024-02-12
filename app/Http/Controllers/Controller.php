<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Models\BaseModel;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param BaseModel|User $model
     * @param BaseService $service
     * @param CustomResponse $response
     * @param string $resource
     * @param string $collection
     * @param string $authParam
     */
    public function __construct(
        public BaseModel|User $model,
        public BaseService    $service,
        public CustomResponse $response,
        public string         $resource,
        public string         $collection,
        public string         $authParam,
    )
    {
        $this->authorizeResource($this->model::class, $this->authParam);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexHelper(): JsonResponse
    {
        $modelsData = $this->service->index();

        $modelsCollection = new $this->collection($modelsData);

        return $this->response->success(data: $modelsCollection);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FormRequest $request
     * @return JsonResponse
     */
    public function storeHelper(FormRequest $request): JsonResponse
    {
        $createData = $request->validated();

        $model = $this->service->store($createData);

        $modelData = new $this->resource($model);

        return $this->response->created(data: $modelData);
    }

    /**
     * Display the specified resource.
     *
     * @param BaseModel|User $model
     * @return JsonResponse
     */
    public function showHelper(BaseModel|User $model): JsonResponse
    {
        $model = $this->service->show($model);

        $modelData = new $this->resource($model);

        return $this->response->success(data: $modelData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FormRequest $request
     * @param BaseModel|User $model
     * @return JsonResponse
     */
    public function updateHelper(FormRequest $request, BaseModel|User $model): JsonResponse
    {
        $updateData = $request->validated();

        $model = $this->service->update($model, $updateData);

        $modelData = new $this->resource($model);

        return $this->response->updated(data: $modelData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BaseModel|User $model
     * @return JsonResponse
     */
    public function destroyHelper(BaseModel|User $model): JsonResponse
    {
        $this->service->destroy($model);

        return $this->response->noContent();
    }
}
