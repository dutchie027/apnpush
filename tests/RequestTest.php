<?php

namespace Apnpush\Tests;

use Apnpush\Notification;
use Apnpush\Payload;
use Apnpush\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetSandboxUri(): void
    {
        $request = new Request($this->createNotification(), $production = false);

        self::assertEquals('https://api.development.push.apple.com/3/device/123', $request->getUri());
    }

    public function testGetProductionUri(): void
    {
        $request = new Request($this->createNotification(), $production = true);

        self::assertEquals('https://api.push.apple.com/3/device/123', $request->getUri());
    }

    public function testGetBody(): void
    {
        $request = new Request($this->createNotification(), $production = false);

        self::assertEquals('{"aps":{}}', $request->getBody());
    }

    public function testGetHeaders(): void
    {
        $request = new Request($this->createNotification(), $production = false);
        $request->addHeader('Connection', 'keep-alive');

        self::assertEquals(['Connection' => 'keep-alive', 'apns-push-type' => 'alert'], $request->getHeaders());
    }

    public function testGetOptions(): void
    {
        $request = new Request($this->createNotification(), $production = false);
        $request->addOption('certificate_secret', 'secret');

        self::assertArrayHasKey('certificate_secret', $request->getOptions());
    }

    private function createNotification(): Notification
    {
        return new Notification(Payload::create(), '123');
    }
}
