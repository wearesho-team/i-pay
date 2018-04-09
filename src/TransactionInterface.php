<?php

namespace Wearesho\Bobra\IPay;

/**
 * Interface TransactionInterface
 * @package Wearesho\Bobra\IPay
 */
interface TransactionInterface
{
    public const TYPE_AUTHORIZATION = 10; // Авторизация
    public const TYPE_CHARGE = 11; // Списание

    /**
     * Идентификатор услуги по которой совершается транзакция
     * @return int
     */
    public function getService(): int;

    /**
     * Тип транзакции
     * @see TransactionInterface::TYPE_AUTHORIZATION
     * @see TransactionInterface::TYPE_CHARGE
     * @return int
     */
    public function getType(): int;

    /**
     * Сумма транзакции, без разделителя
     * @return int
     */
    public function getAmount(): int;

    /**
     * Фиксированная комиссия к платежу, без разделителя
     * @return int
     */
    public function getFee(): ?int;

    /**
     * Валюта транзакции, 3-х буквенный код
     * @return string
     */
    public function getCurrency(): string;

    /**
     * Текстовое описание назначения транзакции
     * @return string
     */
    public function getDescription(): string;

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
