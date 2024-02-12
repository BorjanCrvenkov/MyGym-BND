<?php

namespace App\Policies;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GymPolicy
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
    public function view(User $user, Gym $gym): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_business;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Gym $gym): bool
    {
        return $user->getKey() == $gym->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Gym $gym): bool
    {
        return $user->getKey() == $gym->owner_id;
    }
}
