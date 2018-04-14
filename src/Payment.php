<?php

namespace Wearesho\Bobra\IPay;

use Wearesho\Bobra\Payments;

/**
 * Class Payment
 * @package Wearesho\Bobra\IPay
 */
class Payment implements Payments\PaymentInterface
{
    use PaymentTrait;

    /** @var string */
    public $url;

    public function __construct(int $id, string $url, int $status)
    {
        $this->id = $id;
        $this->url = $url;
        $this->status = $status;
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
