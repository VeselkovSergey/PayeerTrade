<?php

namespace PayeerTrade\Exceptions;

/**
 * Не существующий метод
 */
class UnknownMethodException extends \BadMethodCallException
{
    /**
     * Constructor
     *
     * @param $method
     */
    public function __construct($method)
    {
        parent::__construct("Метода $method не существует");
    }
}
