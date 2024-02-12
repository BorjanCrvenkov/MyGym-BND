<?php

namespace App\LogicValidators\Gym;

use App\Exceptions\Gym\UserHasReachedMaximumNumberOfGymsException;
use App\LogicValidators\BaseLogicValidator;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRegisterGymNumberLogicValidator extends BaseLogicValidator
{
    /**
     * @return void
     * @throws UserHasReachedMaximumNumberOfGymsException
     */
    public function validate(): void
    {
        $user = Auth::user();

        if(!$user){
            return;
        }

        if(!$user->is_business){
            return;
        }

        $currentGymNumber = $this->calculateCurrentUserGymNumber($user);

        $plan = $user->plan;

        if($currentGymNumber < $plan->number_of_allowed_gyms){
            return;
        }

        throw new UserHasReachedMaximumNumberOfGymsException();
    }

    /**
     * @param User $user
     * @return int
     */
    public function calculateCurrentUserGymNumber (User $user): int
    {
        return Gym::query()
            ->where('owner_id', '=', $user->getKey())
            ->count();
    }
}
