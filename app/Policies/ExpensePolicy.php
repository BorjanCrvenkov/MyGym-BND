<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
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
    public function view(User $user, Expense $expense): bool
    {
        $gymOwnerId = Gym::query()
            ->join('expense_types', 'expense_types.gym_id', '=', 'gyms.id')
            ->where('expense_types.id', '=', $expense->expense_type_id)
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
    public function update(User $user, Expense $expense): bool
    {
        $gymOwnerId = Gym::query()
            ->join('expense_types', 'expense_types.gym_id', '=', 'gyms.id')
            ->where('expense_types.id', '=', $expense->expense_type_id)
            ->first()
            ->owner_id;

        return $user->getKey() == $gymOwnerId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expense $expense): bool
    {
        $gymOwnerId = Gym::query()
            ->join('expense_types', 'expense_types.gym_id', '=', 'gyms.id')
            ->where('expense_types.id', '=', $expense->expense_type_id)
            ->first()
            ->owner_id;

        return $user->getKey() == $gymOwnerId;
    }
}
