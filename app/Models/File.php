<?php

namespace App\Models;

use App\Enums\FileTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;

class File extends BaseModel
{
    protected $fillable = [
        'name',
        'link',
        'mime',
        'model_id',
        'file_type',
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
     * @param $query
     * @param $gym_id
     * @return void
     */
    public function scopeGymId($query, $gym_id): void
    {
        $query->where('model_id', '=', $gym_id)
            ->where('file_type', '=', FileTypeEnum::GYM_IMAGE->value);
    }
}
