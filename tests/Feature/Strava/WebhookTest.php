<?php

namespace Tests\Feature\Strava;

use App\Strava\Events\ActivityCreated;
use App\Strava\Events\AthleteDeauthorized;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\Concerns\WithStrava;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use WithFaker,
        WithStrava;

    /**
     * Assert that a newly reported activity:
     * - results in a return status code 200
     * - fires the appropriate event
     * - is persisted to the database
     */
    public function testNewActivitiesAreHandled(): void
    {
        Event::fake([ActivityCreated::class]);

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
     * - does not fire the appropriate event
     */
    public function testNewActivitiesForUnknownAthletesAreHandled()
    {
        Event::fake([ActivityCreated::class]);

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
        Event::fake([ActivityCreated::class]);

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
        Event::fake([ActivityCreated::class]);
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

    /**
     * Assert that de-authorization requests for an athlete:
     * - results in a return status code 200
     * - does fire the appropriate event
     * - revokes the athletes tokens in the database
     */
    public function testDeauthorizationsAreHandled(): void
    {
        Event::fake([AthleteDeauthorized::class]);

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
            'expires_at'    => null,
        ]);
    }

    /**
     * Assert that de-authorization requests for an non-existent athlete:
     * - results in a return status code 200
     * - does not fire the appropriate event
     */
    public function testDeauthorizationsForUnknownAthletesAreHandled(): void
    {
        Event::fake([AthleteDeauthorized::class]);

        $foreignId = $this->faker->numberBetween();

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
