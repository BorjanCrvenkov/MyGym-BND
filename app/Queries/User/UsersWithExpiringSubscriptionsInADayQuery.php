<?php

namespace App\Queries\User;

use App\Models\User;
use App\Queries\BaseQuery;
use Illuminate\Database\Eloquent\Builder;

class UsersWithExpiringSubscriptionsInADayQuery extends BaseQuery
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $endDate = now()->addDay()->toDateString();

        return User::query()
            ->select('users.*')
            ->join('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->where('subscriptions.ends_at', '=', $endDate)
            ->with(['plan',]);
    }
}
