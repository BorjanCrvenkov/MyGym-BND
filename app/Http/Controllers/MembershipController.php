<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreMembershipRequest;

use App\Http\Resources\MembershipCollection;
use App\Http\Resources\MembershipResource;
use App\Models\Membership;
use App\Services\MembershipService;
use Illuminate\Http\JsonResponse;

class MembershipController extends Controller
{
    /**
     * @param Membership $model
     * @param MembershipService $service
     * @param CustomResponse $response
     */
    public function __construct(Membership $model, MembershipService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, MembershipResource::class, MembershipCollection::class, 'membership');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMembershipRequest $request
     * @return JsonResponse
     */
    public function store(StoreMembershipRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Membership $membership
     * @return JsonResponse
     */
    public function show(Membership $membership)
    {
        return $this->showHelper($membership);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Membership $membership
     * @return JsonResponse
     */
    public function destroy(Membership $membership)
    {
        return $this->destroyHelper($membership);
    }
}
