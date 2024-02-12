<?php

namespace App\Policies;

use App\Models\Gym;
use App\Models\User;

class UserPolicy
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
    public function view(User $user, User $model): bool
    {
        if ($user->getKey() == $model->getKey()) {
            return true;
        }

        $gyms = Gym::query()
            ->where('owner_id', '=', $user->getKey())
            ->get();

        foreach ($gyms as $gym) {
            if ($gym->getKey() == $model->gym_id) {
                return true;
            }
        }

        return false;
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
    public function update(User $user, User $model): bool
    {
        if ($user->getKey() == $model->getKey()) {
            return true;
        }

        $gyms = Gym::query()
            ->where('owner_id', '=', $user->getKey())
            ->get();

        foreach ($gyms as $gym) {
            if ($gym->getKey() == $model->gym_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->getKey() == $model->getKey()) {
            return true;
        }

        $gyms = Gym::query()
            ->where('owner_id', '=', $user->getKey())
            ->get();

        foreach ($gyms as $gym) {
            if ($gym->getKey() == $model->gym_id) {
                return true;
            }
        }

        return false;
    }
}
