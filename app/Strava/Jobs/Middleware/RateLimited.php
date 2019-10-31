<?php

namespace App\Strava\Jobs\Middleware;

use Illuminate\Support\Facades\Redis;

class RateLimited
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, callable $next): void
    {
        Redis::throttle('strava')
            ->block(0)
            ->allow(config('services.strava.ratelimit.allow'))
            ->every(config('services.strava.ratelimit.every'))
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release(60);
            });
    }
}
