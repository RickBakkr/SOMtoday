<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 19:08
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;
use SOMtodayAPI\SOMtodayAPI\Services\Request;

class Student implements Fillable {

    public static function fill($data) {
        $object = new Student;

        // Some hacking to get the Somtoday ID.
        $links = array_shift($data->links);
        $object->id = (isset($links->id) ? $links->id : null);

        $keys = ['leerlingnummer', 'roepnaam', 'achternaam', 'email', 'mobielNummer', 'geboortedatum', 'geslacht'];
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

    public function getGrades() {
        $request = Request::get('/rest/v1/resultaten/huidigVoorLeerling/' . $this->id);
        return Grade::fillFromArray($request->items);
    }

    public function getHomework() {
      // SWIGemaakt = homework.
      $request = Request::get('/rest/v1/swigemaakt');
      return SWIGemaakt::fillFromArray($request->items);
    }
}
