<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;

class ExpenseType extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'cost',
        'recurring',
        'recurring_every_number_of_days',
        'next_recurring_date',
        'gym_id',
    ];

    /**
     * @return string[]
     */
    public function allowedFilters(): array
    {
        return [
           AllowedFilter::exact('gym_id'),
        ];
    }
}
