<?php

namespace Wearesho\Bobra\IPay;

/**
 * Interface ConfigInterface
 * @package Wearesho\Bobra\IPay
 */
interface ConfigInterface
{
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
     * @see Language
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
