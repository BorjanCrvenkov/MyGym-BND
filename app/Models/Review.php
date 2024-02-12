<?php

namespace App\Models;

use App\Enums\ReviewTypeEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\QueryBuilder\AllowedFilter;

class Review extends BaseModel
{
    protected $fillable = [
        'rating',
        'body',
        'reviewer_id',
        'model_id',
        'model_type',
    ];

    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('reviewer_id'),
            AllowedFilter::scope('gym_id'),
        ];
    }

    /**
     * @return string[]
     */
    public function allowedIncludes(): array
    {
        return [
            'reviewer',
            'gym',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class,'reviewer_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class,'model_id', 'id');
    }

    /**
     * @param $query
     * @param $gym_id
     * @return void
     */
    public function scopeGymId($query, $gym_id): void
    {
        $query->where('model_id', '=', $gym_id)
            ->where('model_type', '=', ReviewTypeEnum::GYM_REVIEW->value);
    }
}
