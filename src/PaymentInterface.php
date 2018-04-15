<?php

namespace Wearesho\Bobra\IPay;

use Wearesho\Bobra\Payments;

/**
 * Interface PaymentInterface
 * @package Wearesho\Bobra\IPay
 */
interface PaymentInterface extends Payments\PaymentInterface
{
    /**
     * @see PaymentStatus
     * @return int
     */
    public function getStatus(): int;

    /**
     * Cтрока, сгенерированная из строки <salt> и секретного ключа API по алгоритму SHA512
     * @return string
     */
    public function getSign(): string;

    /**
     * Строка, сгенерированная из текущего времени в микросекундах по алгоритму SHA1
     * @return string
     */
    public function getSalt(): string;
}
