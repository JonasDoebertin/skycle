<?php

namespace App\Strava\Jobs;

use App\Strava\Components\ActivitySender;
use App\Strava\Concerns\RefreshesTokens;
use App\Strava\Jobs\Middleware\RateLimited;
use App\Strava\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendActivity implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        RefreshesTokens,
        SerializesModels;

    /**
     * @var \App\Strava\Models\Activity
     */
    protected $activity;

    /**
     * Create a new job instance.
     *
     * @param \App\Strava\Models\Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
        $this->onQueue('strava');
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [
            new RateLimited(),
        ];
    }

    /**
     * Execute the job.
     *
     * @param \App\Strava\Components\ActivitySender $sender
     * @return void
     * @throws \Strava\API\Exception
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    public function handle(ActivitySender $sender): void
    {
        if ($this->activity->athlete->tokenHasExpired()) {
            $this->refreshTokenAndReschedule($this->activity->athlete, $this->activity);

            return;
        }

        $sender->send($this->activity);
    }
}
