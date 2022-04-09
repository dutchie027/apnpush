<?php

namespace Apnpush;

/**
 * Interface ApnsResponseInterface
 */
interface ApnsResponseInterface
{
    /**
     * Get APNs Id
     */
    public function getApnsId(): string;

    /**
     * Get status code.
     */
    public function getStatusCode(): int;

    /**
     * Get reason phrase.
     */
    public function getReasonPhrase(): string;

    /**
     * Get error reason.
     */
    public function getErrorReason(): string;

    /**
     * Get error description.
     */
    public function getErrorDescription(): string;

    /**
     * Get timestamp for a status 410 error
     */
    public function get410Timestamp(): string;
}
