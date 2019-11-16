<?php

namespace App\Darksky\Jobs;

use App\Base\Models\Activity;
use App\Darksky\Components\Fetcher;
use App\Darksky\Jobs\Middleware\RateLimited;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchCondition implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Base\Models\Activity
     */
    protected $activity;

    /**
     * Create a new job instance.
     *
     * @param \App\Base\Models\Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
        $this->onQueue('darksky');
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
     * @param \App\Darksky\Components\Fetcher $fetcher
     * @return void
     * @throws \Throwable
     */
    public function handle(Fetcher $fetcher): void
    {
        $fetcher->fetch($this->activity);
    }
}
