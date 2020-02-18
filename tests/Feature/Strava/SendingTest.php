<?php

namespace Tests\Feature\Strava;

use App\Darksky\Events\ConditionFetched;
use App\Strava\Components\ActivityFetcher;
use App\Strava\Components\ActivitySender;
use App\Strava\Components\Redispatcher;
use App\Strava\Events\ActivityCreated;
use App\Strava\Events\ActivityFetched;
use App\Strava\Events\ActivitySent;
use App\Strava\Jobs\FetchActivity;
use App\Strava\Jobs\SendActivity;
use App\Strava\Listeners\MarkActivityAsDecorated;
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

    /**
     * Assert that for an activity with newly fetched conditions:
     * - the appropriate job gets dispatched on the correct queue
     */
    public function testJobGetsDispatched()
    {
        Queue::fake();
        $this->mock(MarkActivityAsDecorated::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->once();
        });

        $activity = $this->hasActivity('decorated');
        event(new ConditionFetched($activity));

        Queue::assertPushedOn('strava', SendActivity::class, function (SendActivity $job) use ($activity) {
            return $job->activity->is($activity);
        });
    }

    /**
     * Assert that after dispatching the job for a newly created activity:
     * - the appropriate event gets fired
     */
    public function testJobCallsSenderAndFiresEvent()
    {
        Event::fake([ActivitySent::class]);

        $this->mock(ActivitySender::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        });

        $activity = $this->hasActivity('decorated');
        SendActivity::dispatch($activity);

        Event::assertDispatched(ActivitySent::class, function (ActivitySent $event) use ($activity) {
            return $event->activity->is($activity);
        });
    }

    /**
     * Assert that when dispatching the job for an activity with newly fetched conditions:
     * - the fetcher doesn't get called
     * - the re-dispatcher gets called
     */
    public function testJobsGetsRescheduledForExpiredTokens()
    {
        $this->mock(Redispatcher::class, function (MockInterface $mock) {
            $mock->shouldReceive('redispatch')->once();
        });
        $this->mock(ActivitySender::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('send');
        });

        $activity = $this->hasActivity('decorated');
        $activity->athlete->update([
            'expires_at' => now()->subDay(),
        ]);
        SendActivity::dispatch($activity);

        // TODO: Unit test to make sure Redispatcher actually redispatches the job
    }
}
