<?php

namespace App\Strava\Jobs;

use App\Strava\Components\ActivityFetcher;
use App\Strava\Components\TokenRefresher;
use App\Strava\Jobs\Middleware\RateLimited;
use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Strava\Models\Activity
     */
    protected $athlete;

    /**
     * Create a new job instance.
     *
     * @param \App\Strava\Models\Athlete $athlete
     */
    public function __construct(Athlete $athlete)
    {
        $this->athlete = $athlete;
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
     * Execute the job.
     *
     * @param \App\Strava\Components\TokenRefresher $refresher
     * @return void
     */
    public function handle(TokenRefresher $refresher): void
    {
        $refresher->refresh($this->athlete);
    }
}
