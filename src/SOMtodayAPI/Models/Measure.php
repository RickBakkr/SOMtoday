<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 29-3-19
 * Time: 15:34
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;

class Measure implements Fillable {
    public static function fill($data) {
        $object = new Measure;
        $keys = ['maatregelDatum', 'nagekomen', 'automatischToegekend', 'maatregelOmschrijving'];
        foreach($keys as $key) {
            if(isset($data->$key)) {
                $object->$key = $data->$key;
            }
        }
        return $object;
    }

    public static function fillFromArray(array $array) {
        $collection = [];
        foreach($array as $item) {
            $collection[] = self::fill($item);
        }
        return $collection;
    }
}