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
$password = 'MyPasssword';

$allSchools = SOMtoday::getSchools();
foreach($allSchools as $school){
        if ($school->naam == "Rietschans College"){
                $uuid = $school->uuid;
        }
}
$som = new SOMtodayAPI\SOMtoday($uuid, $username, $password);


// Array of Student objects
$students = $som->getStudents();
print_r($students);


// One specific student
$student = $som->getStudent(1290286080);


// Get grades belonging to a specific student
$student->getGrades();
