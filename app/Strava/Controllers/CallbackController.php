<?php

namespace App\Strava\Controllers;

use App\Http\Controllers\Controller;
use App\Strava\Concerns\HandlesOAuth;
use App\Strava\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use League\OAuth2\Client\Token\AccessTokenInterface;

class CallbackController extends Controller
{
    use HandlesOAuth;

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function __invoke(Request $request)
    {
        if (! $this->providedWithCorrectScope($request)) {
            return redirect($this->getAuthorizationUrl());
        }

        $token = $this->getAccessToken($request->get('code'));

        $this->saveAthlete($token);

        return redirect()
            ->route('app.dashboard');
    }

    /**
     * Check whether the user provided the correct scope.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function providedWithCorrectScope(Request $request): bool
    {
        return count(array_intersect($this->getRequiredScopes(), $this->getProvidedScopes($request)))
            === count($this->getRequiredScopes());
    }

    /**
     * Get the provided scope from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getProvidedScopes(Request $request): array
    {
        return explode(',', $request->get('scope', ''));
    }

    /**
     * Save the athlete.
     *
     * @param \League\OAuth2\Client\Token\AccessTokenInterface $token
     */
    protected function saveAthlete(AccessTokenInterface $token): void
    {
        if ($this->isKnownAthlete($token)) {
            return;
        }

        tap(new Athlete([
            'user_id'         => auth()->user()->id,
            'foreign_id'      => data_get($token->getValues(), 'athlete.id'),
            'first_name'      => data_get($token->getValues(), 'athlete.firstname'),
            'last_name'       => data_get($token->getValues(), 'athlete.lastname'),
            'profile_picture' => data_get($token->getValues(), 'athlete.profile'),
            'refresh_token'   => $token->getRefreshToken(),
            'access_token'    => $token->getToken(),
            'expires_at'      => Carbon::createFromTimestamp($token->getExpires()),
        ]))->save();

        flash()->success('New Strava account added!');
    }

    /**
     * Check whether this athlete is already known.
     *
     * @param \League\OAuth2\Client\Token\AccessTokenInterface $token
     * @return bool
     */
    protected function isKnownAthlete(AccessTokenInterface $token): bool
    {
        $knownAthlete = Athlete::query()
            ->where('foreign_id', data_get($token->getValues(), 'athlete.id'))
            ->first();

        if ($knownAthlete) {
            if ($knownAthlete->user_id === auth()->user()->id) {
                flash()->success('You\'ve already connected this Strava account.');
            } else {
                flash()->error('This Strava account is connected to another user.');
            }

            return true;
        }

        return false;
    }
}
