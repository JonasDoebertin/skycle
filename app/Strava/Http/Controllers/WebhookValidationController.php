<?php

namespace App\Strava\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Http\Requests\CallbackValidationRequest;
use Illuminate\Support\Facades\Response;

class WebhookValidationController extends Controller
{
    /**
     * @param \App\Strava\Http\Requests\CallbackValidationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CallbackValidationRequest $request)
    {
        return Response::json([
            'hub.challenge' => $request->get('hub_challenge'),
        ]);
    }
}
