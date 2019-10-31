<?php

namespace App\Darksky\Components;

use App\Darksky\Models\Condition;

class ConditionConverter
{
    const EMOJI_NEW_MOON = '🌑';
    const EMOJI_WAXING_CRESCENT = '🌒';
    const EMOJI_FIRST_QUARTER_MOON = '🌓';
    const EMOJI_WAXING_GIBBOUS = '🌔';
    const EMOJI_FULL_MOON = '🌕';
    const EMOJI_WANING_GIBBOUS = '🌖';
    const EMOJI_LAST_QUARTER_MOON = '🌗';
    const EMOJI_WANING_CRESCENT = '🌘';

    const EMOJI_SUN = '☀️';
    const EMOJI_SUN_BEHIND_SMALL_CLOUD = '🌤';
    const EMOJI_SUN_BEHIND_CLOUD = '⛅️';
    const EMOJI_SUN_BEHIND_LARGE_CLOUD = '🌥';
    const EMOJI_CLOUD = '☁️';
    const EMOJI_RAIN = '🌧';
    const EMOJI_SNOW = '🌨';
    const EMOJI_WIND = '💨';

    const EMOJI_SOUTH_ARROW = '↓';
    const EMOJI_SOUTH_WEST_ARROW = '↙';
    const EMOJI_WEST_ARROW = '←';
    const EMOJI_NORTH_WEST_ARROW = '↖';
    const EMOJI_NORTH_ARROW = '↑';
    const EMOJI_NORTH_EAST_ARROW = '↗';
    const EMOJI_EAST_ARROW_ARROW = '→';
    const EMOJI_SOUTH_EAST_ARROW = '↘';

    const EMOJI_DOT = '·';

    const SIMPLE_EMOJIS = [
        'clear-day' => self::EMOJI_SUN,
        'rain' => self::EMOJI_RAIN,
        'snow' => self::EMOJI_SNOW,
        'sleet' => self::EMOJI_SNOW,
        'wind' => self::EMOJI_WIND,
        'cloudy' => self::EMOJI_CLOUD,
        'fog' => self::EMOJI_CLOUD,
    ];

    const MOONPHASE_EMOJIS = [
        self::EMOJI_NEW_MOON,
        self::EMOJI_WAXING_CRESCENT,
        self::EMOJI_FIRST_QUARTER_MOON,
        self::EMOJI_WAXING_GIBBOUS,
        self::EMOJI_FULL_MOON,
        self::EMOJI_WANING_GIBBOUS,
        self::EMOJI_LAST_QUARTER_MOON,
        self::EMOJI_WANING_CRESCENT,
    ];

    const CLOUD_COVERAGE_EMOJIS = [
        self::EMOJI_SUN,
        self::EMOJI_SUN_BEHIND_SMALL_CLOUD,
        self::EMOJI_SUN_BEHIND_CLOUD,
        self::EMOJI_SUN_BEHIND_LARGE_CLOUD,
        self::EMOJI_CLOUD,
    ];

    const WIND_BEARING_EMOJIS = [
        self::EMOJI_SOUTH_ARROW,
        self::EMOJI_SOUTH_WEST_ARROW,
        self::EMOJI_WEST_ARROW,
        self::EMOJI_NORTH_WEST_ARROW,
        self::EMOJI_NORTH_ARROW,
        self::EMOJI_NORTH_EAST_ARROW,
        self::EMOJI_EAST_ARROW_ARROW,
        self::EMOJI_SOUTH_EAST_ARROW,
    ];

    /**
     * Get a single emoji representing a weather condition.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string|null
     */
    public function toEmoji(Condition $condition): ?string
    {
        if ($emoji = $this->getSimpleEmoji($condition)) {
            return $emoji;
        }

        if ($condition->icon === 'clear-night') {
            return $this->getNightEmoji($condition);
        }

        if ($condition->icon === 'partly-cloudy-day') {
            return $this->getCloudyDayEmoji($condition);
        }

        return null;
    }

    /**
     * Get a summary representing a weather condition.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string
     */
    public function toSummary(Condition $condition): string
    {
        $parts = [
            $this->getSummaryPart($condition),
            $this->getTemperaturePart($condition),
            $this->getWindPart($condition),
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Get the foreign summery part of the condition summary.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string|null
     */
    protected function getSummaryPart(Condition $condition): ?string
    {
        return $condition->summary;
    }

    /**
     * Get the temperature part of the condition summary.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string
     */
    protected function getTemperaturePart(Condition $condition): string
    {
        return round($condition->temperature) . '°C';
    }

    /**
     * Get the wind part of the condition summary.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string|null
     */
    protected function getWindPart(Condition $condition): ?string
    {
        if ($condition->wind_speed === null && $condition->wind_gust === null) {
            return null;
        }

        if (
            $condition->wind_gust !== null
            && $condition->wind_gust !== 0
        ) {
            return 'Wind:'
                . ' ' . $this->getWindDirectionEmoji($condition)
                . ' ' . round($condition->wind_speed ?? 0)
                . '-' . round($condition->wind_gust) . ' km/h';
        }

        if (
            $condition->wind_speed !== null
            && $condition->wind_speed !== 0
        ) {
            return 'Wind:'
                . ' ' . $this->getWindDirectionEmoji($condition)
                . ' ' . round($condition->wind_speed ?? 0) . ' km/h';
        }

        return null;
    }

    /**
     * Get a direction emoji corresponding to the current wind bearing.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string
     */
    protected function getWindDirectionEmoji(Condition $condition): string
    {
        if ($condition->wind_bearing === null) {
            return self::EMOJI_DOT;
        }

        return data_get(
            self::WIND_BEARING_EMOJIS,
            min(8, max(0, round($condition->wind_bearing / 45))),
            self::EMOJI_DOT
        );
    }

    /**
     * Get a simple 1:1 emoji mapping.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string|null
     */
    protected function getSimpleEmoji(Condition $condition): ?string
    {
        return data_get(self::SIMPLE_EMOJIS, $condition->icon);
    }

    /**
     * Get a night time emoji corresponding to the current moon phase, if available.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string
     */
    protected function getNightEmoji(Condition $condition): string
    {
        if ($condition->moon_phase === null) {
            return self::EMOJI_WANING_CRESCENT;
        }

        return data_get(
            self::MOONPHASE_EMOJIS,
            round($condition->moon_phase * 8),
            self::EMOJI_WANING_CRESCENT
        );
    }

    /**
     * Get a cloudy day emoji corresponding to the current cloud coverage, if available.
     *
     * @param \App\Darksky\Models\Condition $condition
     * @return string
     */
    protected function getCloudyDayEmoji(Condition $condition): string
    {
        if ($condition->cloud_coverage === null) {
            return self::EMOJI_SUN_BEHIND_CLOUD;
        }

        return data_get(
            self::CLOUD_COVERAGE_EMOJIS,
            round($condition->cloud_coverage * 4),
            self::EMOJI_SUN_BEHIND_CLOUD
        );
    }
}
