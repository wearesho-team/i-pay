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

    /**
     * @see PaymentStatus
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
