<?php

namespace Wearesho\Bobra\IPay;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Bobra\IPay
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return $this->getEnv('IPAY_ID');
    }

    /**
     * @inheritdoc
     */
    public function getKey(): string
    {
        return $this->getEnv("IPAY_KEY");
    }

    /**
     * @inheritdoc
     */
    public function getSecret(): string
    {
        return $this->getEnv("IPAY_SECRET");
    }

    /**
     * @inheritdoc
     */
    public function getLifetime(): int
    {
        return $this->getEnv("IPAY_LIFETIME", 24);
    }

    /**
     * @inheritdoc
     */
    public function getLanguage(): string
    {
        return $this->getEnv("IPAY_LANGUAGE", ConfigInterface::LANGUAGE_UA);
    }

    /**
     * @inheritdoc
     */
    public function getVersion(): string
    {
        return $this->getEnv('IPAY_VERSION', '3.00');
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return $this->getEnv("IPAY_DEBUG", false)
            ? ConfigInterface::URL_TEST
            : ConfigInterface::URL_REAL;
    }
}
