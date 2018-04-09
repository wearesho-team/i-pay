<?php

namespace Wearesho\Bobra\IPay;

/**
 * Interface ConfigInterface
 * @package Wearesho\Bobra\IPay
 */
interface ConfigInterface
{
    public const LANGUAGE_RU = 'ru';
    public const LANGUAGE_UA = 'ua';
    public const LANGUAGE_EN = 'en';

    public const URL_TEST = 'https://api.sandbox.ipay.ua/';
    public const URL_REAL = 'https://api.ipay.ua/';

    /**
     * Merchant identifier
     *
     * @return int
     */
    public function getId(): int;

    /**
     * API Key
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * API Secret
     *
     * @return string
     */
    public function getSecret(): string;

    /**
     * Payment "life time", hours.
     * Min - 1, max - 240
     *
     * @return int
     */
    public function getLifetime(): int;

    /**
     * @see ConfigInterface::LANGUAGE_EN
     * @see ConfigInterface::LANGUAGE_RU
     * @see ConfigInterface::LANGUAGE_UA
     *
     * @return string
     */
    public function getLanguage(): string;

    /**
     * @return string
     */
    public function getVersion(): string;

    /**
     * API URL (test or production)
     *
     * @return string
     */
    public function getUrl(): string;
}
