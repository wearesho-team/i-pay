<?php

namespace Wearesho\Bobra\IPay\Notification;

use Wearesho\Bobra\Payments;

/**
 * Class Payment
 * @package Wearesho\Bobra\IPay\Notification
 */
class Payment
{
    use Payments\PaymentTrait;

    public function __construct()
    {
    }

    /**
     * Уникальный идентификатор платежа, используется в формировании web-ссылки
     * @return string 40 байт
     */
    public function getIdent(): string
    {
    }


}
