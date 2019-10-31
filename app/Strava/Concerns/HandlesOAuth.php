<?php

namespace App\Strava\Concerns;

use League\OAuth2\Client\Token\AccessTokenInterface;
use Strava\API\OAuth;

trait HandlesOAuth
{
    /**
     * Get the oAuth authorization url.
     *
     * @return string
     */
    protected function getAuthorizationUrl(): string
    {
        $oauth = new OAuth([
            'clientId'     => config('services.strava.key'),
            'clientSecret' => config('services.strava.secret'),
            'redirectUri'  => route('strava.oauth.callback'),
        ]);

        return $oauth->getAuthorizationUrl([
            'scope' => $this->getRequiredScopes(),
        ]);
    }

    /**
     * Get the oAuth token exchange url.
     *
     * @param string $authorizationToken
     * @return \League\OAuth2\Client\Token\AccessToken
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    protected function getAccessToken(string $authorizationToken): AccessTokenInterface
    {
        $oauth = new OAuth([
            'clientId'     => config('services.strava.key'),
            'clientSecret' => config('services.strava.secret'),
            'redirectUri'  => route('strava.oauth.callback'),
        ]);

        return $oauth->getAccessToken('authorization_code', [
            'code' => $authorizationToken,
        ]);
    }

    protected function refreshAccessToken(string $refreshToken): AccessTokenInterface
    {
        $oauth = new OAuth([
            'clientId'     => config('services.strava.key'),
            'clientSecret' => config('services.strava.secret'),
            'redirectUri'  => route('strava.oauth.callback'),
        ]);

        return $oauth->getAccessToken('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     * Get the required token scopes.
     *
     * @return array
     */
    protected function getRequiredScopes(): array
    {
        return [
            'read',
            'activity:read',
            'activity:read_all',
            'activity:write',
        ];
    }
}
