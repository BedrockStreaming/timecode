<?php

namespace M6Web\Component\Timecode\Tests\Units;

use atoum\atoum;

/**
 * TimeConverter test class
 */
class TimeConverter extends atoum\test
{
    /**
     * @dataProvider millisecondsToFramesDataProvider
     */
    public function testMillisecondsToFrames(
        $milliseconds = null,
        $framerate = null,
        $expectedFrames = null
    ) {
        $this
            ->given(
                $testedClass = $this->testedClass->getClass()
            )
            ->when(
                $actualFrames = $testedClass::millisecondsToFrames($milliseconds, $framerate)
            )
            ->then
                ->integer($actualFrames)->isIdenticalTo($expectedFrames)
        ;
    }

    protected function millisecondsToFramesDataProvider()
    {
        return [
            '1, 25' => [
                1,
                (float) 25,
                0,
            ],
            '10, 25' => [
                10,
                (float) 25,
                0,
            ],
            '100, 25' => [
                100,
                (float) 25,
                2,
            ],
            '1000, 25' => [
                1000,
                (float) 25,
                25,
            ],
            '9999, 25' => [
                9999,
                (float) 25,
                249,
            ],
            '1, 29.97' => [
                1,
                29.97,
                0,
            ],
            '10, 29.97' => [
                10,
                29.97,
                0,
            ],
            '100, 29.97' => [
                100,
                29.97,
                2,
            ],
            '1000, 29.97' => [
                1000,
                29.97,
                29,
            ],
            '9999, 29.97' => [
                9999,
                29.97,
                299,
            ],
        ];
    }
}
