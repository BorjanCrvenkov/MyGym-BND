<?php

namespace App\Models;

use App\Enums\FileTypeEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;

class Gym extends BaseModel
{
    protected $fillable = [
        'name',
        'address',
        'date_opened',
        'working_times',
        'phone_number',
        'owner_id',
        'rating',
        'email',
        'shutdown',
        'shutdown_date',
    ];

    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::scope('user_id')->default(Auth::id()),
            AllowedFilter::exact('shutdown'),
        ];
    }

    /**
     * @return string[]
     */
    public function allowedIncludes(): array
    {
        return [
            'cover_image',
            'social_media_links',
        ];
    }

    public function defaultSorts(): array
    {
        return [
            'id'
        ];
    }


    /**
     * @return HasOne
     */
    public function cover_image(): HasOne
    {
        return $this->hasOne(File::class, 'model_id', 'id')
            ->where('files.file_type', '=', FileTypeEnum::GYM_COVER_IMAGE->value);
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(File::class, 'model_id', 'id')
            ->where('files.file_type', '=', FileTypeEnum::GYM_IMAGE->value);
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function membership_types(): HasMany
    {
        return $this->hasMany(MembershipType::class, 'gym_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'gym_id', 'id');
    }

    /**
     * @param $query
     * @param $user_id
     * @return void
     */
    public function scopeUserId($query, $user_id): void
    {
        $user = Auth::user();
        if ($user->is_admin || $user->is_member) {
            return;
        }

        if ($user->is_business) {
            $query->where('gyms.owner_id', '=', $user->getKey());
        }
    }

    /**
     * @return HasMany
     */
    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'gym_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function social_media_links(): HasOne
    {
        return $this->hasOne(SocialMediaLinks::class);
    }

    /**
     * @return HasMany
     */
    public function expense_types(): HasMany
    {
        return $this->hasMany(ExpenseType::class, 'gym_id', 'id');
    }
}
