<?php

namespace Tests\Feature\Strava;

use App\Darksky\Events\ConditionFetched;
use App\Strava\Components\ActivityFetcher;
use App\Strava\Events\ActivityFetched;
use App\Strava\Jobs\FetchActivity;
use App\Strava\Jobs\SendActivity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\Concerns\WithStrava;
use Tests\TestCase;

class SendingTest extends TestCase
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

        $activity = $this->hasActivity('decorated');
        event(new ConditionFetched($activity));

//        Queue::assertPushedOn('strava', SendActivity::class, function (SendActivity $job) use ($activity) {
//            return $job->activity->is($activity);
//        });
    }

//    public function testJobCallsFetcherAndFiresEvent()
//    {
//        Event::fake();
//
//        $this->mock(ActivityFetcher::class, function (MockInterface $mock) {
//            $mock->shouldReceive('fetch')->once();
//        });
//
//        $activity = $this->hasActivity();
//        FetchActivity::dispatch($activity);
//
//        Event::assertDispatched(ActivityFetched::class, function (ActivityFetched $event) use ($activity) {
//            return $event->activity->is($activity);
//        });
//    }

    public function testJobsGetsRescheduledForExpiredTokens()
    {
        $this->markTestIncomplete();

        // Test that when called with an expired token a new job chain to
        // refresh token and retry fetch is dispatched
    }
}
