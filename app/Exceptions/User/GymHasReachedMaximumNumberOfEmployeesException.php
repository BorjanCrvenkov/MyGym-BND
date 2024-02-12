<?php

namespace App\Exceptions\User;

use Exception;
use Throwable;

class GymHasReachedMaximumNumberOfEmployeesException extends Exception
{
    const MESSAGE = "You have reached the maximum number of employees for your plan";
    const ERROR_CODE = 452;

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = self::MESSAGE, int $code = self::ERROR_CODE, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
