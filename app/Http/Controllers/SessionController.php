<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Http\Resources\SessionCollection;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    /**
     * @param Session $model
     * @param SessionService $service
     * @param CustomResponse $response
     */
    public function __construct(Session $model, SessionService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, SessionResource::class, SessionCollection::class, 'session');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Session store
     *
     * @param StoreSessionRequest $request
     * @return JsonResponse
     */
    public function store(StoreSessionRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Session show
     *
     * @param Session $session
     * @return JsonResponse
     */
    public function show(Session $session)
    {
        return $this->showHelper($session);
    }

    /**
     * Session update
     *
     * @param UpdateSessionRequest $request
     * @param Session $session
     * @return JsonResponse
     */
    public function update(UpdateSessionRequest $request, Session $session)
    {
        return $this->updateHelper($request, $session);
    }

    /**
     * Session delete
     *
     * @param Session $session
     * @return JsonResponse
     */
    public function destroy(Session $session)
    {
        return $this->destroyHelper($session);
    }
}
