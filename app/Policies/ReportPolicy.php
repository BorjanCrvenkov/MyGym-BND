<?php

namespace App\Policies;

use App\Enums\ReportTypeEnum;
use App\Models\Gym;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * @param User|null $user
     * @return ?bool
     */
    public function before(?User $user): ?bool
    {
        if (isset($user) && $user->is_admin) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_business;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        if ($report->model_type == ReportTypeEnum::GYM_PROBLEM->value) {
            $gymOwnerId = Gym::query()
                ->find($report->model_id)
                ->owner_id;
            return $user->getKey() == $gymOwnerId;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->is_member;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        if ($report->model_type == ReportTypeEnum::GYM_PROBLEM->value) {
            $gymOwnerId = Gym::query()
                ->find($report->model_id)
                ->owner_id;
            return $user->getKey() == $gymOwnerId;
        }

        return false;
    }
}
