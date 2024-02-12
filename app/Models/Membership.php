<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;

class Membership extends BaseModel
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'user_id',
        'original_membership_type_model',
        'membership_type_id',
        'gym_id',
        'active_session_id',
        'charge_id',
        'refunded',
    ];

    protected $casts = [
        'original_membership_type_model' => 'array',
    ];

    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('membership_type_id'),
            AllowedFilter::scope('started_sessions'),
            AllowedFilter::exact('gym_id'),
        ];
    }

    /**
     * @return string[]
     */
    public function allowedIncludes(): array
    {
        return [
            'user',
            'active_session',
        ];
    }

    /**
     * @return string[]
     */
    public function allowedSorts(): array
    {
        return [
            'end_date'
        ];
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function membership_type(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class, 'membership_type_id', 'id');
    }

    /**
     * @param $query
     * @param bool $startedSessions
     * @return void
     */
    public function scopeStartedSessions($query, bool $startedSessions): void
    {
        if ($startedSessions) {
            $query->whereNotNull('active_session_id');
        } else {
            $query->whereNull('active_session_id');
        }
    }

    /**
     * @return HasOne
     */
    public function active_session(): HasOne
    {
        return $this->hasOne(Session::class, 'id', 'active_session_id');
    }

    /**
     * @return BelongsTo
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'membership_id', 'id')
            ->orderBy('created_at', 'desc');
    }
}
