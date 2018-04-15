<?php

namespace Wearesho\Bobra\IPay;

/**
 * Class Payment
 * @package Wearesho\Bobra\IPay
 */
class Payment implements PaymentInterface
{
    use PaymentTrait;

    /** @var string */
    protected $url;

    public function __construct(int $id, string $url, int $status, string $salt, string $sign)
    {
        $this->id = $id;
        $this->url = $url;
        $this->status = $status;
        $this->sign = $sign;
        $this->salt = $salt;
    }

    public function getUrl(): string
    {
        return $this->url;
    }


    public function jsonSerialize(): array
    {
        return [
            'status' => $this->status,
            'url' => $this->url,
        ];
    }
}
