<?php


namespace App\Services;


class CustomApiException extends \Exception
{

    /**
     * CustomApiException constructor.
     * @param $HTTP_BAD_REQUEST
     * @param string $param
     */
    public function __construct($HTTP_BAD_REQUEST, string $param)
    {
        parent::__construct($param, $HTTP_BAD_REQUEST);
    }
}