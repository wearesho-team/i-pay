<?php

namespace Wearesho\Bobra\IPay\Tests\Unit\Notification;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay;

/**
 * Class ConfigProviderTest
 * @package Wearesho\Bobra\IPay\Tests\Unit\Notification
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage All configs have to implement Wearesho\Bobra\IPay\ConfigInterface
     */
    public function testInvalidArgument()
    {
        $notConfigInstance = new \stdClass;
        new IPay\Notification\ConfigProvider([$notConfigInstance]);
    }

    public function testProvidingConfig()
    {
        $firstConfig = new IPay\Config(1, 'key', 'secret');
        $secondConfig = new IPay\Config(2, 'key', 'secret');
        $thirdConfig = new IPay\EnvironmentConfig();

        $provider = new IPay\Notification\ConfigProvider([$firstConfig, $secondConfig, $thirdConfig]);
        $this->assertEquals($firstConfig, $provider->provide(1));
        $this->assertEquals($secondConfig, $provider->provide(2));

        $this->expectException(IPay\Notification\UnsupportedMerchantException::class);
        $provider->provide(3);
    }

    /**
     * @expectedException \Wearesho\Bobra\IPay\Notification\UnsupportedMerchantException
     * @expectedExceptionMessage Unsupported MerchantId 1
     */
    public function testMissingConfig()
    {
        $provider = new IPay\Notification\ConfigProvider([]);
        try {
            $provider->provide(1);
        } catch (IPay\Notification\UnsupportedMerchantException $exception) {
            $this->assertEquals(1, $exception->getMerchantId());
            throw $exception;
        }
    }
}
