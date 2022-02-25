# m6web/timecode

[![Build Status](https://github.com/BedrockStreaming/timecode/actions/workflows/ci.yml/badge.svg)](https://github.com/BedrockStreaming/timecode/actions/workflows/ci.yml) [![Total Downloads](https://poser.pugx.org/m6web/timecode/downloads.svg)](https://packagist.org/packages/m6web/timecode)

Tiny PHP library to deal with [(SMPTE) timecode](https://en.wikipedia.org/wiki/SMPTE_timecode) through Timecode object representation.

## Installation

```shell
composer require m6web/timecode
```

## Usage

```php
<?php

require __DIR__.'/vendor/autoload.php';

use M6Web\Component\Timecode\Timecode;

// Let's say we want to create a timecode corresponding to 9hours, 2minutes, 0seconds and 3frames with a framerate of 25 (which is the default framerate btw)

// We could either create it like any other PHP objects
$timecode = new Timecode(9, 2, 0, 3, 25); // hours, minutes, seconds, frames, framerate

// Or simply using its string representation
$timecode = Timecode::createFromString('09:02:00:03', 25);

// Or even using its total number of frames
$timecode = Timecode::createFromNumberOfFrames(813003, 25);


// Now let's say we want to subtract 5hours from our timecode
$resultTimecode = $timecode->sub(new Timecode(5, 0, 0, 0));

$resultTimecode->getHours(); // 4
$resultTimecode->getMinutes(); // 2
$resultTimecode->getSeconds(); // 0
$resultTimecode->getFrames(); // 3
echo $resultTimecode; // '04:02:00:03'
```
