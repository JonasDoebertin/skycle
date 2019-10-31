<?php

namespace App\Darksky\Components;

use App\Base\Models\Activity;
use App\Darksky\Events\ConditionFetched;
use App\Darksky\Models\Condition;
use DmitryIvanov\DarkSkyApi\Service;
use DmitryIvanov\DarkSkyApi\Weather\DataPoint;

class ConditionFetcher
{
    /**
     * @var \DmitryIvanov\DarkSkyApi\Service
     */
    protected $darksky;

    /**
     * @var \App\Base\Models\Activity
     */
    protected $activity;

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
        $this->activity = $activity;

        $observed = $this->fetchCondition();
        $this->saveCondition($observed);
    }

    /**
     * Fetch an observed weather condition from Darksky.
     *
     * @return \DmitryIvanov\DarkSkyApi\Weather\DataPoint
     * @throws \Throwable
     */
    protected function fetchCondition(): DataPoint
    {
        $response = $this->darksky
            ->units('si')
            ->language('de')
            ->location(
                $this->activity->start_latitude,
                $this->activity->start_longitude
            )
            ->timeMachine(
                $this->activity->start_time->toDateTimeString(),
                ['currently']
            );

        return $response->currently();
    }

    /**
     * Save an observed condition to the database.
     *
     * @param \DmitryIvanov\DarkSkyApi\Weather\DataPoint $observed
     */
    protected function saveCondition(DataPoint $observed)
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

        $this->activity->condition()->save($condition);

        event(new ConditionFetched($this->activity));
    }
}
