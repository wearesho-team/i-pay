<?php

namespace Wearesho\Bobra\IPay;

use Wearesho\Bobra\Payments;

/**
 * Class Payment
 * @package Wearesho\Bobra\IPay
 */
class Payment implements Payments\PaymentInterface
{
    const PAYMENT_REGISTERED = 1; // Платеж успешно зарегистрирован
    const PAYMENT_ERROR = 2; // Ошибка при регистрации платежа

    /** @var int */
    public $id;

    /** @var string */
    public $url;

    /** @var int */
    public $status;

    public function __construct(int $id, string $url, int $status)
    {
        $this->id = $id;
        $this->url = $url;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status,
            'redirectUrl' => $this->url,
        ];
    }
}
