<?php

namespace PayeerTrade\Exceptions;

/**
 * Обработка ошибок API
 */
class ApiException extends \ErrorException
{
    /**
     * Constructor
     */
    public function __construct($error)
    {
        parent::__construct(ApiErrorsEnum::from($error)->description());
    }
}
