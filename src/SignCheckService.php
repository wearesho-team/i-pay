<?php

namespace Wearesho\Bobra\IPay;

/**
 * Class SignCheckService
 * @package Wearesho\Bobra\IPay
 */
class SignCheckService
{
    /** @var ConfigInterface */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param PaymentInterface $payment
     * @throws InvalidSignException
     */
    public function check(PaymentInterface $payment): void
    {
        if (hash_hmac('sha512', $payment->getSalt(), $this->config->getKey()) !== $payment->getSign()) {
            throw new InvalidSignException(
                $payment->getSign(),
                $payment->getSalt(),
                "Invalid sign from response"
            );
        }
    }
}
