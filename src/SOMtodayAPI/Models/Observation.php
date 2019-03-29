<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 29-3-19
 * Time: 15:29
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;

class Observation implements Fillable {
    public static function fill($data) {
        $object = new Observation;
        $keys = ['beginDatumTijd', 'eindDatumTijd', 'beginLesuur', 'eindLesuur', 'waarnemingSoort'];
        foreach($keys as $key) {
            if(isset($data->$key)) {
                $object->$key = $data->$key;
            }
        }
        $data = $data->afspraak->afspraakType;
        $keys = ['naam', 'omschrijving', 'standaardKleur', 'categorie', 'activiteit', 'percentageIIVO', 'presentieRegistratieDefault', 'actief'];
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