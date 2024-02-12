<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * @param Role $model
     * @param RoleService $service
     * @param CustomResponse $response
     */
    public function __construct(Role $model, RoleService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, RoleResource::class, RoleCollection::class, 'role');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Role store
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Role show
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role)
    {
        return $this->showHelper($role);
    }

    /**
     * Role update
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        return $this->updateHelper($request, $role);
    }

    /**
     * Role delete
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        return $this->destroyHelper($role);
    }
}
