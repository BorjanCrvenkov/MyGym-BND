<?php

namespace App\Services;

use App\Models\MembershipType;

class MembershipTypeService extends BaseService
{
    /**
     * @param MembershipType $model
     */
    public function __construct(MembershipType $model)
    {
        parent::__construct($model);
    }
}
