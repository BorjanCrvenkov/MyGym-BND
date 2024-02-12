<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\QueryBuilder\AllowedFilter;

class Session extends BaseModel
{
    protected $fillable = [
        'time_end',
        'membership_id',
        'description',
    ];

    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('membership_id'),
        ];
    }

    /**
     * @return string[]
     */
    public function allowedSorts(): array
    {
        return [
            'created_at',
            '-time_end'
        ];
    }


    /**
     * @return HasOne
     */
    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class, 'id', 'membership_id');
    }
}
