<?php

namespace App\Strava\Jobs;

use App\Strava\Components\ActivityDecorator;
use App\Strava\Concerns\RefreshesTokens;
use App\Strava\Jobs\Middleware\RateLimited;
use App\Strava\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DecorateActivity implements ShouldQueue
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
     * @param \App\Strava\Components\ActivityDecorator $decorator
     * @return void
     * @throws \Strava\API\Exception
     */
    public function handle(ActivityDecorator $decorator): void
    {
        if ($this->tokenHasExpired($this->activity->athlete)) {
            $this->refreshTokenAndReschedule($this->activity->athlete, $this->activity);

            return;
        }

        $decorator->decorate($this->activity);
    }
}
