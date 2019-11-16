<?php

namespace App\Darksky\Components;

use App\Base\Models\Activity;
use App\Darksky\Events\ActivityDiscarded;
use App\Darksky\Events\ConditionFetched;
use App\Darksky\Models\Condition;
use DmitryIvanov\DarkSkyApi\Service;
use DmitryIvanov\DarkSkyApi\Weather\DataPoint;

class Fetcher
{
    /**
     * @var \DmitryIvanov\DarkSkyApi\Service
     */
    protected $darksky;

    /**
     * Fetcher constructor.
     *
     * @param \DmitryIvanov\DarkSkyApi\Service $darksky
     */
    public function __construct(Service $darksky)
    {
        $this->darksky = $darksky;
    }

    /**
     * Fetch and save an observed condition for a given point in time and location.
     *
     * @param \App\Base\Models\Activity $activity
     * @throws \Throwable
     */
    public function fetch(Activity $activity)
    {
        if (! $this->hasLocationData($activity)) {
            $this->discard($activity);

            return;
        }

        $observed = $this->fetchCondition($activity);
        $this->saveCondition($activity, $observed);
        $this->notify($activity);
    }

    /**
     * Check if an activity has location data attached.
     *
     * @param \App\Base\Models\Activity $activity
     * @return bool
     */
    protected function hasLocationData(Activity $activity): bool
    {
        return $activity->start_latitude !== null
            && $activity->start_longitude !== null;
    }

    /**
     * Discard an activity.
     *
     * @param \App\Base\Models\Activity $activity
     */
    protected function discard(Activity $activity): void
    {
        event(new ActivityDiscarded($activity));
    }

    /**
     * Fetch an observed weather condition from Darksky.
     *
     * @param \App\Base\Models\Activity $activity
     * @return \DmitryIvanov\DarkSkyApi\Weather\DataPoint
     * @throws \Throwable
     */
    protected function fetchCondition(Activity $activity): DataPoint
    {
        $response = $this->darksky
            ->units('si')  // TODO: make configurable
            ->language('de') // TODO: make configurable
            ->location(
                $activity->start_latitude,
                $activity->start_longitude
            )
            ->timeMachine(
                $activity->start_time->toDateTimeString(),
                ['currently']
            );

        return $response->currently();
    }

    /**
     * Save an observed condition to the database.
     *
     * @param \App\Base\Models\Activity $activity
     * @param \DmitryIvanov\DarkSkyApi\Weather\DataPoint $observed
     */
    protected function saveCondition(Activity $activity, DataPoint $observed)
    {
        $condition = tap(new Condition(), function (Condition $condition) use ($observed) {
            $condition->fill([
                'summary'              => $observed->summary(),
                'icon'                 => $observed->icon(),
                'temperature'          => $observed->temperature(),
                'apparent_temperature' => $observed->apparentTemperature(),
                'wind_bearing'         => $observed->windBearing(),
                'wind_speed'           => $observed->windSpeed(),
                'wind_gust'            => $observed->windGust(),
                'moon_phase'           => $observed->moonPhase(),
                'cloud_coverage'       => $observed->cloudCover(),
            ])->save();
        });

        $activity->condition()->save($condition);
    }

    /**
     * Notify application about availability of a condition for an activity.
     *
     * @param \App\Base\Models\Activity $activity
     */
    protected function notify(Activity $activity): void
    {
        event(new ConditionFetched($activity));
    }
}
