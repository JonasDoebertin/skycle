<?php

namespace App\Strava\Commands;

use App\Strava\Components\ActivityManager;
use App\Strava\Models\Activity;
use App\Strava\States\Activity\Discarded;
use App\Strava\States\Activity\Sent;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class PruneActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes sensitive data from historic activities';

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
        Activity::query()
            ->whereState('state', [Sent::class, Discarded::class])
            ->where('state_updated_at', '<=', now()->subWeek())
            ->chunk(100, function (Collection $activities) {
                foreach ($activities as $activity) {
                    $this->manager->prune($activity);
                }
            });
    }
}
