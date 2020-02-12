<?php

namespace Tests\Feature\Strava;

use App\Strava\Components\ActivityFetcher;
use App\Strava\Components\Redispatcher;
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

        $this->mock(Redispatcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('redispatch')->once();
        });

        $this->mock(ActivityFetcher::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('fetch');
        });

        $activity = $this->hasActivity();
        $activity->athlete->update([
            'expires_at' => now()->subDay(),
        ]);
        FetchActivity::dispatch($activity);

        // TODO: Unit test to make sure Redispatcher actually redispatches the job
    }
}
