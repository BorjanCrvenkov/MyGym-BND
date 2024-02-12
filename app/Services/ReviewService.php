<?php

namespace App\Services;

use App\Enums\ReviewTypeEnum;
use App\Jobs\Gym\CalculateGymRatingJob;
use App\Models\BaseModel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ReviewService extends BaseService
{
    /**
     * @param Review $model
     */
    public function __construct(Review $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return BaseModel|User
     */
    public function store(array $data): BaseModel|User
    {
        $data = $this->resolveReviewerIdField($data);

        $review = $this->model->create($data);

        $this->dispatchCalculateGymRatingJob($review);

        return $this->show($review);
    }

    /**
     * @param BaseModel|User $review
     * @param array $data
     * @return Builder|array|Collection|Model|Builder[]
     */
    public function update(BaseModel|User $review, array $data): Builder|array|Collection|Model
    {
        $review->update($data);

        $this->dispatchCalculateGymRatingJob($review);

        return $this->show($review);
    }

    /**
     * @param BaseModel|User $review
     * @return bool|null
     */
    public function destroy(BaseModel|User $review): bool|null
    {
        $review->delete();

        $this->dispatchCalculateGymRatingJob($review);

        return true;
    }


    /**
     * @param array $data
     * @return array
     */
    public function resolveReviewerIdField(array $data): array
    {
        if (Arr::has($data, 'reviewer_id')) {
            return $data;
        }

        $data['reviewer_id'] = Auth::id();

        return $data;
    }

    /**
     * @param Review $review
     * @return void
     */
    public function dispatchCalculateGymRatingJob(Review $review): void
    {
        if($review->model_type != ReviewTypeEnum::GYM_REVIEW->value){
            return;
        }

        CalculateGymRatingJob::dispatch($review->model_id);
    }
}
