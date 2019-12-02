<?php

namespace Tests\Feature;

use App\Strava\Events\ActivityCreated;
use App\Strava\Events\AthleteDeauthorized;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\Concerns\WithStrava;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use WithStrava,
        WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
        Queue::fake();
    }

    /**
     * Assert that a newly reported activity:
     * - results in a return status code 200
     * - fires the appropriate event
     * - is persisted to the database
     */
    public function testNewActivitiesAreHandled(): void
    {
        $athlete = $this->hasAthlete();

        $response = $this->postJson(route('strava.webhook.invoke'), [
            'object_type'     => 'activity',
            'object_id'       => $activityId = $this->faker->numberBetween(),
            'aspect_type'     => 'create',
            'owner_id'        => $athlete->foreign_id,
            'subscription_id' => 1,
            'event_time'      => time(),
        ]);

        $response->assertStatus(200);

        Event::assertDispatched(ActivityCreated::class, function ($event) use ($activityId) {
            return $event->activity->foreign_id === $activityId;
        });

        $this->assertDatabaseHas('strava_activities', [
            'athlete_id' => $athlete->id,
            'foreign_id' => $activityId,
        ]);
    }

    /**
     * Assert that a newly reported activity for an unknown athlete:
     * - results in a return status code 200
     * - does not fires the appropriate event
     */
    public function testNewActivitiesForUnknownAthletesAreHandled()
    {
        $response = $this->postJson(route('strava.webhook.invoke'), [
            'object_type'     => 'activity',
            'object_id'       => $activityId = $this->faker->numberBetween(),
            'aspect_type'     => 'create',
            'owner_id'        => $athleteId = $this->faker->numberBetween(),
            'subscription_id' => 1,
            'event_time'      => time(),
        ]);

        $response->assertStatus(200);

        Event::assertNotDispatched(ActivityCreated::class);

        $this->assertDatabaseMissing('strava_activities', [
            'athlete_id' => $athleteId,
            'foreign_id' => $activityId,
        ]);
    }

    /**
     * Assert that an already known but as newly created reported activity:
     * - results in a return status code 200
     * - does not fires the appropriate event
     */
    public function testKnownActivitiesAreHandled()
    {
        $activity = $this->hasActivity();

        $response = $this->postJson(route('strava.webhook.invoke'), [
            'object_type'     => 'activity',
            'object_id'       => $activity->foreign_id,
            'aspect_type'     => 'create',
            'owner_id'        => $activity->athlete->foreign_id,
            'subscription_id' => 1,
            'event_time'      => time(),
        ]);

        $response->assertStatus(200);

        Event::assertNotDispatched(ActivityCreated::class);
    }

    /**
     * Assert that an already known but as newly created reported activity by an unknown athlete:
     * - results in a return status code 200
     * - does not fires the appropriate event
     * - are not persisted to the database
     */
    public function testKnownActivitiesForUnknownAthletesAreHandled()
    {
        $activity = $this->hasActivity();

        $response = $this->postJson(route('strava.webhook.invoke'), [
            'object_type'     => 'activity',
            'object_id'       => $activity->foreign_id,
            'aspect_type'     => 'create',
            'owner_id'        => $athleteId = $this->faker->numberBetween(),
            'subscription_id' => 1,
            'event_time'      => time(),
        ]);

        $response->assertStatus(200);

        Event::assertNotDispatched(ActivityCreated::class);

        $this->assertDatabaseMissing('strava_activities', [
            'athlete_id' => $athleteId,
            'foreign_id' => $activity->foreign_id,
        ]);
    }

    public function testDeauthorizationsAreHandled(): void
    {
        $athlete = $this->hasAthlete();

        $response = $this->postJson(route('strava.webhook.invoke'), [
            'object_type'     => 'athlete',
            'object_id'       => $athlete->foreign_id,
            'aspect_type'     => 'delete',
            'owner_id'        => $athlete->foreign_id,
            'subscription_id' => 1,
            'event_time'      => time(),
        ]);

        $response->assertStatus(200);

        Event::assertDispatched(AthleteDeauthorized::class, function ($event) use ($athlete) {
            return $event->athlete->is($athlete);
        });

        $this->assertDatabaseHas('strava_athletes', [
            'id'            => $athlete->id,
            'refresh_token' => null,
            'access_token'  => null,
        ]);
    }

    public function testDeauthorizationsForUnknownAthletesAreHandled(): void
    {
        $foreignId = $this->faker->numberBetween();

        // TODO: Validates that deauthorization requests actually look like this
        $response = $this->postJson(route('strava.webhook.invoke'), [
            'object_type'     => 'athlete',
            'object_id'       => $foreignId,
            'aspect_type'     => 'delete',
            'owner_id'        => $foreignId,
            'subscription_id' => 1,
            'event_time'      => time(),
        ]);

        $response->assertStatus(200);

        Event::assertNotDispatched(AthleteDeauthorized::class);
    }
}
