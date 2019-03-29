<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 29-3-19
 * Time: 15:13
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;

class Studiewijzeritemafspraaktoekenning implements Fillable {
    public static function fill($orgData) {
        $object = new Studiewijzeritemafspraaktoekenning;
        $data = $orgData->studiewijzerItem;
        $data->datumTijd = $orgData->datumTijd;
        $data->huiswerkKlaarDatum = $orgData->huiswerkKlaarDatum;
        $keys = ['datumTijd', 'huiswerkKlaarDatum', 'opdrachtBeschrijving', 'onderwerp', 'type', 'inleverperiodes', 'lesmateriaal', 'projectgroepen', 'bijlagen', 'externeMaterialen', 'tonen'];
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