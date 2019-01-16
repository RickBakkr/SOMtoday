<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 19:08
 */

namespace SOMtodayAPI\SOMtodayAPI\Interfaces;


interface Fillable {
    public static function fill($data);
    public static function fillFromArray(array $array);
}