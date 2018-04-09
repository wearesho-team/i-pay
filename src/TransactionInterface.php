<?php

namespace Wearesho\Bobra\IPay;

use Wearesho\Bobra\Payments;

/**
 * Interface TransactionInterface
 * @package Wearesho\Bobra\IPay
 */
interface TransactionInterface extends Payments\TransactionInterface
{
    public const TYPE_AUTHORIZATION = 10; // Авторизация
    public const TYPE_CHARGE = 11; // Списание

    /**
     * Доп. комментарии к назначению платежа
     * @return null|string
     */
    public function getNote(): ?string;

    /**
     * Любая дополнительная информация о транзакции
     * будет приведена к JSON при запросе
     * @return array
     */
    public function getInfo(): array;
}
