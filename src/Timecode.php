<?php

namespace M6Web\Component\Timecode;

/**
 * Timecode class representation
 */
class Timecode
{
    /**
     * @var float
     */
    const DEFAULT_FRAMERATE = 25.0;

    /**
     * @var float
     */
    private $framerate;

    /**
     * @var int
     */
    private $hours;

    /**
     * @var int
     */
    private $minutes;

    /**
     * @var int
     */
    private $seconds;

    /**
     * @var int
     */
    private $frames;

    /**
     * @param int   $hours
     * @param int   $minutes
     * @param int   $seconds
     * @param int   $frames
     * @param float $framerate By default, we use 25 which is the PAL or SECAM standard.
     */
    public function __construct(int $hours, int $minutes, int $seconds, int $frames, float $framerate = self::DEFAULT_FRAMERATE)
    {
        $this->framerate = $framerate;
        $this->hours = $hours;
        $this->minutes = $minutes;
        $this->seconds = $seconds;
        $this->frames = $frames;
    }

    /**
     * Create a Timecode object from a string representation.
     *
     * Supported notations are:
     * - "hours:minutes:seconds:frames"
     * - "hours:minutes:seconds.milliseconds"
     *
     * @param string $timecodeStr
     * @param float  $framerate   By default, we use 25 which is the PAL or SECAM standard.
     *
     * @return self
     */
    public static function createFromString(string $timecodeStr, float $framerate = self::DEFAULT_FRAMERATE) : self
    {
        // Support "hours:minutes:seconds:frames" notation
        $matches = [];
        preg_match('@^(?P<hours>^\d{1,3}):(?P<minutes>\d{1,2}):(?P<seconds>\d{1,2}):(?P<frames>\d{1,2})$@', $timecodeStr, $matches);
        if (!empty($matches['hours']) && !empty($matches['minutes']) && !empty($matches['seconds']) && !empty($matches['frames'])) {
            // @codingStandardsIgnoreStart
            return new self(
                $matches['hours'],
                $matches['minutes'],
                $matches['seconds'],
                $matches['frames'],
                $framerate
            );
            // @codingStandardsIgnoreEnd
        }

        // Support "hours:minutes:seconds.milliseconds" notation
        $matches = [];
        preg_match('@^(?P<hours>^\d{1,3}):(?P<minutes>\d{1,2}):(?P<seconds>\d{1,2})\.(?P<ms>\d{1,3})$@', $timecodeStr, $matches);
        if (!empty($matches['hours']) && !empty($matches['minutes']) && !empty($matches['seconds']) && !empty($matches['ms'])) {
            // @codingStandardsIgnoreStart
            return new self(
                $matches['hours'],
                $matches['minutes'],
                $matches['seconds'],
                TimeConverter::millisecondsToFrames($matches['ms'], $framerate),
                $framerate
            );
            // @codingStandardsIgnoreEnd
        }

        throw new \InvalidArgumentException('Unsupported string notation.');
    }

    /**
     * Create a Timecode object representing a given number of frames.
     *
     * @param int   $frames
     * @param float $framerate By default, we use 25 which is the PAL or SECAM standard.
     *
     * @return self
     */
    public static function createFromNumberOfFrames(int $frames, float $framerate = self::DEFAULT_FRAMERATE) : self
    {
        $hours = floor($frames / (60 * 60 * $framerate));
        $frames -= 60 * 60 * $framerate * $hours;

        $minutes = floor($frames / (60 * $framerate));
        $frames -= 60 * $framerate * $minutes;

        $seconds = floor($frames / $framerate);
        $frames -= $framerate * $seconds;

        // @codingStandardsIgnoreStart
        return new self($hours, $minutes, $seconds, $frames, $framerate);
        // @codingStandardsIgnoreEnd
    }

    /**
     * Convert the Timecode to its corresponding number of frames
     *
     * @return int
     */
    public function convertToFrames() : int
    {
        $totalFrames = $this->frames;
        $totalFrames += $this->framerate * $this->seconds;
        $totalFrames += 60 * $this->framerate * $this->minutes;
        $totalFrames += 60 * 60 * $this->framerate * $this->hours;

        return floor($totalFrames);
    }

    /**
     * Convert the Timecode to its corresponding number of seconds
     *
     * @return float
     */
    public function convertToSeconds() : float
    {
        return (float) ($this->convertToFrames() / $this->framerate);
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return sprintf('%02d:%02d:%02d:%02d', $this->hours, $this->minutes, $this->seconds, $this->frames);
    }

    /**
     * @return float
     */
    public function getFramerate() : float
    {
        return $this->framerate;
    }

    /**
     * @return int
     */
    public function getHours() : int
    {
        return $this->hours;
    }

    /**
     * @return int
     */
    public function getMinutes() : int
    {
        return $this->minutes;
    }

    /**
     * @return int
     */
    public function getSeconds() : int
    {
        return $this->seconds;
    }

    /**
     * @return int
     */
    public function getFrames() : int
    {
        return $this->frames;
    }

    /**
     * Return a new instance of Timecode after adding given $timecode to the current one
     *
     * @param Timecode $timecode
     *
     * @return self
     */
    public function add(Timecode $timecode) : self
    {
        return self::createFromNumberOfFrames($this->convertToFrames() + $timecode->convertToFrames(), $this->framerate);
    }

    /**
     * Return a new instance of Timecode after subtracting given $timecode from the current one
     *
     * @param Timecode $timecode
     *
     * @return self
     */
    public function sub(Timecode $timecode) : self
    {
        return self::createFromNumberOfFrames($this->convertToFrames() - $timecode->convertToFrames(), $this->framerate);
    }
}
