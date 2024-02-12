<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse\CustomResponse;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * @param Review $model
     * @param ReviewService $service
     * @param CustomResponse $response
     */
    public function __construct(Review $model, ReviewService $service, CustomResponse $response)
    {
        parent::__construct($model, $service, $response, ReviewResource::class, ReviewCollection::class, 'review');
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->indexHelper();
    }

    /**
     * Review store
     *
     * @param StoreReviewRequest $request
     * @return JsonResponse
     */
    public function store(StoreReviewRequest $request)
    {
        return $this->storeHelper($request);
    }

    /**
     * Review show
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function show(Review $review)
    {
        return $this->showHelper($review);
    }

    /**
     * Review update
     *
     * @param UpdateReviewRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        return $this->updateHelper($request, $review);
    }

    /**
     * Review delete
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function destroy(Review $review)
    {
        return $this->destroyHelper($review);
    }
}
