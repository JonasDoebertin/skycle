<?php

namespace App\Strava\Components;

use App\Strava\Concerns\HandlesOAuth;
use App\Strava\Events\TokenRefreshed;
use App\Strava\Models\Athlete;
use Illuminate\Support\Carbon;

class TokenRefresher
{
    use HandlesOAuth;

    /**
     * Refresh an athletes api access.
     *
     * @param \App\Strava\Models\Athlete $athlete
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function refresh(Athlete $athlete): void
    {
        // Early exit if the athlete has not expired
        if (! $athlete->tokenHasExpired()) {
            return;
        }

        $this->refreshToken($athlete);
        $this->notify($athlete);
    }

    /**
     * Request and store a fresh access token.
     *
     * @param \App\Strava\Models\Athlete $athlete
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    protected function refreshToken(Athlete $athlete): void
    {
        $token = $this->refreshAccessToken($athlete->refresh_token);

        $athlete->update([
            'refresh_token' => $token->getRefreshToken(),
            'access_token'  => $token->getToken(),
            'expires_at'    => Carbon::createFromTimestamp($token->getExpires()),
        ]);
    }

    /**
     * Notify application about refreshed athlete.
     *
     * @param \App\Strava\Models\Athlete $athlete
     */
    protected function notify(Athlete $athlete): void
    {
        event(new TokenRefreshed($athlete));
    }
}
