<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 29-3-19
 * Time: 15:24
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;

class Appointment implements Fillable {
    public static function fill($data) {
        $object = new Appointment();
        $keys = ['beginDatumTijd', 'eindDatumTijd', 'titel', 'omschrijving', 'presentieRegistratieVerplicht', 'presentieRegistratieVerwerkt', 'afspraakStatus'];
        foreach($keys as $key) {
            if(isset($data->$key)) {
                $object->$key = $data->$key;
            }
        }

        $data = $data->afspraakType;
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