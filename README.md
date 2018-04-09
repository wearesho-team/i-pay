# IPay Integration

## Installation
Using composer:
```php
composer install wearesho-team/i-pay
```

## Requirements
- PHP >= 7.1
- SimpleXML

## Usage
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

## License
MIT