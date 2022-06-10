<?php

namespace PayeerTrade\Exceptions;

/**
 * Обработка ошибок
 */
class RuntimeException extends \RuntimeException
{
    /**
     * Constructor
     */
    public function __construct($message = "Что-то сломалось по дороге")
    {
        parent::__construct($message);
    }
}
