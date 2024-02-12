<?php

namespace App\Policies;

use App\Models\SocialMediaLinks;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SocialMediaLinksPolicy
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
    public function view(User $user, SocialMediaLinks $socialMediaLinks): bool
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
    public function update(User $user, SocialMediaLinks $socialMediaLinks): bool
    {
        return $user->getKey() == $socialMediaLinks->gym->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SocialMediaLinks $socialMediaLinks): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SocialMediaLinks $socialMediaLinks): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SocialMediaLinks $socialMediaLinks): bool
    {
        //
    }
}
