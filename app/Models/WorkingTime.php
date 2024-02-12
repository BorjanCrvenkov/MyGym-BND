<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\QueryBuilder\AllowedFilter;

class WorkingTime extends BaseModel
{
    protected $fillable = [
        'start_time',
        'end_time',
        'working_schedule_id',
    ];

    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::scope('between'),
        ];
    }

    /**
     * @return BelongsTo
     */
    public function working_schedule(): BelongsTo
    {
        return $this->belongsTo(WorkingSchedule::class);
    }

    /**
     * @param $query
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function scopeBetween($query, $startDate, $endDate): mixed
    {
        return $query
            ->whereBetween('start_time', [$startDate, $endDate])
            ->whereBetween('end_time', [$startDate, $endDate]);
    }
}
