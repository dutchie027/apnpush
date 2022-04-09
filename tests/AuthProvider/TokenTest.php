<?php

namespace Apnpush\AuthProvider;

use Apnpush\AuthProviderInterface;
use Apnpush\Notification;
use Apnpush\Payload;
use Apnpush\Request;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testCreatingTokenAuthProviderWithKeyPath()
    {
        $options = $this->getOptions();
        $options['private_key_path'] = __DIR__ . '/../files/private_key.p8';
        $authProvider = Token::create($options);

        self::assertInstanceOf(AuthProviderInterface::class, $authProvider);
        self::assertIsString($authProvider->get());
    }

    public function testCreatingTokenAuthProviderWithKeyContent()
    {
        $options = $this->getOptions();
        $options['private_key_content'] = file_get_contents(
            implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'files', 'private_key.p8'])
        );
        $authProvider = Token::create($options);

        self::assertInstanceOf(AuthProviderInterface::class, $authProvider);
        self::assertIsString($authProvider->get());
    }

    public function testCreatingTokenAuthProviderWithoutKey()
    {
        $this->expectException(\InvalidArgumentException::class);

        $options = $this->getOptions();
        Token::create($options);
    }

    public function testUseExistingToken()
    {
        $token = 'eyJhbGciOiJFUzI1NiIsImtpZCI6IjEyMzQ1Njc4OTAifQ.eyJpc3MiOiIxMjM0NTY3ODkwIiwiaWF0IjoxNDc4NTE0NDk4fQ.' .
            'YxR8Hw--Hp8YH8RF2QDiwOjmGhTC_7g2DpbnzKQZ8Sj20-q12LrLrAMafcuxf97CTHl9hCVere0vYrzcLmGV-A';

        $options = $this->getOptions();
        $authProvider = Token::useExisting($token, $options);

        self::assertInstanceOf(AuthProviderInterface::class, $authProvider);
        self::assertEquals($token, $authProvider->get());
    }

    public function testVoipApnsTopic()
    {
        $options = $this->getOptions();
        $options['private_key_path'] = __DIR__ . '/../files/private_key.p8';
        $authProvider = Token::create($options);

        $request = $this->createRequest('voip');
        $authProvider->authenticateClient($request);

        self::assertSame($request->getHeaders()['apns-topic'], $options['app_bundle_id'] . '.voip');
    }

    public function testComplicationApnsTopic()
    {
        $options = $this->getOptions();
        $options['private_key_path'] = __DIR__ . '/../files/private_key.p8';
        $authProvider = Token::create($options);

        $request = $this->createRequest('complication');
        $authProvider->authenticateClient($request);

        self::assertSame($request->getHeaders()['apns-topic'], $options['app_bundle_id'] . '.complication');
    }

    public function testFileproviderApnsTopic()
    {
        $options = $this->getOptions();
        $options['private_key_path'] = __DIR__ . '/../files/private_key.p8';
        $authProvider = Token::create($options);

        $request = $this->createRequest('fileprovider');
        $authProvider->authenticateClient($request);

        self::assertSame($request->getHeaders()['apns-topic'], $options['app_bundle_id'] . '.pushkit.fileprovider');
    }

    private function getOptions()
    {
        return [
            'key_id' => '1234567890',
            'team_id' => '1234567890',
            'app_bundle_id' => 'com.app.Test',
        ];
    }

    private function createRequest(string $pushType = 'alert'): Request
    {
        $notification = new Notification(Payload::create()->setPushType($pushType), '123');

        return new Request($notification, $production = false);
    }
}
