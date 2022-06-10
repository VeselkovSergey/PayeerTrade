<?php

namespace PayeerTrade\Exceptions;

/**
 * Не существующая функция
 */
class UnknownFunctionException extends \BadFunctionCallException
{
    /**
     * Constructor
     *
     * @param $function
     */
    public function __construct($function)
    {
        parent::__construct("Функции $function не существует");
    }
}
