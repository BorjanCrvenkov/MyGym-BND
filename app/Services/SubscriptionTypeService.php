<?php

namespace App\Services;

use App\Models\SubscriptionType;

class SubscriptionTypeService extends BaseService
{
    /**
     * @param SubscriptionType $model
     */
    public function __construct(SubscriptionType $model)
    {
        parent::__construct($model);
    }
}
