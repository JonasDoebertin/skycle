<?php

namespace App\Strava\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Components\ActivityManager;
use App\Strava\Components\AthleteManager;
use App\Strava\Events\ActivityCreated;
use App\Strava\Events\AthleteDeauthorized;
use App\Strava\Models\Athlete;
use App\Strava\Requests\CallbackRequest;
use Illuminate\Support\Facades\Response;

class WebhookController extends Controller
{
    /**
     * @var \App\Strava\Components\AthleteManager
     */
    protected $athletes;

    /**
     * @var \App\Strava\Components\ActivityManager
     */
    protected $activities;

    /**
     * WebhookController constructor.
     *
     * @param \App\Strava\Components\AthleteManager $athletes
     * @param \App\Strava\Components\ActivityManager $activities
     */
    public function __construct(AthleteManager $athletes, ActivityManager $activities)
    {
        $this->activities = $activities;
        $this->athletes = $athletes;
    }

    /**
     * @param \App\Strava\Requests\CallbackRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Strava\Exceptions\ActivityAlreadyExistsException
     * @throws \App\Strava\Exceptions\UnknownAthleteException
     */
    public function __invoke(CallbackRequest $request)
    {
        if (
            $request->reportsActivity()
            && ($athlete = $this->athletes->get($request->get('owner_id')))
            && ! $this->isKnownActivity($request)
        ) {
            $this->saveActivity($request, $athlete);
        } elseif (
            $request->deautorizesAthlete()
            && ($athlete = $this->athletes->get($request->get('object_id')))
        ) {
            $this->athletes->deauthorize($athlete);
            event(new AthleteDeauthorized($athlete));
        }

        return Response::json([]);
    }

    /**
     * Check whether a reported activity is already known.
     *
     * @param \App\Strava\Requests\CallbackRequest $request
     * @return bool
     */
    protected function isKnownActivity(CallbackRequest $request): bool
    {
        return $this->activities->has($request->get('object_id'));
    }

    /**
     * Save a newly reported activity.
     *
     * @param \App\Strava\Requests\CallbackRequest $request
     * @param \App\Strava\Models\Athlete $athlete
     * @throws \App\Strava\Exceptions\ActivityAlreadyExistsException
     */
    protected function saveActivity(CallbackRequest $request, Athlete $athlete): void
    {
        $activity = $this->activities->store($athlete, $request->get('object_id'));

        event(new ActivityCreated($activity));
    }
}
