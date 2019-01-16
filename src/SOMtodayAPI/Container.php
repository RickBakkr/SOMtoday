<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 17:44
 */

namespace SOMtodayAPI;


use SOMtodayAPI\SOMtodayAPI\Exceptions\BindInexistantException;

class Container {

    protected function bind($key, $value) {
        $this->$key = $value;
    }
    protected function getBind($key) {
        if(isset($this->$key))
            return $this->$key;
        throw new BindInexistantException($key );
    }
}