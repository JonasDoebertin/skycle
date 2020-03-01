<?php

namespace App\Strava\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Concerns\HandlesOAuth;
use Illuminate\Http\RedirectResponse;

class AuthorizeController extends Controller
{
    use HandlesOAuth;

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(): RedirectResponse
    {
        return redirect($this->getAuthorizationUrl());
    }
}
