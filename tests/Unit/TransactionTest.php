<?php

namespace Wearesho\Bobra\IPay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay;

class TransactionTest extends TestCase
{
    public function testSerizlize()
    {
        $transaction = new IPay\Transaction(
            1,
            50,
            'Description',
            'type',
            ['info' => true],
            'RUB'
        );

        $this->assertEquals(
            '{"id":1,"amount":5000,"type":"type","description":"Description","info":{"info":true},"currency":"RUB"}',
            json_encode($transaction)
        );

        $transaction
            ->setMerchantId(1)
            ->setFee(2.5)
            ->setNote('Note');

        $this->assertEquals(
            '{"id":1,"amount":5000,"type":"type","description":"Description","info":{"info":true},"currency":"RUB","fee":250,"note":"Note","merchantId":1}',
            json_encode($transaction)
        );
    }
}
