<?php

namespace App\Policies;

use App\Enums\FileTypeEnum;
use App\Models\Expense;
use App\Models\File;
use App\Models\Gym;
use App\Models\User;

class FilePolicy
{
    /**
     * @param User|null $user
     * @return ?bool
     */
    public function before(?User $user): ?bool
    {
        if(isset($user) && $user->is_admin) {
            return true;
        }

        return true;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, File $file): bool
    {
        if($file->file_type == FileTypeEnum::USER_IMAGE){
            return $user->getKey() == $file->model_id;
        }else if($file->file_type == FileTypeEnum::USER_IDENTIFICATION_DOCUMENT){
            return $user->getKey() == $file->model_id;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, File $file): bool
    {
        if($file->file_type == FileTypeEnum::USER_IMAGE){
            return $user->getKey() == $file->model_id;
        }else if($file->file_type == FileTypeEnum::USER_IDENTIFICATION_DOCUMENT){
            return $user->getKey() == $file->model_id;
        }else if($file->file_type == FileTypeEnum::GYM_COVER_IMAGE){
            return true;
        }else if($file->file_type == FileTypeEnum::GYM_IMAGE){
            return true;
        }else if($file->file_type == FileTypeEnum::EQUIPMENT_IMAGE){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, File $file): bool
    {
        return true;
    }
}
