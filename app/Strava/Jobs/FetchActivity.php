<?php

namespace App\Strava\Jobs;

use App\Strava\Components\ActivityFetcher;
use App\Strava\Components\Redispatcher;
use App\Strava\Events\ActivityFetched;
use App\Strava\Jobs\Middleware\RateLimited;
use App\Strava\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class FetchActivity implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * @var \App\Strava\Models\Activity
     */
    public $activity;

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
    public function middleware(): array
    {
        return [
            new RateLimited(),
        ];
    }

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): Carbon
    {
        return now()->addHour();
    }

    /**
     * Execute the job.
     *
     * @param \App\Strava\Components\Redispatcher $redispatcher
     * @param \App\Strava\Components\ActivityFetcher $fetcher
     * @return void
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     * @throws \Strava\API\Exception
     */
    public function handle(Redispatcher $redispatcher, ActivityFetcher $fetcher): void
    {
        // Refresh the access token if it has expired
        if ($this->activity->athlete->tokenHasExpired()) {
            $redispatcher->redispatch(self::class, $this->activity);

            return;
        }

        // Fetch an activity in detail from Strava
        $fetcher->fetch($this->activity);

        // Notify application about a newly fetched activity
        event(new ActivityFetched($this->activity));
    }
}
