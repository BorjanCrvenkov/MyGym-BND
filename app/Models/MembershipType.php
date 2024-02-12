<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\QueryBuilder\AllowedFilter;

class MembershipType extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_weeks',
        'gym_id',
    ];

    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('gym_id'),
        ];
    }

    /**
     * @return string[]
     */
    public function defaultSorts(): array
    {
        return [
            'id',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'id');
    }
}
