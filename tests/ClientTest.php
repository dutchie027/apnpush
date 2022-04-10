<?php

namespace Apnpush\Tests;

use Apnpush\AuthProvider\Token;
use Apnpush\Client;
use Apnpush\Notification;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testAmountOfAddedMessages(): void
    {
        $notification = $this->createMock(Notification::class);
        $notification2 = $this->createMock(Notification::class);
        $notification3 = $this->createMock(Notification::class);
        $notification4 = $this->createMock(Notification::class);

        $client = $this->getClient();

        $client->addNotification($notification);
        $client->addNotifications([$notification, $notification2, $notification3]);
        $client->addNotification($notification4);

        self::assertCount(4, $client->getNotifications());
    }

    private function getClient(): Client
    {
        $authProvider = $this->createMock(Token::class);

        return new Client($authProvider, $production = false);
    }

    public function testPrepareHandle(): void
    {
        $client = $this->getClient();

        $method = (new \ReflectionClass($client))->getMethod('prepareHandle');
        $method->setAccessible(true);

        $notification = $this->createMock(Notification::class);

        $ch = $method->invoke($client, $notification);

        if (PHP_MAJOR_VERSION === 7) {
            self::assertIsResource($ch);
        } elseif (PHP_MAJOR_VERSION === 8) {
            self::assertIsObject($ch);
        }
        self::assertTrue(curl_errno($ch) === 0);
    }
}
