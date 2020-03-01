<?php

namespace App\Strava\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Http\Requests\UpdateAthleteRequest;
use App\Strava\Models\Athlete;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show the specified athlete.
     *
     * @param \App\Strava\Models\Athlete $athlete
     * @return \Illuminate\View\View
     */
    public function show(Athlete $athlete): View
    {
        return view('app.strava.settings')
            ->with([
                'athlete' => $athlete,
            ]);
    }

    /**
     * Update the specified athlete.
     *
     * @param \App\Strava\Http\Requests\UpdateAthleteRequest $request
     * @param \App\Strava\Models\Athlete $athlete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAthleteRequest $request, Athlete $athlete): RedirectResponse
    {
        $athlete->update($request->validated());

        flash()->success('Account updated');

        return redirect()
            ->route('app.strava.athlete.show', ['athlete' => $athlete]);
    }

    /**
     * Remove the specified athlete.
     *
     * @param \App\Strava\Models\Athlete $athlete
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Athlete $athlete): RedirectResponse
    {
        $athlete->delete();

        return redirect()->route('app.dashboard');
    }
}
