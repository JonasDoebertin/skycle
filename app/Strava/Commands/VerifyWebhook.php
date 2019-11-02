<?php

namespace App\Strava\Commands;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class VerifyWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:webhook:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies the strava webhook';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Create a new command instance.
     *
     * @param \GuzzleHttp\Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        parent::__construct();

        $this->guzzle = $guzzle;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            if (!$this->verifyWebhook()) {
                return $this->unregistered();
            }
        } catch (TransferException $e) {
            return $this->failure($e);
        }

        return $this->registered();
    }

    /**
     * Make a request to register the webhook.
     */
    protected function verifyWebhook(): bool
    {
        $response = $this->guzzle->get(config('services.strava.webhooks.endpoint'), [
            'query' => $this->buildPayload(),
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);

        $webhooks = json_decode($response->getBody()->getContents());

        foreach ($webhooks as $webhook) {
            if (($webhook->callback_url ?? '') === route('strava.webhook.invoke')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the webhook registration payload.
     *
     * @return array
     */
    protected function buildPayload(): array
    {
        return [
            'client_id'     => config('services.strava.key'),
            'client_secret' => config('services.strava.secret'),
        ];
    }

    /**
     * Exit with a failure message.
     *
     * @param \Exception $e
     * @return int
     */
    protected function failure(Exception $e): int
    {
        $this->error('Could not verify Strava webhook.');

        return 1;
    }

    protected function unregistered(): int
    {
        $this->error('Strava webhook is either unregistered or registered for the wrong domain.');

        return 2;
    }

    /**
     * Exit with a success message.
     *
     * @return int
     */
    protected function registered(): int
    {
        $this->info('Successfully verified Strava webhook.');

        return 0;
    }
}
