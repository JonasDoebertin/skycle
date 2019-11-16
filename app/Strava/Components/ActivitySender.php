<?php

namespace App\Strava\Components;

use App\Strava\Concerns\ConnectsToStrava;
use App\Strava\Events\ActivitySent;
use App\Strava\Models\Activity;
use App\Strava\States\Activity\Decorated;
use App\Strava\States\Activity\Fetched;
use App\Strava\States\Activity\Sent;

class ActivitySender
{
    use ConnectsToStrava;

    /**
     * Send an activity back to Strava.
     *
     * @param \App\Strava\Models\Activity $activity
     * @throws \Strava\API\Exception
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    public function send(Activity $activity): void
    {
        // Early exit if the activity has already been decorated
        if (!$activity->hasState(Decorated::class)) {
            return;
        }

        $this->sendActivity($activity);
        $this->transitionToSentState($activity);
        $this->notify($activity);
    }

    /**
     * Decorate an activity.
     *
     * @param \App\Strava\Models\Activity $activity
     * @throws \Strava\API\Exception
     */
    protected function sendActivity(Activity $activity): void
    {
        $emoji = $activity->condition->toEmoji();
        $summary = $activity->condition->toSummary();

        $this->getStravaClient($activity->athlete)
            ->updateActivity(
                $activity->foreign_id,
                "{$emoji} {$activity->name}",
                null, null, null, null, null,
                "{$summary}\n{$activity->description}"
            );
    }

    /**
     * Transition the activity to "sent" state.
     *
     * @param \App\Strava\Models\Activity $activity
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    protected function transitionToSentState(Activity $activity): void
    {
        $activity->transitionTo(Sent::class);
    }

    /**
     * Notify application about a newly sent activity.
     *
     * @param \App\Strava\Models\Activity $activity
     */
    protected function notify(Activity $activity): void
    {
        event(new ActivitySent($activity));
    }
}
