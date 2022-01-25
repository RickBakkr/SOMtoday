<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 20:09
 */

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
$students = $som->getStudents();

// One specific student
$student = $som->getStudent(3061729886130);

// Get grades belonging to a specific student
$student->getGrades();
