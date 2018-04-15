<?php

namespace Wearesho\Bobra\IPay\Tests\Unit\Notification;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay;
use Wearesho\Bobra\Payments\Transaction;
use Wearesho\Bobra\Payments\TransactionCollection;

/**
 * Class PaymentTest
 * @package Wearesho\Bobra\IPay\Tests\Unit\Notification
 */
class PaymentTest extends TestCase
{
    public function testGetters()
    {
        $payment = new IPay\Notification\Payment(
            1,
            'ident',
            IPay\PaymentStatus::CREATION_SUCCESS,
            50,
            'UAH',
            new \DateTime('2017-01-01'),
            $transactions = new TransactionCollection([
                new Transaction(1, 50, 'type', 'Description', ['info' => true,])
            ]),
            'salt',
            'sign'
        );

        $this->assertEquals('ident', $payment->getIdent());
        $this->assertEquals(IPay\PaymentStatus::CREATION_SUCCESS, $payment->getStatus());
        $this->assertEquals('UAH', $payment->getCurrency());
        $this->assertEquals(50, $payment->getAmount());
        $this->assertEquals($transactions, $payment->getTransactions());
        // phpcs:ignore
        $expectedJson = '{"id":1,"ident":"ident","transactions":[{"id":1,"amount":5000,"type":"type","description":"Description","info":{"info":true},"currency":"UAH"}],"timestamp":"2017-01-01 00:00:00","currency":"UAH","amount":50,"status":1}';
        $this->assertEquals(
            $expectedJson,
            json_encode($payment)
        );
    }
}
