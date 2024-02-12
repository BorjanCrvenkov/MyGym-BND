<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;

class Expense extends BaseModel
{
    protected $fillable = [
        'name',
        'status',
        'paid_at',
        'expense_type_id',
    ];

    public function allowedFilters(): array
    {
        return [
            AllowedFilter::scope('gym_id'),
        ];
    }

    /**
     * @return BelongsTo
     */
    public function expense_type(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id', 'id');
    }

    /**
     * @param $query
     * @param $gym_id
     * @return void
     */
    public function scopeGymId($query, $gym_id): void
    {
        $query->whereExists(function ($subQuery) use ($gym_id) {
            $subQuery->select(DB::raw('1'))
                ->from('expense_types')
                ->where('expense_types.gym_id', '=', $gym_id)
                ->whereColumn('expenses.expense_type_id', '=', 'expense_types.id');
        });
    }
}
