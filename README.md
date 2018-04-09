# IPay Integration
[![Latest Stable Version](https://poser.pugx.org/wearesho-team/i-pay/v/stable.png)](https://packagist.org/packages/wearesho-team/i-pay)
[![Total Downloads](https://poser.pugx.org/wearesho-team/i-pay/downloads.png)](https://packagist.org/packages/wearesho-team/i-pay)
[![Build Status](https://travis-ci.org/wearesho-team/i-pay.svg?branch=master)](https://travis-ci.org/wearesho-team/i-pay)
[![codecov](https://codecov.io/gh/wearesho-team/i-pay/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/i-pay)


## Installation
Using composer:
```bash
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