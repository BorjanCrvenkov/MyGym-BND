<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkingSchedule;
use Illuminate\Auth\Access\Response;

class WorkingSchedulePolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkingSchedule $workingSchedule): bool
    {
        if($user->getKey() == $workingSchedule->user_id){
            return true;
        }

        foreach ($user->gyms as $gym){
            if($gym->getKey == $workingSchedule->user->gym_id){
                return true;
            }
        }

        return false;
    }
}
