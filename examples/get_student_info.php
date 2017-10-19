<?php
require_once '../SOMtoday.class.php';
require_once '../config.php';

$som = new SOMtoday($username, $password, $guid);

var_dump($som->getStudents());
