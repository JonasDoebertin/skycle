<?php

namespace App\Strava\Components;

use App\Strava\Concerns\HandlesOAuth;
use App\Strava\Models\Athlete;
use Illuminate\Support\Carbon;

class TokenRefresher
{
    use HandlesOAuth;

    /**
     * @var \App\Strava\Models\Athlete
     */
    protected $athlete;

    /**
     * Refresh an athletes api access.
     *
     * @param \App\Strava\Models\Athlete $athlete
     */
    public function refresh(Athlete $athlete): void
    {
        // Preparations
        $this->athlete = $athlete;

        // Early exit if the athlete has not expired
        if (!$this->athlete->isExpired()) {
            return;
        }

        $this->refreshToken();
    }

    /**
     * Request and store a fresh access token.
     */
    protected function refreshToken(): void
    {
        $token = $this->refreshAccessToken($this->athlete->refresh_token);

        $this->athlete->update([
            'refresh_token'   => $token->getRefreshToken(),
            'access_token'    => $token->getToken(),
            'expires_at'      => Carbon::createFromTimestamp($token->getExpires()),
        ]);
    }
}
