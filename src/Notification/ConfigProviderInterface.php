<?php

namespace Wearesho\Bobra\IPay\Notification;

use Wearesho\Bobra\IPay;

/**
 * Interface ConfigProvider
 * @package Wearesho\Bobra\IPay\Notification
 */
interface ConfigProviderInterface
{
    public function provide(int $merchantId): IPay\ConfigInterface;
}
