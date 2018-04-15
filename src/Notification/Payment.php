<?php

namespace Wearesho\Bobra\IPay\Notification;

use Wearesho\Bobra\IPay;
use Wearesho\Bobra\Payments;

/**
 * Class Payment
 * @package Wearesho\Bobra\IPay\Notification
 */
class Payment implements IPay\PaymentInterface
{
    use IPay\PaymentTrait;

    /** @var string */
    protected $ident;

    /** @var int */
    protected $amount;

    /** @var string */
    protected $currency;

    /** @var \DateTime */
    protected $timestamp;

    /** @var Payments\TransactionCollection */
    protected $transactions;

    public function __construct(
        int $id,
        string $ident,
        int $status,
        int $amount,
        string $currency,
        \DateTime $timestamp,
        Payments\TransactionCollection $transactions,
        string $salt,
        string $sign
    ) {
        $this->id = $id;
        $this->ident = $ident;
        $this->status = $status;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->timestamp = $timestamp;
        $this->transactions = $transactions;
        $this->salt = $salt;
        $this->sign = $sign;
    }

    /**
     * Уникальный идентификатор платежа, используется в формировании web-ссылки
     * @return string 40 байт
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

    /**
     * @return Payments\TransactionCollection
     */
    public function getTransactions(): Payments\TransactionCollection
    {
        return $this->transactions;
    }

    /**
     * Дата проведения последней операции по платежу
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * 3х буквенных код валюты платежа
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Общая сумма платежа, сумма всех транзакций и комиссии в копейках
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this;
    }
}
