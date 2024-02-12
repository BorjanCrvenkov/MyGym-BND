<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FileTypeEnum;
use App\Enums\UserRolesEnum;
use App\Notifications\Auth\EmailConfirmationNotification;
use App\Traits\QueryBuilderTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use Laravel\Sanctum\HasApiTokens;
use Spatie\QueryBuilder\AllowedFilter;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, QueryBuilderTrait, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'date_of_birth',
        'date_of_employment',
        'bio',
        'username',
        'confirmation_token',
        'role_id',
        'gym_id',
        'plan_id',
        'working_schedule_id',
    ];

    protected $with = [
        'image',
        'identification_file',
        'role',
        'plan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * @return string[]
     */
    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('email'),
            AllowedFilter::exact('username'),
            AllowedFilter::exact('gym_id'),
        ];
    }

    /**
     * @return string[]
     */
    public function allowedIncludes(): array
    {
        return [
            'working_schedule',
        ];
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new EmailConfirmationNotification);
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role->name === UserRolesEnum::ADMINISTRATOR->value;
    }

    /**
     * @return bool
     */
    public function getIsBusinessAttribute(): bool
    {
        return $this->role->name === UserRolesEnum::BUSINESS->value;
    }

    /**
     * @return bool
     */
    public function getIsEmployeeAttribute(): bool
    {
        return $this->role->name === UserRolesEnum::EMPLOYEE->value;
    }

    /**
     * @return bool
     */
    public function getIsMemberAttribute(): bool
    {
        return $this->role->name === UserRolesEnum::MEMBER->value;
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(File::class, 'model_id', 'id')
            ->where('files.file_type', '=', FileTypeEnum::USER_IMAGE->value);
    }

    /**
     * @return HasOne
     */
    public function identification_file(): HasOne
    {
        return $this->hasOne(File::class, 'model_id', 'id')
            ->where('files.file_type', '=', FileTypeEnum::USER_IDENTIFICATION_DOCUMENT->value);
    }

    /**
     * @return HasMany
     */
    public function gyms(): HasMany
    {
        return $this->hasMany(Gym::class, 'owner_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function gym_employed_at(): BelongsTo
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function working_schedule(): HasOne
    {
        return $this->hasOne(WorkingSchedule::class);
    }
}
