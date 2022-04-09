<?php

namespace Apnpush\Tests\Payload;

use Apnpush\Payload\Sound;
use PHPUnit\Framework\TestCase;

class SoundTest extends TestCase
{
    public function testSetCritical()
    {
        $sound = Sound::create()->setCritical(1);

        self::assertEquals(1, $sound->getCritical());
    }

    public function testSetName()
    {
        $sound = Sound::create()->setName('soundName');

        self::assertEquals('soundName', $sound->getName());
    }

    public function testSetVolume()
    {
        $sound = Sound::create()->setVolume(1.0);

        self::assertEquals(1.0, $sound->getVolume());
    }

    public function testSoundConvertingToJson()
    {
        $sound = Sound::create()
            ->setCritical(1)
            ->setName('soundName')
            ->setVolume(1.0);

        self::assertJsonStringEqualsJsonString(
            '{"critical":1,"name":"soundName","volume":1.0}',
            $sound->toJson()
        );
    }
}
