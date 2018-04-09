<?php

namespace Wearesho\Bobra\IPay;

/**
 * Class Config
 * @package Wearesho\Bobra\IPay
 */
class Config implements ConfigInterface
{
    public const MODE_REAL = 'real';
    public const MODE_TEST = 'test';

    protected const URL_TEST = 'https://api.sandbox.ipay.ua/';
    protected const URL_REAL = 'https://api.ipay.ua/';

    /** @var int */
    protected $id;

    /** @var string */
    protected $key;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $language;

    /** @var string */
    protected $url = Config::URL_TEST;

    /** @var string */
    protected $version = '3.00';

    /** @var int */
    protected $lifetime = 1;

    public function __construct(int $id, string $key, string $secret)
    {
        $this->id = $id;
        $this->key = $key;
        $this->secret = $secret;
    }

    public function setLanguage(string $language): Config
    {
        if (
            $language !== ConfigInterface::LANGUAGE_EN
            && $language !== ConfigInterface::LANGUAGE_RU
            && $language !== ConfigInterface::LANGUAGE_UA
        ) {
            throw new \InvalidArgumentException("Invalid language");
        }

        $this->language = $language;

        return $this;
    }

    public function setMode($mode): Config
    {
        switch ($mode) {
            case static::MODE_TEST:
                $this->url = 'https://api.sandbox.ipay.ua/';
                break;
            case static::MODE_REAL:
                $this->url = 'https://api.ipay.ua/';
                break;
            default:
                throw new \InvalidArgumentException("Invalid mode.");
        }

        return $this;
    }

    public function setLifetime(int $hours): Config
    {
        if ($hours < 1 || $hours > 240) {
            throw new \InvalidArgumentException("Lifetime have to be between 1 and 240 hours");
        }
        $this->lifetime = $hours;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritdoc
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @inheritdoc
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * @inheritdoc
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @inheritdoc
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
