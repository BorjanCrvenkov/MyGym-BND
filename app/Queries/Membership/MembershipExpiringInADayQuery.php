<?php

namespace App\Queries\Membership;

use App\Models\Membership;
use App\Queries\BaseQuery;
use Illuminate\Database\Eloquent\Builder;

class MembershipExpiringInADayQuery  extends BaseQuery
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $endDate = now()->addDay()->toDateString();

        return Membership::query()
            ->where('end_date', '=', $endDate)
            ->with(['membership_type', 'user', 'gym']);
    }
}
