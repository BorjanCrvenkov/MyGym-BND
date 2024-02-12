<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreMembershipTypeRequest;
use App\Http\Requests\UpdateMembershipTypeRequest;
use App\Http\Resources\MembershipTypeCollection;
use App\Http\Resources\MembershipTypeResource;
use App\Models\MembershipType;
use App\Services\MembershipTypeService;
use Illuminate\Http\JsonResponse;

class MembershipTypeController extends Controller
{
    /**
     * @param MembershipType $model
     * @param MembershipTypeService $service
     * @param CustomResponse $response
     */
    public function __construct(MembershipType $model, MembershipTypeService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, MembershipTypeResource::class, MembershipTypeCollection::class, 'membership_type');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * MembershipType store
     *
     * @param StoreMembershipTypeRequest $request
     * @return JsonResponse
     */
    public function store(StoreMembershipTypeRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * MembershipType show
     *
     * @param MembershipType $membershipType
     * @return JsonResponse
     */
    public function show(MembershipType $membershipType)
    {
        return $this->showHelper($membershipType);
    }

    /**
     * MembershipType update
     *
     * @param UpdateMembershipTypeRequest $request
     * @param MembershipType $membershipType
     * @return JsonResponse
     */
    public function update(UpdateMembershipTypeRequest $request, MembershipType $membershipType)
    {
        return $this->updateHelper($request, $membershipType);
    }

    /**
     * MembershipType delete
     *
     * @param MembershipType $membershipType
     * @return JsonResponse
     */
    public function destroy(MembershipType $membershipType)
    {
        return $this->destroyHelper($membershipType);
    }
}
