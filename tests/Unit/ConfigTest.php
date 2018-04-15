<?php

namespace Wearesho\Bobra\IPay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wearesho\Bobra\IPay;

class ConfigTest extends TestCase
{
    /** @var IPay\Config */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new IPay\Config(1, 2, 3);
    }

    public function testSetLanguage()
    {
        $this->config->setLanguage(IPay\Language::RU);
        $this->assertEquals(IPay\Language::RU, $this->config->getLanguage());
        $this->expectException(\InvalidArgumentException::class);
        $this->config->setLanguage("uk");
    }

    public function testSetMode()
    {
        $this->config->setMode(IPay\Url::REAL);
        $this->assertEquals(
            'https://api.ipay.ua/',
            $this->config->getUrl()
        );
        $this->config->setMode(IPay\Url::TEST);
        $this->assertEquals(
            'https://api.sandbox.ipay.ua/',
            $this->config->getUrl()
        );
        $this->expectException(\InvalidArgumentException::class);
        $this->config->setMode('invalidMode');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLowLifetime()
    {
        $this->config->setLifetime(0);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHighLifetime()
    {
        $this->config->setLifetime(241);
    }

    public function testValidLifetime()
    {
        $this->config->setLifetime(10);
        $this->assertEquals(10, $this->config->getLifetime());
    }
}
