<?php

namespace Apnpush\Tests;

use Apnpush\InvalidPayloadException;
use Apnpush\Payload;
use PHPUnit\Framework\TestCase;

class InvalidPayloadExceptionTest extends TestCase
{
    public function testReservedKey()
    {
        $exception = InvalidPayloadException::reservedKey();

        self::assertTrue(is_a($exception, \Exception::class));
        self::assertStringContainsString(Payload::PAYLOAD_ROOT_KEY, $exception->getMessage());
    }

    public function testNotExistingCustomValue()
    {
        $key = 'this is a string';

        $exception = InvalidPayloadException::notExistingCustomValue($key);

        self::assertTrue(is_a($exception, \Exception::class));
        self::assertStringContainsString($key, $exception->getMessage());
    }
}
