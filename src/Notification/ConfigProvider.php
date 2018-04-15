<?php

namespace Wearesho\Bobra\IPay\Notification;

use Wearesho\Bobra\IPay;

/**
 * Class ConfigProvider
 * @package Wearesho\Bobra\IPay\Notification
 */
class ConfigProvider implements ConfigProviderInterface
{
    /** @var array|IPay\ConfigInterface[] */
    protected $configs;

    /**
     * ConfigProvider constructor.
     * @param IPay\ConfigInterface[] $configs
     */
    public function __construct(array $configs = [])
    {
        foreach ($configs as $config) {
            if (!$config instanceof IPay\ConfigInterface) {
                throw new \InvalidArgumentException(
                    "All configs have to implement " . IPay\ConfigInterface::class
                );
            }
        }
        $this->configs = $configs;
    }

    /**
     * @param int $merchantId
     * @return IPay\ConfigInterface
     * @throws \RuntimeException
     */
    public function provide(int $merchantId): IPay\ConfigInterface
    {
        foreach ($this->configs as $config) {
            if ($config->getId() === $merchantId) {
                return $config;
            }
        }
        throw new UnsupportedMerchantException($merchantId);
    }
}