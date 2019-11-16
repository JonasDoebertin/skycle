<?php

namespace App\Strava\Events;

use App\Strava\Models\Athlete;
use Illuminate\Queue\SerializesModels;

class TokenRefreshed
{
    use SerializesModels;

    /**
     * @var \App\Strava\Models\Athlete
     */
    public $athlete;

    /**
     * Create a new event instance.
     *
     * @param \App\Strava\Models\Athlete $athlete
     */
    public function __construct(Athlete $athlete)
    {
        $this->athlete = $athlete;
    }
}
