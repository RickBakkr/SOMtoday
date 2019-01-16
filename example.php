<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 20:09
 */

use SOMtodayAPI\SOMtoday;

$allSchools = SOMtoday::getSchools();

$uuid = '2169e4bd-f4ff-4665-97ab-7bea4749b800';
$username = '147219';
$password = 'inolongerhaveaccesstosomtoday';

$som = new SOMtodayAPI\SOMtoday($uuid, $username, $password);

// Array of Student objects
$students = $som->getStudents();
// One specific student
$student = $som->getStudent(1290286080);

// Get grades belonging to a specific student
$student->getGrades();
