<?php

namespace Apnpush\Tests;

use Apnpush\Notification;
use Apnpush\Payload;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    public function testGetDeviceToken(): void
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        self::assertSame('deviceTokenString', $message->getDeviceToken());
    }

    public function testGetPayload(): void
    {
        $payload = Payload::create();

        $message = new Notification($payload, 'deviceTokenString');

        self::assertSame($payload, $message->getPayload());
    }

    public function testId(): void
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $id = 'this is a string';
        $message->setId($id);

        self::assertSame($id, $message->getId());
    }

    public function testExpirationAt(): void
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $expire = (new \DateTime())->modify('+1 day');
        $expected = $expire->getTimestamp();

        $message->setExpirationAt($expire);

        self::assertSame($expected, $message->getExpirationAt()->getTimestamp()); /** @phpstan-ignore-line */
    }

    public function testPriority(): void
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $message->setHighPriority();
        self::assertSame(Notification::PRIORITY_HIGH, $message->getPriority());

        $message->setLowPriority();
        self::assertSame(Notification::PRIORITY_LOW, $message->getPriority());
    }

    public function testCollapseId(): void
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $id = 'this is a string';
        $message->setCollapseId($id);

        self::assertSame($id, $message->getCollapseId());
    }
}
