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
    protected $merchantId;

    /** @var int */
    protected $type = TransactionInterface::TYPE_CHARGE;

    public function __construct(
        int $service,
        float $amount,
        string $description,
        string $type = TransactionInterface::TYPE_CHARGE,
        array $info = [],
        string $currency = 'UAH'
    ) {
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
        return $this->setNumeric($this->fee, $fee);
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

    public function setMerchantId(int $merchantId): Transaction
    {
        $this->merchantId = $merchantId;
        return $this;
    }

    public function getMerchantId(): ?int
    {
        return $this->merchantId;
    }

    public function jsonSerialize()
    {
        $json = parent::jsonSerialize();
        if (!empty($this->getFee())) {
            $json['fee'] = $this->getFee();
        }
        if (!empty($this->getNote())) {
            $json['note'] = $this->getNote();
        }
        if (!empty($this->getMerchantId())) {
            $json['merchantId'] = $this->getMerchantId();
        }
        return $json;
    }
}
