<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 19:27
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;

class Grade implements Fillable {

    public static function fill($data) {
        $object = new Grade;

        // Some hacking to get the Somtoday ID.
        $links = array_shift($data->links);
        $object->id = (isset($links->id) ? $links->id : null);

        $keys = ['herkansingstype', 'resultaat', 'geldendResultaat', 'resultaatAfwijkendNiveau', 'resultaatLabel', 'resultaatLabelAfkorting', 'resultaatLabelAfwijkendNiveau', 'resultaatLabelAfwijkendNiveauAfkorting', 'datumInvoer', 'teltNietmee', 'toetsNietGemaakt', 'leerjaar', 'periode', 'overschrevenDoor', 'examenWeging', 'isExamendossierResultaat', 'isVoortgangsdossierResultaat', 'type', 'volgnummer', 'weging'];
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