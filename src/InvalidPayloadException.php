<?php

namespace Apnpush;

/**
 * Class InvalidPayloadException
 */
final class InvalidPayloadException extends \Exception
{
    public static function reservedKey(): self
    {
        return new static('Key ' . Payload::PAYLOAD_ROOT_KEY . " is reserved and can't be used for custom property.");
    }

    public static function notExistingCustomValue(string $key): self
    {
        return new static("Custom value with key '$key' doesn't exist.");
    }
}
