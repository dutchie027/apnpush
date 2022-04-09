<?php

namespace Apnpush\AuthProvider;

use Apnpush\AuthProviderInterface;
use Apnpush\Request;

/**
 * Class Token
 *
 * @see http://bit.ly/communicating-with-apns
 */
class Token implements AuthProviderInterface
{
    /**
     * Generated auth token.
     *
     * @var string
     */
    private $token = '';

    /**
     * Path to p8 private key.
     *
     * @var string|null
     */
    private $privateKeyPath;

    /**
     * Private key data.
     *
     * @var string|null
     */
    private $privateKeyContent;

    /**
     * Private key secret.
     *
     * @var string|null
     */
    private $privateKeySecret;

    /**
     * The Key ID obtained from Apple developer account.
     *
     * @var string
     */
    private $keyId;

    /**
     * The Team ID obtained from Apple developer account.
     *
     * @var string
     */
    private $teamId;

    /**
     * The bundle ID for app obtained from Apple developer account.
     *
     * @var string
     */
    private $appBundleId;

    /**
     * This provider accepts the following options:
     *
     * - key_id
     * - team_id
     * - app_bundle_id
     * - private_key_path
     * - private_key_content
     * - private_key_secret
     *
     * @param array<string> $options
     */
    private function __construct(array $options)
    {
        $this->keyId = $options['key_id'];
        $this->teamId = $options['team_id'];
        $this->appBundleId = $options['app_bundle_id'];
        $this->privateKeyPath = $options['private_key_path'] ?? null;
        $this->privateKeyContent = $options['private_key_content'] ?? null;
        $this->privateKeySecret = $options['private_key_secret'] ?? null;
    }

    /**
     * Create Token Auth Provider.
     *
     * @param array<string> $options
     */
    public static function create(array $options): Token
    {
        $token = new self($options);
        $token->token = $token->generate();

        return $token;
    }

    /**
     * Use previously generated token.
     *
     * @param array<string> $options
     */
    public static function useExisting(string $tokenString, array $options): Token
    {
        $token = new self($options);
        $token->token = $tokenString;

        return $token;
    }

    /**
     * Authenticate client.
     */
    public function authenticateClient(Request $request): void
    {
        $request->addHeaders([
            'apns-topic' => $this->generateApnsTopic($request->getHeaders()['apns-push-type']),
            'Authorization' => 'bearer ' . $this->token,
        ]);
    }

    /**
     * Generate a correct apns-topic string
     *
     * @return string
     */
    public function generateApnsTopic($pushType)
    {
        switch ($pushType) {
            case 'voip':
                return $this->appBundleId . '.voip';

            case 'complication':
                return $this->appBundleId . '.complication';

            case 'fileprovider':
                return $this->appBundleId . '.pushkit.fileprovider';

            default:
                return $this->appBundleId;
        }
    }

    /**
     * Get last generated token.
     */
    public function get(): string
    {
        return $this->token;
    }

    /**
     * Generate new token.
     */
    private function generate(): string
    {
        $header = ['alg' => 'ES256', 'kid' => $this->keyId];
        $claims = ['iss' => $this->teamId, 'iat' => time()];

        $header_encoded = $this->base64($header);
        $claims_encoded = $this->base64($claims);

        if ($this->privateKeyContent) {
            $key = $this->privateKeyContent;
        } elseif ($this->privateKeyPath) {
            $key = openssl_pkey_get_private('file://' . $this->privateKeyPath) ?: '';
        } else {
            throw new \InvalidArgumentException('Unable to find private key.');
        }

        $signature = $this->privateKeySecret;
        openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $key, 'sha256');
        $this->token = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);

        return $this->token;
    }

    /**
     * Undocumented function
     *
     * @param array<mixed> $data
     */
    private function base64($data): string
    {
        return rtrim(strtr(base64_encode(json_encode($data) ?: ''), '+/', '-_'), '=');
    }
}
