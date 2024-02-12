<?php

namespace App\Services;

use App\Models\Role;

class RoleService extends BaseService
{
    /**
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
