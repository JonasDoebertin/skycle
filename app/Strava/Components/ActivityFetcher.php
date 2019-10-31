<?php

namespace App\Strava\Components;

use App\Strava\Events\ActivityFetched;
use App\Strava\Concerns\ConnectsToStrava;
use App\Strava\Models\Activity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ActivityFetcher
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
     * Fetch an activity in detail from Strava.
     *
     * @param \App\Strava\Models\Activity $activity
     * @param bool $force
     * @throws \Strava\API\Exception
     */
    public function fetch(Activity $activity, $force = false)
    {
        $this->prepare($activity);

        // Early exit if the activity has already been fetched
        if (!$force && $this->hasBeenFetched()) {
            return;
        }

        $details = $this->strava->getActivity($activity->foreign_id, false);

        $this->saveActivityDetails($details);
    }

    protected function prepare(Activity $activity)
    {
        $this->activity = $activity;
        $this->strava = $this->getStravaClient($activity->athlete);
    }

    /**
     * Check whether the activity has already been fetched.
     *
     * @return bool
     */
    protected function hasBeenFetched()
    {
        return $this->activity->isFetched();
    }

    /**
     * Save an activities details.
     *
     * @param array $details
     */
    protected function saveActivityDetails(array $details)
    {
        $startTime = Carbon::parse(data_get($details, 'start_date'), 'UTC');

        $this->activity->update([
            'name'            => data_get($details, 'name'),
            'description'     => data_get($details, 'description'),
            'timezone'        => $this->parseTimezone(data_get($details, 'timezone')),
            'start_time'      => $startTime,
            'start_latitude'  => data_get($details, 'start_latlng.0'),
            'start_longitude' => data_get($details, 'start_latlng.1'),
            'end_time'        => $startTime->clone()->addSeconds(data_get($details, 'elapsed_time')),
            'end_latitude'    => data_get($details, 'end_latlng.0'),
            'end_longitude'   => data_get($details, 'end_latlng.1'),
            'fetched_at'      => now(),
        ]);

        event(new ActivityFetched($this->activity));
    }

    /**
     * Extract a usable timezone out of a Strava time zone string.
     *
     * @param string $timezone
     * @return string
     */
    protected function parseTimezone(string $timezone): string
    {
        preg_match(
            '/\([^\)]*\)\s(.*)/i',
            $timezone,
            $matches
        );

        return data_get($matches, 1, 'UTC');
    }
}
