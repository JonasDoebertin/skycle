<?php

namespace App\Http\Controllers;

use App\Darksky\Components\Fetcher;
use App\Strava\Jobs\FetchActivity;
use App\Strava\Jobs\SendActivity;
use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;

class TestController extends Controller
{
    /**
     * @var \App\Darksky\Components\Fetcher
     */
    protected $darkSkyFetcher;

    /**
     * TestController constructor.
     *
     * @param \App\Darksky\Components\Fetcher $darkSkyFetcher
     */
    public function __construct(Fetcher $darkSkyFetcher)
    {
        $this->darkSkyFetcher = $darkSkyFetcher;
    }

    public function fetch()
    {
        FetchActivity::dispatchNow(Athlete::first(), Activity::first());
    }

    public function decorate()
    {
        SendActivity::dispatchNow(Activity::first());
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
