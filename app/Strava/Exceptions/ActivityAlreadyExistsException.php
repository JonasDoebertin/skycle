<?php

namespace App\Strava\Exceptions;

class ActivityAlreadyExistsException extends Exception
{
    /**
     * @param int $foreignId
     * @return static
     */
    public static function create(int $foreignId): self
    {
        return new static("Activity {$foreignId} already exists.");
    }
}
