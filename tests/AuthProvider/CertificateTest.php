<?php

namespace Apnpush\AuthProvider;

use Apnpush\AuthProviderInterface;
use Apnpush\Notification;
use Apnpush\Payload;
use Apnpush\Request;
use PHPUnit\Framework\TestCase;

class CertificateTest extends TestCase
{
    public function testCreatingCertificateAuthProvider()
    {
        $options = $this->getOptions();
        $authProvider = Certificate::create($options);

        self::assertInstanceOf(AuthProviderInterface::class, $authProvider);
    }

    public function testAuthenticatingClient()
    {
        $options = $this->getOptions();
        $authProvider = Certificate::create($options);

        $request = $this->createRequest();
        $authProvider->authenticateClient($request);

        self::assertSame($request->getOptions()[CURLOPT_SSLCERT], $options['certificate_path']);
        self::assertSame($request->getOptions()[CURLOPT_SSLCERTPASSWD], $options['certificate_secret']);
    }

    public function testVoipApnsTopic()
    {
        $options = $this->getOptions();
        $authProvider = Certificate::create($options);

        $request = $this->createRequest('voip');
        $authProvider->authenticateClient($request);

        self::assertSame($request->getHeaders()['apns-topic'], $options['app_bundle_id'] . '.voip');
    }

    public function testComplicationApnsTopic()
    {
        $options = $this->getOptions();
        $authProvider = Certificate::create($options);

        $request = $this->createRequest('complication');
        $authProvider->authenticateClient($request);

        self::assertSame($request->getHeaders()['apns-topic'], $options['app_bundle_id'] . '.complication');
    }

    public function testFileproviderApnsTopic()
    {
        $options = $this->getOptions();
        $authProvider = Certificate::create($options);

        $request = $this->createRequest('fileprovider');
        $authProvider->authenticateClient($request);

        self::assertSame($request->getHeaders()['apns-topic'], $options['app_bundle_id'] . '.pushkit.fileprovider');
    }

    private function getOptions()
    {
        return [
            'certificate_path' => __DIR__ . '/../files/certificate.pem',
            'certificate_secret' => 'secret',
            'app_bundle_id' => 'com.apple.test',
        ];
    }

    private function createRequest(string $pushType = 'alert'): Request
    {
        $notification = new Notification(Payload::create()->setPushType($pushType), '123');

        return new Request($notification, $production = false);
    }
}
