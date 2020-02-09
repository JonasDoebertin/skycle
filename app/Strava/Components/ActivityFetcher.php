<?php

namespace App\Strava\Components;

use App\Strava\Concerns\ConnectsToStrava;
use App\Strava\Concerns\ParsesTimezones;
use App\Strava\Events\ActivityFetched;
use App\Strava\Models\Activity;
use App\Strava\States\Activity\Fetched;
use App\Strava\States\Activity\Reported;
use Illuminate\Support\Carbon;

class ActivityFetcher
{
    use ConnectsToStrava,
        ParsesTimezones;

    /**
     * Fetch an activity in detail from Strava.
     *
     * @param \App\Strava\Models\Activity $activity
     * @throws \Strava\API\Exception
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    public function fetch(Activity $activity): void
    {
        // Early exit if the activity has already been fetched
        if (! $activity->hasState(Reported::class)) {
            return;
        }

        $details = $this->getStravaClient($activity->athlete)
            ->getActivity($activity->foreign_id, false);

        $this->saveDetails($activity, $details);
        $this->transitionToFetchedState($activity);
    }

    /**
     * Save an activities details.
     *
     * @param \App\Strava\Models\Activity $activity
     * @param array $details
     */
    protected function saveDetails(Activity $activity, array $details)
    {
        $startTime = Carbon::parse(data_get($details, 'start_date'), 'UTC');

        $activity->update([
            'name'            => data_get($details, 'name'),
            'description'     => data_get($details, 'description'),
            'timezone'        => $this->parseTimezone(data_get($details, 'timezone')),
            'start_time'      => $startTime,
            'start_latitude'  => data_get($details, 'start_latlng.0'),
            'start_longitude' => data_get($details, 'start_latlng.1'),
            'end_time'        => $startTime->clone()->addSeconds(data_get($details, 'elapsed_time')),
            'end_latitude'    => data_get($details, 'end_latlng.0'),
            'end_longitude'   => data_get($details, 'end_latlng.1'),
        ]);
    }

    /**
     * Transition the activity to "fetched" state.
     *
     * @param \App\Strava\Models\Activity $activity
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    protected function transitionToFetchedState(Activity $activity): void
    {
        $activity->transitionTo(Fetched::class);
    }
}
