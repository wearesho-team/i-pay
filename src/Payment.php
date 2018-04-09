<?php

namespace Wearesho\Bobra\IPay;

/**
 * Class Payment
 * @package Wearesho\Bobra\IPay
 */
class Payment
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
