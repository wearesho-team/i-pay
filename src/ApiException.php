<?php

namespace Wearesho\Bobra\IPay;

/**
 * Class ApiException
 * @package Wearesho\Bobra\IPay
 */
class ApiException extends \Exception
{
    public function __construct(int $code, \Throwable $previous = null)
    {
        parent::__construct($message = "", 0, $previous);
        $this->setCode($code);
    }

    public function setCode(int $code): ApiException
    {
        $this->code = $code;
        $this->message = $this->_getMessage($code);
        return $this;
    }

    /**
     * @param int $code
     * @return string
     * @codeCoverageIgnore This method contains only configuration
     */
    protected function _getMessage(int $code): string
    {
        switch ($code) {
            case 1:
                return "Запрос не в формате XML или XML запрос не корректен.";
            case 2:
                return "Указаны не все необходимые параметры, или параметры содержат некорректные данные.";
            case 3:
                return "Неверный идентификатор мерчанта.";
            case 4:
                return "Неверная подпись или ключ мерчанта.";
            case 5:
                return "Ошибка данных транзакции или транзакция запрещена.";
            case 100:
                return "Превышено «время жизни» платежа.";
            default:
                if ($code > 5 and $code < 100) {
                    return "Внутренняя ошибка системы, обратитесь в службу технической поддержки iPay.";
                }
                return "Неизвестная ошибка $code.";
        }
    }
}
