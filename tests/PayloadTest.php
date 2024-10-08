<?php

namespace Apnpush\Tests;

use Apnpush\InvalidPayloadException;
use Apnpush\Payload;
use Apnpush\Payload\Alert;
use Apnpush\Payload\Sound;
use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function testSetAlert(): void
    {
        $alert = Alert::create();
        $payload = Payload::create()->setAlert($alert);

        self::assertSame($alert, $payload->getAlert());
    }

    public function testSetBadge(): void
    {
        $payload = Payload::create()->setBadge(3);

        self::assertEquals(3, $payload->getBadge());
    }

    public function testSetSound(): void
    {
        $sound = Sound::create();
        $payload = Payload::create()->setSound($sound);

        self::assertSame($sound, $payload->getSound());
    }

    public function testSetCategory(): void
    {
        $payload = Payload::create()->setCategory('categoryString');

        self::assertEquals('categoryString', $payload->getCategory());
    }

    public function testSetThreadId(): void
    {
        $payload = Payload::create()->setThreadId('thread-id');

        self::assertEquals('thread-id', $payload->getThreadId());
    }

    public function testSetContentAvailability(): void
    {
        $payload = Payload::create()->setContentAvailability(true);

        self::assertTrue($payload->isContentAvailable());
    }

    public function testSetMutableContent(): void
    {
        $payload = Payload::create()->setMutableContent(true);

        self::assertTrue($payload->hasMutableContent());
    }

    public function testSetCustomValue(): void
    {
        $payload = Payload::create()->setCustomValue('key', 'value');

        self::assertEquals('value', $payload->getCustomValue('key'));
    }

    public function testSetCustomValueToRootKey(): void
    {
        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage("Key aps is reserved and can't be used for custom property.");

        Payload::create()->setCustomValue('aps', 'value');
    }

    public function testGetCustomValueOfNotExistingKey(): void
    {
        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage("Custom value with key 'notExistingKey' doesn't exist.");

        Payload::create()
            ->setCustomValue('something', 'value')
            ->getCustomValue('notExistingKey');
    }

    public function testSetPushType(): void
    {
        $payload = Payload::create()->setPushType('pushType');

        self::assertEquals('pushType', $payload->getPushType());
    }

    public function testConvertToJSon(): void
    {
        $alert = Alert::create()->setTitle('title');
        $sound = Sound::create()->setName('soundName')->setCritical(1)->setVolume(1.0);
        $payload = Payload::create()
            ->setAlert($alert)
            ->setBadge(1)
            ->setSound($sound)
            ->setCategory('category')
            ->setThreadId('tread-id')
            ->setContentAvailability(true)
            ->setMutableContent(true)
            ->setCustomValue('key', 'value');

        self::assertJsonStringEqualsJsonString(
            '{"aps": {"alert": {"title": "title"}, "badge": 1, "sound": {"critical": 1, "name": "soundName", "volume": 1.0}, "category": "category", ' .
            ' "thread-id": "tread-id", "mutable-content": 1, "content-available": 1}, "key": "value"}',
            $payload->toJson()
        );
    }

    public function testSetCustomArrayType(): void
    {
        $alert = Alert::create()->setTitle('title');
        $payload = Payload::create()
            ->setAlert($alert)
            ->setCustomValue('array', [1, 2, 3]);

        self::assertEquals(gettype(json_decode($payload->toJson())->array), 'array');
    }

    public function testJsonSizeException(): void
    {
        $this->expectException(InvalidPayloadException::class);

        $alert = Alert::create()->setTitle(
            str_repeat('title that is going to be waaaaaaay to big and is going to throw an error to avoid having a request failing', 40)
        );
        $sound = Sound::create()->setName('soundName')->setCritical(1)->setVolume(1.0);
        $payload = Payload::create()
            ->setAlert($alert)
            ->setBadge(1)
            ->setSound($sound)
            ->setCategory('category')
            ->setThreadId('tread-id')
            ->setContentAvailability(true)
            ->setMutableContent(true)
            ->setCustomValue('key', 'value');

        $payload->toJson();
    }
}
