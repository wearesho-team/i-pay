<?php

namespace Wearesho\Bobra\IPay;

use Wearesho\Bobra\Payments;

/**
 * Trait PaymentTrait
 * @package Wearesho\Bobra\IPay
 */
trait PaymentTrait
{
    use Payments\PaymentTrait;

    /** @var int */
    protected $status;

    /** @var string */
    protected $salt;

    /** @var string */
    protected $sign;

    /**
     * @see PaymentStatus
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Cтрока, сгенерированная из строки <salt> и секретного ключа API по алгоритму SHA512
     * @return string
     */
    public function getSign(): string
    {
        return $this->sign;
    }

    /**
     * Строка, сгенерированная из текущего времени в микросекундах по алгоритму SHA1
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }
}
