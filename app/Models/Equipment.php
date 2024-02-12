<?php

namespace App\Models;

use App\Enums\FileTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\QueryBuilder\AllowedFilter;

class Equipment extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'last_service_date',
        'next_service_date',
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
    public function allowedIncludes(): array
    {
        return [
            'image',
        ];
    }

    public function defaultSorts(): array
    {
        return [
            'id',
        ];
    }

    /**
     * @return HasOne
     */
    public function image(): HasOne
    {
        return $this->hasOne(File::class, 'model_id', 'id')
            ->where('files.file_type', '=', FileTypeEnum::EQUIPMENT_IMAGE->value);
    }

    /**
     * @return BelongsTo
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'id');
    }
}
