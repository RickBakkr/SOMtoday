<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 17:41
 */

namespace SOMtodayAPI;

include_once __DIR__ . "/../../curl.php";
include_once __DIR__ . "/Exceptions/AuthException.php";
include_once __DIR__ . "/Exceptions/BindInexistantException.php";
include_once __DIR__ . "/Exceptions/RequestException.php";
include_once __DIR__ . "/Interfaces/Fillable.php";
include_once __DIR__ . "/Models/Absence.php";
include_once __DIR__ . "/Models/Grade.php";
include_once __DIR__ . "/Models/Observation.php";
include_once __DIR__ . "/Models/Student.php";
include_once __DIR__ . "/Models/Appointment.php";
include_once __DIR__ . "/Models/Measure.php";
include_once __DIR__ . "/Models/SWIGemaakt.php";
include_once __DIR__ . "/Models/Studiewijzeritemafspraaktoekenning.php";
include_once __DIR__ . "/Services/Auth.php";
include_once __DIR__ . "/Services/Request.php";

use SOMtodayAPI\SOMtodayAPI\Models\Student;
use SOMtodayAPI\SOMtodayAPI\Services\Auth;
use SOMtodayAPI\SOMtodayAPI\Services\Request;

include_once(__DIR__ . "/Container.php");
class SOMtoday extends Container {
	public function __construct($uuid, $username, $password) {
		$this->registerBindings($uuid, $username, $password);
		$this->authenticate();
	}

	private function registerBindings($uuid, $username, $password) {
		$this->bind('uuid', $uuid);
		$this->bind('username', $username);
		$this->bind('password', $password);
	}

	private function authenticate() {
		$auth = Auth::login(
			$this->getBind('uuid'),
			$this->getBind('username'),
			$this->getBind('password')
		);
		if (empty($auth->token_type)){
			die("\nAuth::login() went wrong!\n");
		}
		$this->bind('auth_details', $auth);
		Request::setToken($auth->token_type .' ' . $auth->access_token);
		Request::setBaseURL($auth->somtoday_api_url);
	}

	public function getStudents() {
		$request = Request::get('/rest/v1/leerlingen');
		return Student::fillFromArray($request->items);
	}

	public function getStudent($id) {
		if(!is_numeric($id)) {
			throw new \Exception("ID is not numeric.");
		}
		$request = Request::get('/rest/v1/leerlingen/' . $id);
		return Student::fill($request);
	}

	public static function getSchools() {
		$request = Request::getOrdinary('https://servers.somtoday.nl/organisaties.json');
		$res = array_shift($request)->instellingen;
		return $res;
	}
}
