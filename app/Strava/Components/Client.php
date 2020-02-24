<?php

namespace App\Strava\Components;

use Strava\API\Client as StravaClient;

class Client
{
    /**
     * @var \Strava\API\Client
     */
    protected $strava;

    /**
     * Client constructor.
     *
     * @param \Strava\API\Client $strava
     */
    public function __construct(StravaClient $strava)
    {
        $this->strava = $strava;
    }

    /**
     * Retrieve an activity
     *
     * @link    https://strava.github.io/api/v3/athlete/#get-details,
     * @link    https://strava.github.io/api/v3/athlete/#get-another-details
     * @param int $id
     * @param bool $includeAllEfforts
     * @return array
     * @throws \Strava\API\Exception
     */
    public function getActivity(int $id, bool $includeAllEfforts = false): array
    {
        return $this->strava->getActivity($id, $includeAllEfforts);
    }
}
