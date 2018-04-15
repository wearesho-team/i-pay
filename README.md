# IPay Integration
[![Latest Stable Version](https://poser.pugx.org/wearesho-team/i-pay/v/stable.png)](https://packagist.org/packages/wearesho-team/i-pay)
[![Total Downloads](https://poser.pugx.org/wearesho-team/i-pay/downloads.png)](https://packagist.org/packages/wearesho-team/i-pay)
[![Build Status](https://travis-ci.org/wearesho-team/i-pay.svg?branch=master)](https://travis-ci.org/wearesho-team/i-pay)
[![codecov](https://codecov.io/gh/wearesho-team/i-pay/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/i-pay)

[Change Log](./CHANGELOG.md)

## Installation
Using composer:
```bash
composer install wearesho-team/i-pay
```

## Requirements
- PHP >= 7.1
- SimpleXML

## Usage
### Creating Payments
For configuring you application use [ConfigInterface](./src/ConfigInterface.php)
(implementation also available in [Config](./src/Config.php) class)

```php
<?php

use Wearesho\Bobra\IPay;

$config = new IPay\Config($merchantId = 14, $merchantKey = 123456789, $merchantSecret = 987654321);
$config->setMode(IPay\Config::MODE_REAL); // Switching to production API (default: test)

// Note: you should use DI container to instantiate IPay\Client
$client = new IPay\Client($config, new \GuzzleHttp\Client());


/**
 * Creating payment
 */

$payment = $client->createPayment(
    new IPay\UrlPair(
        'http://ipay.ua/good',
        'http://ipay.ua/bad'
    ),
    [
        new IPay\Transaction(
            100, // Operation ID
            100.50, // Will be transformed into 10050 when requesting
            "Service Payment"
        ),
    ]
);
$payment->getUrl(); // You should redirect user to this page to make payment

/**
 * Competing payment
 */
$client->completePayment($paymentId = 3456);

/**
 * Reversing payment
 */
$client->completePayment($paymentId = 3456, IPay\Client::ACTION_REVERSAL);
// or
$client->reversePayment($paymentId = 3456);

```
### Handling notification
Implement controller using your framework
```php
<?php

namespace App;

use Wearesho\Bobra\IPay;

class Controller {
    public function actionIPay() {
        // You may handle as many merchant id as you want
        // just pass here configurations with different merchant IDs
        $configProvider = new IPay\Notification\ConfigProvider([
            new IPay\Config(1, "key", "secret"),
            new IPay\Config(2, "another-key", "another-secret"),
        ]);
        $server = new IPay\Notification\Server($configProvider);

        $xml = $_POST['xml'];
        if (empty($xml)) {
            throw new \HttpException(400, "Missing XML");
        }

        try {
            // Sign checking will be done automatically 
            $payment = $server->handle($xml);
        } catch (IPay\Notification\InvalidBodyException | IPay\InvalidSignException $exception) {
            throw new \HttpException(400, $exception->getMessage(), 0, $exception);
        } catch (IPay\Notification\UnsupportedMerchantException $exception) {
            throw new \HttpException(
                501,
                "Merchant ID {$exception->getMerchantId()} is not configured"
            );
        }

        // do what you want with payment
    }
}

```

## License
MIT