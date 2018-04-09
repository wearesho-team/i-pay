<?php

namespace Wearesho\Bobra\IPay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay;

/**
 * Class TransactionTest
 * @package Wearesho\Bobra\IPay\Tests\Unit
 */
class TransactionTest extends TestCase
{
    /** @var IPay\Transaction */
    protected $transaction;

    protected function setUp():void
    {
        parent::setUp();
        $this->transaction = new IPay\Transaction(0, 0, 0, 'UAH');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCurrency()
    {
        $this->transaction->setCurrency('UA');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidType()
    {
        $this->transaction->setType(0);
    }

    public function testValidType()
    {
        $this->assertInstanceOf(
            IPay\TransactionInterface::class,
            $this->transaction->setType(IPay\TransactionInterface::TYPE_AUTHORIZATION)
        );
    }
}