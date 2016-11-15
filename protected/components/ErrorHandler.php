<?php

/**
 * Created by PhpStorm.
 * User: dkhodakovskiy
 * Date: 15.11.16
 * Time: 16:06
 */
class ErrorHandler
{

    public $code;
    public $message;

    const ERROR_INVALID_JSON    = 100;
    const ERROR_REQUEST_FAILED  = 101;
    const ERROR_NOT_ENOUGH_DATA = 102;

    public function __construct($code, $message)
    {
        $this->code     = $code;
        $this->message  = $message;
    }

}