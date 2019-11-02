<?php

namespace App\Strava\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Components\ActivityManager;
use App\Strava\Events\ActivityCreated;
use App\Strava\Requests\CallbackRequest;
use Illuminate\Support\Facades\Response;

class WebhookController extends Controller
{
    /**
     * @var \App\Strava\Components\ActivityManager
     */
    protected $activities;

    /**
     * WebhookController constructor.
     *
     * @param \App\Strava\Components\ActivityManager $activities
     */
    public function __construct(ActivityManager $activities)
    {
        $this->activities = $activities;
    }

    /**
     * @param \App\Strava\Requests\CallbackRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Strava\Exceptions\ActivityAlreadyExistsException
     * @throws \App\Strava\Exceptions\UnknownAthleteException
     */
    public function __invoke(CallbackRequest $request)
    {
        if ($request->reportsActivity() && ! $this->isKnownActivity($request)) {
            $this->saveActivity($request);
        }

//        else if ($this->deautorizesAthlete($request)) {
//            $this->deauthorizeAthlete($request);
//        }

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
     * @throws \App\Strava\Exceptions\ActivityAlreadyExistsException
     * @throws \App\Strava\Exceptions\UnknownAthleteException
     */
    protected function saveActivity(CallbackRequest $request): void
    {
        $activity = $this->activities->store($request->get('object_id'), $request->get('owner_id'));

        event(new ActivityCreated($activity));
    }
}
