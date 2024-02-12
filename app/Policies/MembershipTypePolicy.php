<?php

namespace App\Policies;

use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MembershipTypePolicy
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
    public function view(User $user, MembershipType $membershipType): bool
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
    public function update(User $user, MembershipType $membershipType): bool
    {
        return $user->getKey() == $membershipType->gym->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MembershipType $membershipType): bool
    {
        return $user->getKey() == $membershipType->gym->owner_id;
    }
}
