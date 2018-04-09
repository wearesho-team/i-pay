<?php

namespace Wearesho\Bobra\IPay;

use Wearesho\Bobra\Payments;

/**
 * Class Transaction
 * @package Wearesho\Bobra\IPay
 */
class Transaction extends Payments\Transaction implements TransactionInterface
{
    /** @var string|null */
    protected $note = null;

    /** @var int|null */
    protected $fee = null;

    /** @var int */
    protected $type = TransactionInterface::TYPE_CHARGE;

    public function __construct(
        int $service,
        float $amount,
        string $type = TransactionInterface::TYPE_CHARGE,
        string $description,
        array $info = [],
        string $currency = 'UAH'
    )
    {
        parent::__construct($service, $amount, $type, $description, $info, $currency);
    }

    /**
     * @inheritdoc
     */
    public function getFee(): ?int
    {
        return $this->fee;
    }

    public function setFee(float $fee): Payments\Transaction
    {
        return $this->_setNumeric($this->fee, $fee);
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
}
