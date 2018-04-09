<?php

namespace Wearesho\Bobra\IPay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay\Payment;

/**
 * Class PaymentTest
 * @package Wearesho\Bobra\IPay\Tests\Unit
 */
class PaymentTest extends TestCase
{
    public function testJson()
    {
        $payment = new Payment(1, 'https://google.com', 2);
        $json = json_encode($payment);
        $this->assertEquals('{"status":2,"url":"https:\/\/google.com"}', $json);
    }
}
