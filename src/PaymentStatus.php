<?php

namespace Wearesho\Bobra\IPay;

/**
 * Interface PaymentStatus
 * @package Wearesho\Bobra\IPay
 */
interface PaymentStatus
{
    // region Регистрация платежа
    public const CREATION_SUCCESS = 1; // Платеж успешно зарегистрирован
    public const CREATION_FAIL = 2; // Ошибка при регистрации платежа
    // endregion

    // region Авторизация средств на карте
    public const AUTHORIZATION_SUCCESS = 3; // Авторизация средств на карте успешна
    public const AUTHORIZATION_FAIL = 4; // Ошибка при авторизации средств на карте
    // endregion

    // region Списание средств с карты
    public const WRITE_DOWN_SUCCESS = 5; // Списание средств с карты успешно
    public const WRITE_DOWN_FAIL = 6; // Ошибка при списании средств с карты
    // endregion

    // region Запрос на отложенное списание
    public const WRITE_DOWN_REQUEST_SUCCESS = 7; // Запрос на списание обработан успешно
    public const WRITE_DOWN_REQUEST_FAIL = 8; // Ошибка при выполнении запроса на списание
    // endregion

    // region Запрос на отложенную отмену
    public const CANCEL_REQUEST_SUCCESS = 9; // Запрос на отмену авторизации выполнен успешно
    public const CANCEL_REQUEST_FAIL = 10; // Ошибка при выполнении запроса на отмену
    // endregion
}
