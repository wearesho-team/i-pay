<?php

namespace Wearesho\Bobra\IPay;

/**
 * Class Transaction
 * @package Wearesho\Bobra\IPay
 */
class Transaction implements TransactionInterface
{
    /** @var string */
    protected $currency;

    /** @var int */
    protected $service;

    /** @var int */
    protected $amount;

    /** @var string */
    protected $description;

    /** @var string|null */
    protected $note = null;

    /** @var array */
    protected $info = [];

    /** @var int|null */
    protected $fee = null;

    /** @var int */
    protected $type = TransactionInterface::TYPE_CHARGE;

    public function __construct(int $service, float $amount, string $description, string $currency = 'UAH')
    {
        $this->service = $service;
        $this->_setNumeric($this->amount, $amount);
        $this->description = $description;
    }

    /**
     * @inheritdoc
     */
    public function getService(): int
    {
        return $this->service;
    }

    public function setService(int $service): Transaction
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): Transaction
    {
        if ($type !== static::TYPE_CHARGE && $type !== static::TYPE_AUTHORIZATION) {
            throw new \InvalidArgumentException("Invalid type $type");
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(float $amount): Transaction
    {
        return $this->_setNumeric($this->amount, $amount);
    }

    /**
     * @inheritdoc
     */
    public function getFee(): ?int
    {
        return $this->fee;
    }

    public function setFee(float $fee): Transaction
    {
        return $this->_setNumeric($this->fee, $fee);
    }

    /**
     * @inheritdoc
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Transaction
     * @todo: add currency validation
     */
    public function setCurrency(string $currency): Transaction
    {
        if (mb_strlen($currency) !== 3) {
            throw new \InvalidArgumentException("Currency code length must be equal to 3");
        }

        $this->currency = mb_strtoupper($currency);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Transaction
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note = null): Transaction
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    public function setInfo(array $info = []): Transaction
    {
        $this->info = $info;
        return $this;
    }

    protected function _setNumeric(&$field, float $value): Transaction
    {
        $field = round($value, 2) * 100;
        return $this;
    }
}
