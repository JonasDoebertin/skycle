<?php

namespace App\Strava\Commands;

use App\Strava\Components\ActivityManager;
use App\Strava\Events\ActivityCreated;
use App\Strava\Exceptions\ActivityAlreadyExistsException;
use App\Strava\Exceptions\UnknownAthleteException;
use App\Strava\Models\Athlete;
use Illuminate\Console\Command;

class InjectActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:inject {athlete : The athletes id on Strava} {activity : The activities id on Strava}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Injects a single activity into the application';

    /**
     * @var \App\Strava\Components\ActivityManager
     */
    protected $manager;

    /**
     * Create a new command instance.
     *
     * @param \App\Strava\Components\ActivityManager $manager
     */
    public function __construct(ActivityManager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $activity = $this->manager->store(
                $this->argument('activity'),
                $this->argument('athlete')
            );
            event(new ActivityCreated($activity));
        } catch (ActivityAlreadyExistsException $e) {
            $this->error('This activity already exists.');

            return 1;
        } catch (UnknownAthleteException $e) {
            $this->error('Unknown athlete.');

            return 1;
        }

        $this->info('Activity injected.');
    }

    protected function athleteExists(): bool
    {
        return Athlete::query()
            ->where('foreign_id', $this->argument('athlete'))
            ->exists();
    }

    protected function activityExists(): bool
    {
        return $this->manager->has($this->argument('activity'));
    }

    protected function injectActivity(): void
    {
        $activity = $this->manager->store(
            $this->argument('activity'),
            $this->argument('athlete')
        );

        event(new ActivityCreated($activity));
    }
}
