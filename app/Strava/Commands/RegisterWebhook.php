<?php

namespace App\Strava\Commands;

use App\Strava\Concerns\HandlesWebhooks;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;

class RegisterWebhook extends Command
{
    use HandlesWebhooks;

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
            if ($this->verifyWebhook()) {
                return $this->verified();
            }

            $this->deregisterWebhooks();
            $this->registerWebhook();
        } catch (TransferException $e) {
            return $this->failed($e);
        }

        return $this->registered();
    }

    /**
     * Exit with a success message.
     *
     * @return int
     */
    protected function verified(): int
    {
        $this->info('Successfully verified Strava webhook.');

        return 0;
    }

    /**
     * Exit with a success message.
     *
     * @return int
     */
    protected function registered(): int
    {
        $this->info('Successfully registered Strava webhook.');

        return 0;
    }

    /**
     * Exit with a failure message.
     *
     * @param \Exception $e
     * @return int
     */
    protected function failed(Exception $e): int
    {
        $this->error('Could not register Strava webhook.');
        $this->line($e->getMessage());

        return 1;
    }
}
