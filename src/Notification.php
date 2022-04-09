<?php

namespace Apnpush;

use DateTime;

/**
 * Class Notification
 */
class Notification
{
    public const PRIORITY_HIGH = 10;
    public const PRIORITY_LOW = 5;

    /**
     * Notification payload.
     *
     * @var Payload
     */
    private $payload;

    /**
     * Token of device.
     *
     * @var string
     */
    private $deviceToken;

    /**
     * A canonical UUID that identifies the notification.
     *
     * @var string
     */
    private $id;

    /**
     * This value identifies the date when the notification is no longer valid and can be discarded.
     *
     * @var \DateTime
     */
    private $expirationAt;

    /**
     * The priority of the notification.
     *
     * @var int
     */
    private $priority;

    /**
     * Id for the coalescing of similar notifications.
     *
     * @var string
     */
    private $collapseId;

    /**
     * Notification constructor.
     */
    public function __construct(Payload $payload, string $deviceToken)
    {
        $this->payload = $payload;
        $this->deviceToken = $deviceToken;
    }

    /**
     * Get device token.
     */
    public function getDeviceToken(): string
    {
        return $this->deviceToken;
    }

    /**
     * Get payload.
     */
    public function getPayload(): Payload
    {
        return $this->payload;
    }

    /**
     * Get notification id.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set notification id.
     */
    public function setId(string $id): Notification
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get expiration DateTime.
     */
    public function getExpirationAt(): ?\DateTime
    {
        return $this->expirationAt;
    }

    /**
     * Set expiration DateTime.
     */
    public function setExpirationAt(\DateTime $expirationAt): Notification
    {
        $this->expirationAt = clone $expirationAt;

        return $this;
    }

    /**
     * Get notification priority.
     *
     * @return int|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set high priority.
     */
    public function setHighPriority(): Notification
    {
        $this->priority = self::PRIORITY_HIGH;

        return $this;
    }

    /**
     * Set low priority.
     */
    public function setLowPriority(): Notification
    {
        $this->priority = self::PRIORITY_LOW;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCollapseId()
    {
        return $this->collapseId;
    }

    public function setCollapseId(string $collapseId): Notification
    {
        $this->collapseId = $collapseId;

        return $this;
    }
}
