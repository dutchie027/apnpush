<?php

namespace Apnpush\AuthProvider;

use Apnpush\AuthProviderInterface;
use Apnpush\Request;

/**
 * Class Certificate
 *
 * @see     http://bit.ly/communicating-with-apns
 */
class Certificate implements AuthProviderInterface
{
    /**
     * Path to certificate.
     *
     * @var string
     */
    private $certificatePath;

    /**
     * Certificate secret.
     *
     * @var string
     */
    private $certificateSecret;

    /**
     * The bundle ID for app obtained from Apple developer account.
     *
     * @var string
     */
    private $appBundleId;

    /**
     * This provider accepts the following options:
     *
     * - certificate_path
     * - certificate_secret
     *
     * @param array<string> $options
     */
    private function __construct(array $options)
    {
        $this->certificatePath = $options['certificate_path'] ;
        $this->certificateSecret = $options['certificate_secret'];
        $this->appBundleId = $options['app_bundle_id'];
    }

    /**
     * Create Certificate Auth provider.
     *
     * @param array<string> $options
     */
    public static function create(array $options): Certificate
    {
        return new self($options);
    }

    /**
     * Authenticate client.
     */
    public function authenticateClient(Request $request): void
    {
        $request->addOptions(
            [
                CURLOPT_SSLCERT => $this->certificatePath,
                CURLOPT_SSLCERTPASSWD => $this->certificateSecret,
                CURLOPT_SSL_VERIFYPEER => true,
            ]
        );
        $request->addHeaders([
            'apns-topic' => $this->generateApnsTopic($request->getHeaders()['apns-push-type']),
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
}
