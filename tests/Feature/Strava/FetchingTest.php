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

    /**
     * Assert that for a newly created activity:
     * - the appropriate job gets dispatched on the correct queue
     */
    public function testJobGetsDispatched()
    {
        Queue::fake();

        $activity = $this->hasActivity();
        event(new ActivityCreated($activity));

        Queue::assertPushedOn('strava', FetchActivity::class, function (FetchActivity $job) use ($activity) {
            return $job->activity->is($activity);
        });
    }

    /**
     * Assert that after dispatching the job for a newly created activity:
     * - the appropriate event gets fired
     */
    public function testJobCallsFetcherAndFiresEvent()
    {
        Event::fake([ActivityFetched::class]);

        $this->mock(ActivityFetcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetch')->once();
        });

        $activity = $this->hasActivity();
        event(new ActivityCreated($activity));

        Event::assertDispatched(ActivityFetched::class, function (ActivityFetched $event) use ($activity) {
            return $event->activity->is($activity);
        });

        // TODO: Unit test fetcher
    }

    /**
     * Assert that when dispatching the job for a newly created activity with an expired token:
     * - the fetcher doesn't get called
     * - the re-dispatcher gets called
     */
    public function testJobsGetsRescheduledForExpiredTokens()
    {
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
