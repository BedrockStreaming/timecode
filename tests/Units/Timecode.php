<?php

namespace M6Web\Component\Timecode\Tests\Units;

use atoum;

/**
 * Timecode test class
 */
class Timecode extends atoum\test
{
    /**
     * @dataProvider createFromStringDataProvider
     */
    public function testCreateFromString(
        $timecodeStr = null,
        $framerate = null,
        $expectedHours = null,
        $expectedMinutes = null,
        $expectedSeconds = null,
        $expectedFrames = null,
        $expectedFramerate = null
    ) {
        $this
            ->given(
                $testedClass = $this->testedClass->getClass()
            )
        ;

        // Handle exceptions
        if (is_null($expectedHours) || is_null($expectedMinutes) || is_null($expectedSeconds) || is_null($expectedFrames) || is_null($expectedFramerate)) {
            $this->exception(function() use ($testedClass, $timecodeStr, $framerate) {
                is_null($framerate) ? $testedClass::createFromString($timecodeStr) : $testedClass::createFromString($timecodeStr, $framerate);
            })
                ->isInstanceOf('InvalidArgumentException')
                ->hasMessage('Unsupported string notation.')
            ;

            return;
        }

        $this
            ->when(
                /** @var \M6Web\Component\Timecode\Timecode $timecode */
                $timecode = is_null($framerate) ? $testedClass::createFromString($timecodeStr) : $testedClass::createFromString($timecodeStr, $framerate)
            )
            ->then
                ->integer($timecode->getHours())->isIdenticalTo($expectedHours)
                ->integer($timecode->getMinutes())->isIdenticalTo($expectedMinutes)
                ->integer($timecode->getSeconds())->isIdenticalTo($expectedSeconds)
                ->integer($timecode->getFrames())->isIdenticalTo($expectedFrames)
                ->float($timecode->getFramerate())->isIdenticalTo($expectedFramerate)
        ;
    }

    protected function createFromStringDataProvider()
    {
        return [
            'unsupported notation' => [
                '12345.345.6543:12',
                null,
                null,
                null,
                null,
                null,
                null,
            ],
            'hours:minutes:seconds:frames default framerate' => [
                '09:02:00:03',
                null,
                9,
                2,
                0,
                3,
                (float) 25,
            ],
            'hours:minutes:seconds:frames int framerate' => [
                '99:59:59:59',
                60,
                99,
                59,
                59,
                59,
                (float) 60,
            ],
            'hours:minutes:seconds:frames float framerate' => [
                '99:59:59:28',
                29.97,
                99,
                59,
                59,
                28,
                (float) 29.97,
            ],
            'hours:minutes:seconds.milliseconds default framerate' => [
                '09:02:00.120',
                null,
                9,
                2,
                0,
                3,
                (float) 25,
            ],
            'hours:minutes:seconds.milliseconds int framerate' => [
                '99:59:59.999',
                60,
                99,
                59,
                59,
                59,
                (float) 60,
            ],
            'hours:minutes:seconds.milliseconds float framerate' => [
                '99:59:59.940',
                29.97,
                99,
                59,
                59,
                28,
                (float) 29.97,
            ],
        ];
    }

    /**
     * @dataProvider stringConversionDataProvider
     */
    public function testStringConversion(
        $hours = null,
        $minutes = null,
        $seconds = null,
        $frames = null,
        $framerate = null,
        $expectedString = null
    ) {
        $this
            ->given(
                is_null($framerate) ? $this->newTestedInstance($hours, $minutes, $seconds, $frames) : $this->newTestedInstance($hours, $minutes, $seconds, $frames, $framerate)
            )
            ->when(
                $timecodeStr = (string) $this->testedInstance
            )
            ->then
                ->string($timecodeStr)->isIdenticalTo($expectedString)
        ;
    }

    protected function stringConversionDataProvider()
    {
        return [
            'default framerate' => [
                9,
                2,
                0,
                3,
                null,
                '09:02:00:03',
            ],
            'int framerate' => [
                99,
                59,
                59,
                59,
                60,
                '99:59:59:59',
            ],
            'float framerate' => [
                99,
                59,
                59,
                28,
                29.97,
                '99:59:59:28',
            ],
        ];
    }

    /**
     * @dataProvider createFromNumberOfFramesDataProvider
     */
    public function testCreateFromNumberOfFrames(
        $frames = null,
        $framerate = null,
        $expectedHours = null,
        $expectedMinutes = null,
        $expectedSeconds = null,
        $expectedFrames = null,
        $expectedFramerate = null
    ) {
        $this
            ->given(
                $testedClass = $this->testedClass->getClass()
            )
            ->when(
                /** @var \M6Web\Component\Timecode\Timecode $timecode */
                $timecode = is_null($framerate) ? $testedClass::createFromNumberOfFrames($frames) : $testedClass::createFromNumberOfFrames($frames, $framerate)
            )
            ->then
                ->integer($timecode->getHours())->isIdenticalTo($expectedHours)
                ->integer($timecode->getMinutes())->isIdenticalTo($expectedMinutes)
                ->integer($timecode->getSeconds())->isIdenticalTo($expectedSeconds)
                ->integer($timecode->getFrames())->isIdenticalTo($expectedFrames)
                ->float($timecode->getFramerate())->isIdenticalTo($expectedFramerate)
        ;
    }

    protected function createFromNumberOfFramesDataProvider()
    {
        return [
            'default framerate' => [
                813003,
                null,
                9,
                2,
                0,
                3,
                (float) 25,
            ],
            'int framerate' => [
                21599999,
                60,
                99,
                59,
                59,
                59,
                (float) 60,
            ],
            'float framerate' => [
                10789199,
                29.97,
                99,
                59,
                59,
                28,
                (float) 29.97,
            ],
        ];
    }

    /**
     * @dataProvider convertToFramesDataProvider
     */
    public function testConvertToFrames(
        $hours = null,
        $minutes = null,
        $seconds = null,
        $frames = null,
        $framerate = null,
        $expectedFrames = null
    ) {
        $this
            ->given(
                is_null($framerate) ? $this->newTestedInstance($hours, $minutes, $seconds, $frames) : $this->newTestedInstance($hours, $minutes, $seconds, $frames, $framerate)
            )
            ->when(
                $numberOfFrames = $this->testedInstance->convertToFrames()
            )
            ->then
                ->integer($numberOfFrames)->isIdenticalTo($expectedFrames)
        ;
    }

    protected function convertToFramesDataProvider()
    {
        return [
            'default framerate' => [
                9,
                2,
                0,
                3,
                null,
                813003,
            ],
            'int framerate' => [
                99,
                59,
                59,
                59,
                60,
                21599999,
            ],
            'float framerate' => [
                99,
                59,
                59,
                28,
                29.97,
                10789198,
            ],
        ];
    }

    /**
     * @dataProvider convertToSecondsDataProvider
     */
    public function testConvertToSeconds(
        $hours = null,
        $minutes = null,
        $seconds = null,
        $frames = null,
        $framerate = null,
        $expectedSeconds = null
    ) {
        $this
            ->given(
                is_null($framerate) ? $this->newTestedInstance($hours, $minutes, $seconds, $frames) : $this->newTestedInstance($hours, $minutes, $seconds, $frames, $framerate)
            )
            ->when(
                $numberOfSeconds = $this->testedInstance->convertToSeconds()
            )
            ->then
                ->float($numberOfSeconds)->isIdenticalTo($expectedSeconds)
        ;
    }

    protected function convertToSecondsDataProvider()
    {
        return [
            'only seconds' => [
                0,
                0,
                30,
                0,
                null,
                (float) 30,
            ],
            'only seconds with a different framerate should give the same result' => [
                0,
                0,
                30,
                0,
                25.5,
                (float) 30,
            ],
            'hours, minutes, seconds' => [
                1,
                15,
                30,
                0,
                null,
                (float) 4530,
            ],
            'only frames' => [
                0,
                0,
                0,
                1,
                25,
                (float) 0.04,
            ],
            'hours, minutes, seconds, frames with 25 framerate' => [
                1,
                15,
                30,
                1,
                25,
                (float) 4530.04,
            ],
        ];
    }

    /**
     * @dataProvider addDataProvider
     */
    public function testAdd(
        $timecode1 = null,
        $timecode2 = null,
        $expectedTimecode = null
    ) {
        $this
            ->when(
                $resultTimecode = $timecode1->add($timecode2)
            )
            ->then
                ->object($resultTimecode)
                    ->isInstanceOfTestedClass()
                    ->isEqualTo($expectedTimecode)
        ;
    }

    protected function addDataProvider()
    {
        return [
            'adding using default framerate' => [
                $this->newTestedInstance(9, 2, 0, 3),
                $this->newTestedInstance(50, 50, 50, 20),
                $this->newTestedInstance(59, 52, 50, 23),
            ],
            'adding more than the frames limit should increase seconds' => [
                $this->newTestedInstance(0, 0, 0, 50, 60),
                $this->newTestedInstance(0, 0, 0, 10, 60),
                $this->newTestedInstance(0, 0, 1, 0, 60),
            ],
            'adding more than the seconds limit should increase minutes' => [
                $this->newTestedInstance(0, 0, 50, 0, 60),
                $this->newTestedInstance(0, 0, 10, 0, 60),
                $this->newTestedInstance(0, 1, 0, 0, 60),
            ],
            'adding more than the minutes limit should increase hours' => [
                $this->newTestedInstance(0, 50, 0, 0, 60),
                $this->newTestedInstance(0, 10, 0, 0, 60),
                $this->newTestedInstance(1, 0, 0, 0, 60),
            ],
        ];
    }

    /**
     * @dataProvider subDataProvider
     */
    public function testSub(
        $timecode1 = null,
        $timecode2 = null,
        $expectedTimecode = null
    ) {
        $this
            ->when(
                $resultTimecode = $timecode1->sub($timecode2)
            )
            ->then
                ->object($resultTimecode)
                    ->isInstanceOfTestedClass()
                    ->isEqualTo($expectedTimecode)
        ;
    }

    protected function subDataProvider()
    {
        return [
            'subtracting using default framerate' => [
                $this->newTestedInstance(50, 50, 50, 20),
                $this->newTestedInstance(9, 2, 0, 3),
                $this->newTestedInstance(41, 48, 50, 17),
            ],
            'subtracting less than the frames limit should decrease seconds' => [
                $this->newTestedInstance(0, 0, 1, 0, 60),
                $this->newTestedInstance(0, 0, 0, 1, 60),
                $this->newTestedInstance(0, 0, 0, 59, 60),
            ],
            'subtracting less than the seconds limit should decrease minutes' => [
                $this->newTestedInstance(0, 1, 0, 0, 60),
                $this->newTestedInstance(0, 0, 1, 0, 60),
                $this->newTestedInstance(0, 0, 59, 0, 60),
            ],
            'subtracting less than the minutes limit should decrease hours' => [
                $this->newTestedInstance(1, 0, 0, 0, 60),
                $this->newTestedInstance(0, 1, 0, 0, 60),
                $this->newTestedInstance(0, 59, 0, 0, 60),
            ],
        ];
    }
}
