<?php

namespace Apnpush;

/**
 * Class Request
 *
 * @see http://bit.ly/communicating-with-apns
 */
class Request
{
    public const APNS_DEVELOPMENT_SERVER = 'https://api.development.push.apple.com';
    public const APNS_PRODUCTION_SERVER = 'https://api.push.apple.com';
    public const APNS_PORT = 443;
    public const APNS_PATH_SCHEMA = '/3/device/{token}';

    public const HEADER_APNS_ID = 'apns-id';
    public const HEADER_APNS_EXPIRATION = 'apns-expiration';
    public const HEADER_APNS_PRIORITY = 'apns-priority';
    public const HEADER_APNS_TOPIC = 'apns-topic';
    public const HEADER_APNS_COLLAPSE_ID = 'apns-collapse-id';
    public const HEADER_APNS_PUSH_TYPE = 'apns-push-type';

    /**
     * Request headers.
     *
     * @var array<mixed>
     */
    private $headers = [];

    /**
     * Request uri.
     *
     * @var string
     */
    private $uri;

    /**
     * Request body.
     *
     * @var string
     */
    private $body;

    /**
     * Curl options.
     *
     * @var array<mixed>
     */
    private $options;

    public function __construct(Notification $notification, bool $isProductionEnv)
    {
        $this->uri = $isProductionEnv ? $this->getProductionUrl($notification) : $this->getSandboxUrl($notification);
        $this->body = $notification->getPayload()->toJson();

        if (!defined('CURL_HTTP_VERSION_2')) {
            define('CURL_HTTP_VERSION_2', 3);
        }

        $this->options = [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2,
            CURLOPT_URL => $this->uri,
            CURLOPT_PORT => self::APNS_PORT,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $notification->getPayload()->toJson(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HEADER => true,
        ];

        $this->prepareApnsHeaders($notification);
    }

    /**
     * Add request header.
     */
    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * Add request headers.
     *
     * @param array<mixed> $headers
     */
    public function addHeaders(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Get request headers.
     *
     * @return array<mixed>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Add request option.
     *
     * @param string $value
     */
    public function addOption(string $key, $value): void
    {
        $this->options[$key] = $value;
    }

    /**
     * Add request options.
     *
     * @param array<mixed> $options
     */
    public function addOptions(array $options): void
    {
        $this->options = array_replace($this->options, $options);
    }

    /**
     * Get request options.
     *
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Get decorated request headers.
     *
     * @return array<mixed>
     */
    public function getDecoratedHeaders(): array
    {
        $decoratedHeaders = [];

        foreach ($this->headers as $name => $value) {
            $decoratedHeaders[] = $name . ': ' . $value;
        }

        return $decoratedHeaders;
    }

    /**
     * Get request uri.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get request body.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Get Url for APNs production server.
     */
    private function getProductionUrl(Notification $notification): string
    {
        return self::APNS_PRODUCTION_SERVER . $this->getUrlPath($notification);
    }

    /**
     * Get Url for APNs sandbox server.
     */
    private function getSandboxUrl(Notification $notification): string
    {
        return self::APNS_DEVELOPMENT_SERVER . $this->getUrlPath($notification);
    }

    /**
     * Get Url path.
     */
    private function getUrlPath(Notification $notification): string
    {
        return str_replace('{token}', $notification->getDeviceToken(), self::APNS_PATH_SCHEMA);
    }

    /**
     * Prepare APNs headers before sending request.
     *
     * @psalm-suppress PossiblyNullReference
     */
    private function prepareApnsHeaders(Notification $notification): void
    {
        if (!empty($notification->getId())) {
            $this->headers[self::HEADER_APNS_ID] = $notification->getId();
        }

        if ($notification->getExpirationAt() instanceof \DateTime) {
            $this->headers[self::HEADER_APNS_EXPIRATION] = $notification->getExpirationAt()->getTimestamp();
        }

        if (is_int($notification->getPriority())) {
            $this->headers[self::HEADER_APNS_PRIORITY] = $notification->getPriority();
        } elseif ($notification->getPayload()->isContentAvailable()) {
            $this->headers[self::HEADER_APNS_PRIORITY] = Notification::PRIORITY_LOW;
        }

        if (!empty($notification->getCollapseId())) {
            $this->headers[self::HEADER_APNS_COLLAPSE_ID] = $notification->getCollapseId();
        }
        // if the push type was set when the payload was created then it will set that as a push type,
        // otherwise we would do our best in order to guess what push type is.
        if (!empty($notification->getPayload()->getPushType())) {
            $this->headers[self::HEADER_APNS_PUSH_TYPE] = $notification->getPayload()->getPushType();
        } elseif ($notification->getPayload()->isContentAvailable()) {
            $this->headers[self::HEADER_APNS_PUSH_TYPE] = 'background';
        } else {
            $this->headers[self::HEADER_APNS_PUSH_TYPE] = 'alert';
        }
    }
}
