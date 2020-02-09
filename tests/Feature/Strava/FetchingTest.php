<?php

namespace Tests\Feature\Strava;

use App\Strava\Components\ActivityFetcher;
use App\Strava\Events\ActivityCreated;
use App\Strava\Events\ActivityFetched;
use App\Strava\Jobs\FetchActivity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\Concerns\WithStrava;
use Tests\TestCase;

class FetchingTest extends TestCase
{
    use WithFaker,
        WithStrava;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testJobGetsDispatched()
    {
        Queue::fake();

        $activity = $this->hasActivity();
        event(new ActivityCreated($activity));

        Queue::assertPushedOn('strava', FetchActivity::class, function (FetchActivity $job) use ($activity) {
            return $job->activity->is($activity);
        });
    }

    public function testJobCallsFetcherAndFiresEvent()
    {
        Event::fake();

        $this->mock(ActivityFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetch')->once();
        });

        $activity = $this->hasActivity();
        FetchActivity::dispatch($activity);

        Event::assertDispatched(ActivityFetched::class, function (ActivityFetched $event) use ($activity) {
            return $event->activity->is($activity);
        });
    }

    public function testJobsGetsRescheduledForExpiredTokens()
    {
        $this->markTestIncomplete();

        // Test that when called with an expired token a new job chain to
        // refresh token and retry fetch is dispatched
    }
}
