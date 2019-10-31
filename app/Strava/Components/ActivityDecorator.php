<?php

namespace App\Strava\Components;

use App\Strava\Concerns\ConnectsToStrava;
use App\Strava\Models\Activity;

class ActivityDecorator
{
    use ConnectsToStrava;

    /**
     * @var \App\Strava\Models\Activity
     */
    protected $activity;

    /**
     * @var \Strava\API\Client
     */
    protected $strava;

    /**
     * Decorate an activity based on the prefetch weather condition.
     *
     * @param \App\Strava\Models\Activity $activity
     * @return \App\Strava\Models\Activity
     * @throws \Strava\API\Exception
     */
    public function decorate(Activity $activity): Activity
    {
        // Preparations
        $this->activity = $activity;
        $this->strava = $this->getStravaClient($activity->athlete);

        // Early exit if the activity has already been decorated
        if ($this->activity->isDecorated()) {
            return $this->activity;
        }

        // Decoration
        $this->decorateActivity();

        return $this->activity;
    }

    /**
     * Decorate an activity.
     *
     * @throws \Strava\API\Exception
     */
    protected function decorateActivity(): void
    {
        $emoji = $this->activity->condition->toEmoji();
        $summary = $this->activity->condition->toSummary();

        $this->strava->updateActivity(
            $this->activity->foreign_id,
            "{$emoji} {$this->activity->name}",
            null,
            null,
            null,
            null,
            null,
            "{$summary}\n{$this->activity->description}"
        );

        $this->activity->update([
            'decorated_at' => now(),
        ]);
    }
}
