<?php

namespace Apnpush\Tests;

use Apnpush\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testGetStatusCode()
    {
        $response = new Response(200, 'headers', 'body');

        self::assertEquals(200, $response->getStatusCode());
    }

    public function testGetApnsId()
    {
        $response = new Response(200, 'apns-id: 123', 'body');

        self::assertEquals('123', $response->getApnsId());
    }

    public function testGetDeviceToken()
    {
        $response = new Response(200, 'headers', 'body', 'token');

        self::assertEquals('token', $response->getDeviceToken());
    }

    public function testGetReasonPhrase()
    {
        $response = new Response(200, 'headers', 'body');

        self::assertEquals('Success.', $response->getReasonPhrase());
    }

    public function testGetErrorReason()
    {
        $response = new Response(400, 'headers', '{"reason": "BadCollapseId"}');

        self::assertEquals('BadCollapseId', $response->getErrorReason());
    }

    public function testGetErrorDescription()
    {
        $response = new Response(400, 'headers', '{"reason": "BadCollapseId"}');

        self::assertEquals(
            'The collapse identifier exceeds the maximum allowed size',
            $response->getErrorDescription()
        );
    }

    public function testGetErrorDescriptionForUnknownReason()
    {
        $response = new Response(400, 'headers', '{"reason": "UnknownReason"}');

        self::assertEquals('', $response->getErrorDescription());
    }

    public function testGetError410Timestamp()
    {
        $response = new Response(410, 'headers', '{"reason": "Unregistered", "timestamp": 1514808000}');

        self::assertEquals('1514808000', $response->get410Timestamp());
    }

    public function testGetError410TimestampFailure()
    {
        $response = new Response(400, 'headers', '{"reason": "BadCollapseId"}');

        self::assertEquals('', $response->get410Timestamp());
    }
}
