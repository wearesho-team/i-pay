<?php

namespace Wearesho\Bobra\IPay;

/**
 * Class UrlPair
 * @package Wearesho\Bobra\IPay
 */
class UrlPair
{
    /** @var string */
    protected $good;

    /** @var string */
    protected $bad;

    /**
     * UrlPair constructor.
     * @param string $good полный адрес URL для направления пользователя после успешной оплаты
     * @param string $bad полный адрес URL для направления пользователя при возникновении ошибки в процессе оплаты
     */
    public function __construct(string $good, string $bad)
    {
        $this->bad = $bad;
        $this->good = $good;
    }

    /**
     * Gets redirect URL for successful payment
     *
     * @return string
     */
    public function getBad(): string
    {
        return $this->bad;
    }

    /**
     * Gets redirect URL for unsuccessful payment
     *
     * @return string
     */
    public function getGood(): string
    {
        return $this->good;
    }
}
