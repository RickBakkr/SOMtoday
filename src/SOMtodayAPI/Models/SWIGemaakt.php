<?php

namespace SOMtodayAPI\SOMtodayAPI\Models;


use SOMtodayAPI\SOMtodayAPI\Interfaces\Fillable;

class SWIGemaakt implements Fillable {

  public static function fill($data) {
      $object = new SWIGemaakt;
      $datumTijd = isset($data->swiToekenning->datumTijd) ? $data->swiToekenning->datumTijd : null;
      $huiswerkKlaarDatum = isset($data->swiToekenning->huiswerkKlaarDatum) ? $data->swiToekenning->huiswerkKlaarDatum : null;
      $data = $data->swiToekenning->studiewijzerItem;
      $data->huiswerkKlaarDatum = $huiswerkKlaarDatum;
      $data->datumTijd = $datumTijd;
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