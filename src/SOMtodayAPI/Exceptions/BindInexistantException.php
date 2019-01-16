<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 18:47
 */

namespace SOMtodayAPI\SOMtodayAPI\Exceptions;


class BindInexistantException extends \Exception {
    public function __construct($key, $code = 0, Exception $previous = null) {
        $message = 'Bind ' . $key . ' is not declared.';
        parent::__construct($message, $code, $previous);
    }

}