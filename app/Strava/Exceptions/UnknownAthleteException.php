<?php

namespace App\Strava\Exceptions;

class UnknownAthleteException extends Exception
{
    /**
     * @param int $foreignId
     * @return static
     */
    public static function create(int $foreignId): self
    {
        return new static("Athlete {$foreignId} is unknown.");
    }
}
