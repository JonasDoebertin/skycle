<?php

namespace App\Darksky\Events;

use App\Base\Models\Activity;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ConditionFetched
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
