<?php

namespace App\Strava\Commands;

use App\Strava\Concerns\HandlesWebhooks;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;

class VerifyWebhook extends Command
{
    use HandlesWebhooks;

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
            if (! $this->verifyWebhook()) {
                return $this->unregistered();
            }
        } catch (TransferException $e) {
            return $this->failure($e);
        }

        return $this->registered();
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
