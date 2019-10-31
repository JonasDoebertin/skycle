<?php

namespace App\Darksky\Jobs;

use App\Darksky\Components\ConditionFetcher;
use App\Base\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
//            new RateLimited('darksky'),
        ];
    }

    /**
     * Execute the job.
     *
     * @param \App\Darksky\Components\ConditionFetcher $fetcher
     * @return void
     * @throws \Throwable
     */
    public function handle(ConditionFetcher $fetcher): void
    {
        $fetcher->fetch($this->activity);
    }
}
