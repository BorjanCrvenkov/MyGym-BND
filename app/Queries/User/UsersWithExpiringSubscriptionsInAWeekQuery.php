<?php

namespace App\Queries\User;

use App\Models\User;
use App\Queries\BaseQuery;
use Illuminate\Database\Eloquent\Builder;

class UsersWithExpiringSubscriptionsInAWeekQuery extends BaseQuery
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $endDate = now()->addWeek()->toDateString();

        return User::query()
            ->select('users.*')
            ->join('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->where('subscriptions.ends_at', '=', $endDate)
            ->with(['plan',]);
    }
}
