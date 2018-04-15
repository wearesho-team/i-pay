<?php

namespace Wearesho\Bobra\IPay\Notification;

use Throwable;

/**
 * Class UnsupportedMerchantException
 * @package Wearesho\Bobra\IPay\Notification
 */
class UnsupportedMerchantException extends \RuntimeException
{
    /** @var int */
    protected $merchantId;

    public function __construct(int $merchantId, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Unsupported MerchantId $merchantId", $code, $previous);
        $this->merchantId = $merchantId;
    }

    public function getMerchantId(): int
    {
        return $this->merchantId;
    }
}
