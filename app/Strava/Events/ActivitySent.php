<?php

namespace App\Strava\Events;

use App\Base\Models\Activity;
use Illuminate\Queue\SerializesModels;

class ActivitySent
{
    use SerializesModels;

    /**
     * @var \App\Base\Models\Activity
     */
    public $activity;

    /**
     * Create a new event instance.
     *
     * @param \App\Base\Models\Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }
}
