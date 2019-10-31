<?php

namespace App\Strava\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Requests\CallbackValidationRequest;
use Illuminate\Support\Facades\Response;

class WebhookValidationController extends Controller
{
    /**
     * @param \App\Strava\Requests\CallbackValidationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CallbackValidationRequest $request)
    {
        return Response::json([
            'hub.challenge' => $request->get('hub_challenge'),
        ]);
    }
}
