<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 20:09
 */

function strToHex($string){
	$hex = '';
	for ($i = 0; $i < strlen($string); $i++) {
		$ord = ord($string[$i]);
		$hexCode = dechex($ord);
		$hex .= substr('0' . $hexCode, -2);
	}
	return strToUpper($hex);
}
function encodePassword($password) {
	return strtolower(strToHex(base64_encode(sha1($password, true))));
}

include_once("./src/SOMtodayAPI/SOMtoday.php");
use SOMtodayAPI\SOMtoday;

$uuid = '';
$username = '412';
$password = 'MyPassword';
$mySchool;

$allSchools = SOMtoday::getSchools();
foreach($allSchools as $school){
        if ($school->naam == "Rietschans College"){
                $mySchool = $school;
                $uuid = $school->uuid;
        }
}
$som = new SOMtodayAPI\SOMtoday($uuid, $username, $password);

// Array of Student objects
// $students = $som->getStudents();

// One specific student
// $student = $som->getStudent(3061729886130);

// Get grades belonging to a specific student
// $student->getGrades();
