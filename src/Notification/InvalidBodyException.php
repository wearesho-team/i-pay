<?php

namespace Wearesho\Bobra\IPay\Notification;

use Throwable;

/**
 * Class InvalidBodyException
 * @package Wearesho\Bobra\IPay\Notification
 */
class InvalidBodyException extends \InvalidArgumentException
{
    /** @var string */
    protected $xml;

    public function __construct(string $xml, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->xml = $xml;
    }

    public function getXml(): string
    {
        return $this->xml;
    }
}
