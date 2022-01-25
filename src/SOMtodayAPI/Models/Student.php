<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 19:08
 */

namespace SOMtodayAPI\SOMtodayAPI\Models;

include_once(__DIR__ . "/../Interfaces/Fillable.php");
include_once(__DIR__ . "/../Services/Request.php");

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

    public function getAppointments() {
        $request = Request::get('/rest/v1/afspraken');
        return Appointment::fillFromArray($request->items);
    }

    public function getObservations() {
        $request = Request::get('/rest/v1/waarnemingen');
        return Observation::fillFromArray($request->items);
    }

    public function getMeasures() {
        $request = Request::get('/rest/v1/maatregeltoekenningen');
        return Measure::fillFromArray($request->items);
    }

    public function getAbsence() {
        $request = Request::get('/rest/v1/absentiemeldingen');
        return Absence::fillFromArray($request->items);
    }

    public function getStudiewijzeritemafspraaktoekenningen($from = null) {
        $request = Request::get('/rest/v1/studiewijzeritemafspraaktoekenningen' . (!is_null($from)?$from:''));
        return Studiewijzeritemafspraaktoekenning::fillFromArray($request->items);
    }

    public function getSWIGemaakt() {
      // SWIGemaakt = homework.
      $request = Request::get('/rest/v1/swigemaakt');
      return SWIGemaakt::fillFromArray($request->items);
    }
}
