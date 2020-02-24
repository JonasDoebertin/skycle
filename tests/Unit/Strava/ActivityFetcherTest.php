<?php

namespace Tests\Unit\Strava;

use App\Strava\Components\ActivityFetcher;
use App\Strava\Components\Client;
use App\Strava\Models\Activity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Tests\Concerns\WithStrava;
use Tests\TestCase;

class ActivityFetcherTest extends TestCase
{
    use WithFaker,
        WithStrava;

    public function testItCallsStravaApi()
    {
        $activity = $this->hasActivity('reported');
        $this->mockClient($activity);

        app(ActivityFetcher::class)->fetch($activity);
    }

    /**
     * Mock the Strava api client wrapper.
     *
     * @param \App\Strava\Models\Activity $activity
     * @return \Mockery\MockInterface
     */
    protected function mockClient(Activity $activity): MockInterface
    {
        $mock = $this->mock(Client::class, function (MockInterface $mock) use ($activity) {
            $mock->shouldReceive('getActivity')
                ->with($activity->foreign_id, false)
                ->once()
                ->andReturn($this->jsonStub('strava/activity.json', true));
        });

        $this->app->bind(Client::class, function () use ($mock) {
            return $mock;
        });

        return $mock;
    }

    public function testAlreadyFetchedActivitiesAreSkipped()
    {
        $activity = $this->hasActivity('fetched');
        $this->mock(ActivityFetcher::class, function (MockInterface $mock) {
            $mock->makePartial()->shouldAllowMockingProtectedMethods()
                ->shouldNotReceive('getStravaClient');
        });

        app(ActivityFetcher::class)->fetch($activity);
    }

    public function testFetchedDetailsGetPersisted()
    {
        $activity = $this->hasActivity('reported');
        $this->mockClient($activity);

        app(ActivityFetcher::class)->fetch($activity);

        $this->assertDatabaseHas('strava_activities', [
            'id'              => $activity->id,
            'name'            => 'Happy Friday',
            'description'     => '',
            'timezone'        => 'America/Los_Angeles',
            'start_time'      => Carbon::create(2018, 2, 16, 14, 52, 54, 'UTC'),
            'start_longitude' => -122.26,
            'start_latitude'  => 37.83,
            'end_time'        => Carbon::create(2018, 2, 16, 16, 06, 24, 'UTC'),
            'end_longitude'   => -122.26,
            'end_latitude'    => 37.83,
        ]);
    }

    public function testNextStateIsApplied()
    {
        $activity = $this->hasActivity('reported');
        $this->mockClient($activity);

        app(ActivityFetcher::class)->fetch($activity);

        $this->assertDatabaseHas('strava_activities', [
            'id'    => $activity->id,
            'state' => 'fetched',
        ]);
    }
}
