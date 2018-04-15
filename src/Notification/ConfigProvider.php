<?php

namespace Wearesho\Bobra\IPay\Notification;

use Wearesho\Bobra\IPay;
use Horat1us\Environment;

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
     * @throws UnsupportedMerchantException
     */
    public function provide(int $merchantId): IPay\ConfigInterface
    {
        foreach ($this->configs as $config) {
            try {
                $configMerchantId = $config->getId();
            } catch (Environment\MissingEnvironmentException $exception) {
                continue;
            }

            if ($configMerchantId === $merchantId) {
                return $config;
            }
        }
        throw new UnsupportedMerchantException($merchantId);
    }
}
