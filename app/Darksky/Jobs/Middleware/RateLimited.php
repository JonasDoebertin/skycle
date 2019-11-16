<?php

namespace App\Darksky\Jobs\Middleware;

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
        Redis::throttle('darksky')
            ->block(0)
            ->allow(config('services.darksky.ratelimit.allow'))
            ->every(config('services.darksky.ratelimit.every'))
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release(60);
            });
    }
}
