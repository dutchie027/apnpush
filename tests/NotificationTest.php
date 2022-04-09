<?php

namespace Apnpush\Tests;

use Apnpush\Notification;
use Apnpush\Payload;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    public function testGetDeviceToken()
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        self::assertSame('deviceTokenString', $message->getDeviceToken());
    }

    public function testGetPayload()
    {
        $payload = Payload::create();

        $message = new Notification($payload, 'deviceTokenString');

        self::assertSame($payload, $message->getPayload());
    }

    public function testId()
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $id = 'this is a string';
        $message->setId($id);

        self::assertSame($id, $message->getId());
    }

    public function testExpirationAt()
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $expire = (new \DateTime())->modify('+1 day');
        $expected = $expire->getTimestamp();

        $message->setExpirationAt($expire);

        // Change object to see unwanted behaviour with object references
        $expire->modify('+2 days');

        self::assertSame($expected, $message->getExpirationAt()->getTimestamp());
    }

    public function testPriority()
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $message->setHighPriority();
        self::assertSame(Notification::PRIORITY_HIGH, $message->getPriority());

        $message->setLowPriority();
        self::assertSame(Notification::PRIORITY_LOW, $message->getPriority());
    }

    public function testCollapseId()
    {
        $message = new Notification(Payload::create(), 'deviceTokenString');

        $id = 'this is a string';
        $message->setCollapseId($id);

        self::assertSame($id, $message->getCollapseId());
    }
}
