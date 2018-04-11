<?php

namespace Wearesho\Bobra\IPay;

use Throwable;

/**
 * Class InvalidSignException
 * @package Wearesho\Bobra\IPay
 */
class InvalidSignException extends \Exception
{
    /** @var string */
    protected $sign;

    /** @var string */
    protected $salt;

    public function __construct(
        string $sign,
        string $salt,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->sign = $sign;
        $this->salt = $salt;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }
}
