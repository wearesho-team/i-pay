<?php

namespace Wearesho\Bobra\IPay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay\ConfigInterface;
use Wearesho\Bobra\IPay\EnvironmentConfig;

/**
 * Class EnvironmentConfigTest
 * @package Wearesho\Bobra\IPay\Tests\Unit
 */
class EnvironmentConfigTest extends TestCase
{
    /** @var EnvironmentConfig */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new EnvironmentConfig("T");
    }

    public function testGetId()
    {
        putenv('TIPAY_ID=1');
        $this->assertEquals(1, $this->config->getId());

        $this->expectException(\DomainException::class);
        putenv('TIPAY_ID');
        $this->config->getId();
    }

    public function testGetKey()
    {
        putenv('TIPAY_KEY=1');
        $this->assertEquals(1, $this->config->getKey());

        $this->expectException(\DomainException::class);
        putenv('TIPAY_KEY');
        $this->config->getKey();
    }

    public function testGetSecret()
    {
        putenv('TIPAY_SECRET=1');
        $this->assertEquals(1, $this->config->getSecret());

        $this->expectException(\DomainException::class);
        putenv('TIPAY_SECRET');
        $this->config->getSecret();
    }

    public function testGetLifetime()
    {
        $this->assertEquals(24, $this->config->getLifetime());
        putenv('TIPAY_LIFETIME=1');
        $this->assertEquals(1, $this->config->getLifetime());
    }

    public function testGetUrl()
    {
        $this->assertEquals(ConfigInterface::URL_REAL, $this->config->getUrl());
        putenv('TIPAY_DEBUG=1');
        $this->assertEquals(ConfigInterface::URL_TEST, $this->config->getUrl());
    }

    public function testGetLanguage()
    {
        $this->assertEquals(ConfigInterface::LANGUAGE_UA, $this->config->getLanguage());
        putenv("TIPAY_LANGUAGE=" . ConfigInterface::LANGUAGE_RU);
        $this->assertEquals(ConfigInterface::LANGUAGE_RU, $this->config->getLanguage());
    }

    public function testGetVersion()
    {
        $this->assertEquals('3.00', $this->config->getVersion());
        putenv('TIPAY_VERSION=3.01');
        $this->assertEquals('3.01', $this->config->getVersion());
    }
}
