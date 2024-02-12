<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'stripe_plan',
        'price',
        'description',
        'number_of_allowed_gyms',
        'number_of_allowed_employees',
        'duration_months',
    ];

    /**
     * @return string[]
     */
    public function defaultSorts(): array
    {
        return [
            'id',
        ];
    }
}
