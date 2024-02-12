<?php

namespace App\Traits;

trait QueryBuilderTrait
{
    /**
     * @return array|string[]
     */
    public function allowedFilters(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function allowedSorts(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function defaultSorts(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function allowedIncludes(): array
    {
        return [];
    }
}
