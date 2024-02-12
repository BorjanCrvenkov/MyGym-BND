<?php

namespace App\Models;

use App\Enums\ReportTypeEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\QueryBuilder\AllowedFilter;

class Report extends BaseModel
{
    protected $fillable = [
        'model_id',
        'model_type',
        'reason',
        'reporter_id',
        'heading',
    ];

    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::scope('gym_id'),
        ];
    }

    /**
     * @return string[]
     */
    public function allowedIncludes(): array
    {
        return [
            'reporter',
        ];
    }

    /**
     * @param $query
     * @param $gym_id
     * @return void
     */
    public function scopeGymId($query, $gym_id): void
    {
        $query->where('model_id', '=', $gym_id)
            ->where('model_type', '=', ReportTypeEnum::GYM_PROBLEM->value);
    }

    /**
     * @return BelongsTo
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id', 'id');
    }
}
