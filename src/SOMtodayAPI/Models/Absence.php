<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 29-3-19
 * Time: 15:39
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;

class Absence implements Fillable {
    public static function fill($data) {
        $object = new Absence();
        $keys = ['datumTijdInvoer', 'beginDatumTijd', 'eindDatumTijd', 'beginLesuur', 'eindLesuur', 'afgehandeld', 'opmerkingen'];
        foreach($keys as $key) {
            if(isset($data->$key)) {
                $object->$key = $data->$key;
            }
        }

        $data = $data->absentieReden;
        $keys = ['absentieSoort', 'afkorting', 'omschrijving', 'geoorloofd'];
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