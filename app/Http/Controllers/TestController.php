<?php

namespace App\Http\Controllers;

use App\Darksky\Components\ConditionFetcher;
use App\Strava\Jobs\DecorateActivity;
use App\Strava\Jobs\FetchActivity;
use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;

class TestController extends Controller
{
    /**
     * @var \App\Darksky\Components\ConditionFetcher
     */
    protected $darkSkyFetcher;

    /**
     * TestController constructor.
     *
     * @param \App\Darksky\Components\ConditionFetcher $darkSkyFetcher
     */
    public function __construct(ConditionFetcher $darkSkyFetcher)
    {
        $this->darkSkyFetcher = $darkSkyFetcher;
    }

    public function fetch()
    {
        FetchActivity::dispatchNow(Athlete::first(), Activity::first());
    }

    public function decorate()
    {
        DecorateActivity::dispatchNow(Activity::first());
    }

    public function weather()
    {
        $activity = Activity::first();

        $condition = $this->darkSkyFetcher->fetch(
            $activity->start_time,
            $activity->start_latitude,
            $activity->start_longitude
        );

        dd($condition);
    }
}
