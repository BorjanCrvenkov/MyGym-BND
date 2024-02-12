<?php

namespace App\Policies;

use App\Models\ExpenseType;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpenseTypePolicy
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
    public function view(User $user, ExpenseType $expenseType): bool
    {
        $gymOwnerId = Gym::query()
            ->join('expense_types', 'expense_types.gym_id', '=', 'gyms.id')
            ->where('expense_types.id', '=', $expenseType->getKey())
            ->first()
            ->owner_id;

        return $user->getKey() == $gymOwnerId;
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
    public function update(User $user, ExpenseType $expenseType): bool
    {
        $gymOwnerId = Gym::query()
            ->join('expense_types', 'expense_types.gym_id', '=', 'gyms.id')
            ->where('expense_types.id', '=', $expenseType->getKey())
            ->first()
            ->owner_id;

        return $user->getKey() == $gymOwnerId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ExpenseType $expenseType): bool
    {
        $gymOwnerId = Gym::query()
            ->join('expense_types', 'expense_types.gym_id', '=', 'gyms.id')
            ->where('expense_types.id', '=', $expenseType->getKey())
            ->first()
            ->owner_id;

        return $user->getKey() == $gymOwnerId;
    }
}
