<?php

namespace App\Strava\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Models\Athlete;

class SettingsController extends Controller
{
    public function __invoke(Athlete $athlete)
    {
        return '';
    }
}
