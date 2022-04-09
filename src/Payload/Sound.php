<?php

namespace Apnpush\Payload;

/**
 * Class Sound
 *
 * @see http://bit.ly/payload-key-reference
 */
class Sound implements \JsonSerializable
{
    public const SOUND_CRITICAL_KEY = 'critical';
    public const SOUND_NAME_KEY = 'name';
    public const SOUND_VOLUME_KEY = 'volume';

    /**
     * Whether the sound should be played as a critical notification or not
     *
     * @var int
     */
    private $critical;

    /**
     * The sound file name.
     *
     * @var string
     */
    private $name;

    /**
     * The sound volume.
     *
     * @var float
     */
    private $volume;

    protected function __construct()
    {
    }

    public static function create(): Sound
    {
        return new self();
    }

    /**
     * Set Sound critical.
     */
    public function setCritical(int $value): Sound
    {
        $this->critical = $value;

        return $this;
    }

    /**
     * Get Sound critical.
     *
     * @return int
     */
    public function getCritical()
    {
        return $this->critical;
    }

    /**
     * Set Sound name.
     */
    public function setName(string $value): Sound
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Get Sound name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Sound volume.
     */
    public function setVolume(float $value): Sound
    {
        $this->volume = $value;

        return $this;
    }

    /**
     * Get Sound volume.
     *
     * @return float
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Convert Sound to JSON.
     */
    public function toJson(): string
    {
        return (json_encode($this, JSON_UNESCAPED_UNICODE)) ?: '';
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array<mixed>
     *
     * @see   http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize()
    {
        $sound = [];

        if (is_int($this->critical)) {
            $sound[self::SOUND_CRITICAL_KEY] = $this->critical;
        }

        if (is_string($this->name)) {
            $sound[self::SOUND_NAME_KEY] = $this->name;
        }

        if (is_float($this->volume)) {
            $sound[self::SOUND_VOLUME_KEY] = $this->volume;
        }

        return $sound;
    }
}
