<?php

namespace App\Strava\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait HandlesWebhooks
{
    /**
     * Make a request to verify the webhook.
     */
    protected function verifyWebhook(): bool
    {
        $response = $this->guzzle->get(config('services.strava.webhooks.endpoint'), [
            'query' => $this->buildBaseRequestPayload(),
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);

        $webhooks = json_decode($response->getBody()->getContents());

        foreach ($webhooks as $webhook) {
            if (($webhook->callback_url ?? '') === route('app.strava.webhook.invoke')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Make a request to register the webhook.
     */
    protected function registerWebhook(): void
    {
        $this->guzzle->post(config('services.strava.webhooks.endpoint'), [
            'form_params' => $this->buildRegistrationRequestPayload(),
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /**
     * Make a request to deregister all webhooks.
     */
    protected function deregisterWebhooks(): void
    {
        $response = $this->guzzle->get(config('services.strava.webhooks.endpoint'), [
            'query' => $this->buildBaseRequestPayload(),
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);

        $webhooks = json_decode($response->getBody()->getContents());

        foreach ($webhooks as $webhook) {
            $this->deregisterWebhook($webhook->id);
        }
    }

    /**
     * Make a request to deregister a single webhook.
     *
     * @param int $id
     */
    protected function deregisterWebhook(int $id): void
    {
        $this->guzzle->delete(config('services.strava.webhooks.endpoint') . $id, [
            'query' => $this->buildBaseRequestPayload(),
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /**
     * Build the webhook request payload.
     *
     * @return array
     */
    protected function buildBaseRequestPayload(): array
    {
        return [
            'client_id'     => config('services.strava.key'),
            'client_secret' => config('services.strava.secret'),
        ];
    }

    /**
     * Build the webhook registration payload.
     *
     * @return array
     */
    protected function buildRegistrationRequestPayload(): array
    {
        return $this->buildBaseRequestPayload() + [
            'callback_url'  => route('app.strava.webhook.validation'),
            'verify_token'  => $this->getVerifyToken(),
        ];
    }

    /**
     * Get the current verify token.
     *
     * @return string
     */
    protected function getVerifyToken(): string
    {
        return Cache::rememberForever('strava.webhook.verifyToken', function (): string {
            return Str::random('64');
        });
    }
}
