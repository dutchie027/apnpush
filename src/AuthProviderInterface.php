<?php

namespace Apnpush;

/**
 * Interface AuthProviderInterface
 */
interface AuthProviderInterface
{
    /**
     * Authenticate client
     */
    public function authenticateClient(Request $request): void;
}
