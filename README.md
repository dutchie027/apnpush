# Apnpush

[![Software License][ico-license]](LICENSE.md)

Apnpush is a simple PHP library for sending push notifications via Apple's APN Service. 

## Features

- [X] Uses new Apple APNs HTTP/2 connection
- [X] Supports JWT-based authentication
- [X] Supports Certificate-based authentication
- [X] Supports new iOS features such as Collapse IDs, Subtitles and Mutable Notifications
- [X] Uses concurrent requests to APNs
- [X] Tested and working in APNs production environment

## Requirements

* PHP 7.4+
* lib-curl >= 7.46.0 (with http/2 support enabled)
* lib-openssl >= 1.0.2e 

## Install

Via Composer

``` bash
$ composer require dutchie027/apnpush
```

## Getting Started

``` php
<?php
require __DIR__ . '/vendor/autoload.php';

use Apnpush\Client;
use Apnpush\Notification;
use Apnpush\Payload;
use Apnpush\Payload\Alert;

$options = [
    'key_id' => 'AAAABBBBCC', // The Key ID obtained from Apple developer account
    'team_id' => 'DDDDEEEEFF', // The Team ID obtained from Apple developer account
    'app_bundle_id' => 'com.app.Test', // The bundle ID for app obtained from Apple developer account
    'private_key_path' => __DIR__ . '/private_key.p8', // Path to private key
    'private_key_secret' => null // Private key secret
];

// Be aware of thing that Token will stale after one hour, so you should generate it again.
// Can be useful when trying to send pushes during long-running tasks
$authProvider = Apnpush\AuthProvider\Token::create($options);

$alert = Alert::create()->setTitle('Hello!')->setBody('First push notification');

$payload = Payload::create()->setAlert($alert);

//set notification sound to default
$payload->setSound('default');

//add custom value to your notification, needs to be customized
$payload->setCustomValue('key', 'value');

$deviceTokens = ['<device_token_1>', '<device_token_2>', '<device_token_3>'];

$notifications = [];
foreach ($deviceTokens as $deviceToken) {
    $notifications[] = new Notification($payload,$deviceToken);
}

$client = new Client($authProvider, $production = false);
$client->addNotifications($notifications);

$responses = $client->push(); // returns an array of ApnsResponseInterface (one Response per Notification)

foreach ($responses as $response) {
    // The device token
    $response->getDeviceToken();
    // A canonical UUID that is the unique ID for the notification. E.g. 123e4567-e89b-12d3-a456-4266554400a0
    $response->getApnsId();
    
    // Status code. E.g. 200 (Success), 410 (The device token is no longer active for the topic.)
    $response->getStatusCode();
    // E.g. The device token is no longer active for the topic.
    $response->getReasonPhrase();
    // E.g. Unregistered
    $response->getErrorReason();
    // E.g. The device token is inactive for the specified topic.
    $response->getErrorDescription();
    $response->get410Timestamp();
}
```

Using Certificate (.pem). Only the initilization differs from JWT code (above). Remember to include the rest of the code by yourself.

``` php
<?php

$client = new Client($authProvider, $production = false);
$client->addNotifications($notifications);


// Set the number of concurrent requests sent through the multiplexed connections. Default : 20
$client->setNbConcurrentRequests( 40 );

// Set the number of maximum concurrent connections established to the APNS servers. Default : 1
$client->setMaxConcurrentConnections( 5 );

$responses = $client->push();

```

## Testing

``` bash
# run php fixer
$ composer fix

# run phpstan
$ compser stan

# run phpunit tests
$ composer test

# run all three above test in sequence
$ composer runall
```

## To-Do

* Fix the tests
  * Ensure they all port/move ok (pslam)
  * Check to see if http/2 is installed
* Clean up the documentation
* Other things

## Code of Conduct

This project adheres to a [code of conduct](CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to
uphold this code.

## License

Apnpush is released under the MIT License. See [`LICENSE`](LICENSE.md) for details.

## Versioning

This code uses [Semver](https://semver.org/). This means that versions are tagged
with MAJOR.MINOR.PATCH. Only a new major version will be allowed to break backward
compatibility (BC).

Classes marked as `@experimental` or `@internal` are not included in our backward compatibility promise.
You are also not guaranteed that the value returned from a method is always the
same. You are guaranteed that the data type will not change.

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).


## Credits

- Thanks to the original author [Arthur Edamov][link-original-author]
- Also, to those who have [contributed][link-contributors]

[ico-version]: https://img.shields.io/packagist/v/edamov/pushok.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/dutchie027/apnpush
[link-downloads]: https://packagist.org/packages/edamov/pushok
[link-original-author]: https://github.com/edamov
[link-contributors]: ../../contributors
