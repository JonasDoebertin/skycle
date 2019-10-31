<?php

namespace App\Strava\Commands;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegisterWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers a strava webhook';

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
            $this->registerWebhook();
        } catch (TransferException $e) {
            return $this->failure($e);
        }

        return $this->success();
    }

    /**
     * Make a request to register the webhook.
     */
    protected function registerWebhook(): void
    {
        $this->guzzle->post(config('services.strava.webhooks.endpoint'), [
            'form_params' => $this->buildPayload(),
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);
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
            'callback_url'  => route('strava.webhook.validation'),
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

    /**
     * Exit with a failure message.
     *
     * @param \Exception $e
     * @return int
     */
    protected function failure(Exception $e): int
    {
        $this->error('Could not register Strava webhook.');
        $this->line($e->getMessage());
        $this->line($e->getResponse()->getBody()->getContents());

        return 1;
    }

    /**
     * Exit with a success message.
     *
     * @return int
     */
    protected function success(): int
    {
        $this->info('Successfully registered Strava webhook.');

        return 0;
    }
}
