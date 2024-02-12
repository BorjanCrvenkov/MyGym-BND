<?php

namespace App\Exceptions\Gym;

use Exception;
use Throwable;

class UserHasReachedMaximumNumberOfGymsException extends Exception
{
    const MESSAGE = "You have reached the maximum number of gyms for your plan";
    const ERROR_CODE = 453;

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
