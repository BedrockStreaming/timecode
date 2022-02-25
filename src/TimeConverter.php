<?php

namespace M6Web\Component\Timecode;

/**
 * Time converter helper
 */
class TimeConverter
{
    /**
     * Convert given milliseconds to its corresponding number of frames.
     */
    public static function millisecondsToFrames(int $milliseconds, float $framerate): int
    {
        return floor($milliseconds / (1000 / $framerate));
    }
}
