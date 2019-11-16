<?php

namespace App\Strava\Concerns;

trait ParsesTimezones
{
    /**
     * Extract a usable timezone out of a Strava time zone string.
     *
     * @param string $timezone
     * @return string
     */
    protected function parseTimezone(string $timezone): string
    {
        preg_match(
            '/\([^\)]*\)\s(.*)/i',
            $timezone,
            $matches
        );

        return data_get($matches, 1, 'UTC');
    }
}
