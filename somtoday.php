<?php

/**
 * @author Stephan Meijer <me@stephanmeijer.com>
 * @copyright MIT (09/11/2014)
 * @version 1.0.0
 */

/**
 * @author Rick Bakker <rickbakkr@gmail.com>
 * @version 1.0.1
 * 
 * This version is working at 20-12-2015.
 * SOM might do changes since then! 
 *
 */

class SOMtodayUser
{
    
    public $pupilID;
    
    public $fullname;
    
    public $username;
    
    private $password;
    
    public $schoolName;
    
    /**
     * BRIN-code
     * 
     * @link http://nl.wikipedia.org/wiki/Basis_Registratie_Instellingen Wikpedia page for more info
     */
    public $BRIN;
    
    /**
     * Base url, as a base for requests
     */
    public $baseURL;
    
    /**
     * @param String $username User's SOMtoday username
     * @param String $password User's SOMtoday password
     * @param String $schoolName The name of your school, as found on http://servers.somtoday.nl
     * @param String $BRIN The BRIN-code of your school, an unique identifier
     */
    function __construct($username, $password, $schoolName, $BRIN = null)
    {
        
        $this->username   = $username;
        $this->schoolName = $schoolName;
        if(is_null($BRIN))
        {
            $this->BRIN = $this->brinLookup($this->schoolName);
        } else {
            $this->BRIN = $BRIN;
        }
        
        $login = $this->login($username, $password);
        
    }
    
    /*
     * Method to get the institution's BRIN based on their abbreviation.
     *
     * @param String $schoolName Plain-text abbreviation
     * @return String BRIN of the institiution
     */
    private function brinLookup($schoolName)
    {
        $servers = file_get_contents('http://servers.somtoday.nl/');
        $servers = json_decode($servers, true);
            
        foreach($servers[0]['instellingen'] as $instelling) 
        {
            if($instelling['afkorting'] == $schoolName) 
            {
                return $instelling['brin'];
            }
        }
        return false;
    }
    
    /**
     * SOMtoday's way of hashing password is NOT SECURE!
     * 
     * @param String $password Plain-text password
     * @return String Hashed and encoded password
     */
    private function hashAndEncodePassword($password)
    {
        // Yes, SOMtoday is using SHA1. This is a shame!
        $hash = sha1($password, true);
        // Base64, how useless..
        $hash = base64_encode($hash);
        // Converting string to hex, another useless layer
        $hash = bin2hex($hash);
        return $hash;
        
    }
    
    /**
     * Authenticate user using given credentials.
     * 
     * @param String $username Plain-text username
     * @param String $password Plain-text password
     * 
     * @return Array Server Response
     * 
     */
    private function login($username, $password)
    {
        
        $passwordHash = $this->hashAndEncodePassword($password);
        $username     = base64_encode($username);
        
        $baseURL = "https://somtoday.nl/" . $this->schoolName . "/services/mobile/v10/";
        
        $url = $baseURL . "Login/CheckMultiLoginB64/" . $username . "/" . $passwordHash . "/" . $this->BRIN;
        
        $response = json_decode(file_get_contents($url), true);
        
        if ($response["error"] == "SUCCESS") {
            
            $fullName = $response["leerlingen"][0]["fullName"];
            $pupilID  = (string) $response["leerlingen"][0]["leerlingId"];
            
            $this->fullName = $fullName;
            $this->baseURL  = $baseURL;
            $this->pupilID  = $pupilID;
            $this->password = $passwordHash;
            $this->username = $username;
            
            return array(
                "success" => true,
                "fullname" => $fullName,
                "pupil_id" => $pupilID
            );
            
        } elseif ($response["error"] == "FEATURE_NOT_ACTIVATED") {
            
            return array(
                "success" => false,
                "error_message" => "This school doesn't support the SOMtoday API."
            );
            
        } elseif ($response["error"] == "FAILED_AUTHENTICATION") {
            
            return array(
                "success" => false,
                "error_message" => "Invalid login details."
            );
            
        } elseif ($response["error"] == "FAILED_OTHER_TYPE") {
            
            return array(
                "success" => false,
                "error_message" => "This account isn't supported."
            );
            
        } else {
            
            return array(
                "success" => false,
                "error_message" => "Unknown error."
            );
            
        }
        
    }
    
    /**
     * @return Array An array with the most recent grades, ordered by age
     */
    public function getGrades()
    {
        $url      = $this->baseURL . "Cijfers/GetMultiCijfersRecentB64/" . $this->username . "/" . $this->password . "/" . $this->BRIN . "/" . $this->pupilID;
        $response = json_decode(file_get_contents($url), true);
        
        return $response["data"];
    }
    
    /**
     * @param Int $daysAhead A number that indicates the amount of days ahead the homework has to be displayed
     * @return Array An array with all homework until the amount of days ahead
     */
    public function getHomework($daysAhead = 14)
    {
        
        $daysAhead = (string) $daysAhead;
        
        $url = $this->baseURL . "Agenda/GetMultiStudentAgendaHuiswerkMetMaxB64/" . $this->username . "/" . $this->password . "/" . $this->BRIN . "/" . $daysAhead . "/" . $this->pupilID;
        
        $response = json_decode(file_get_contents($url), true);
        
        return $response["data"];
    }
    
    /**
     * @param Int $daysAhead A number that indicates the amount of days ahead it has to fetch the schedule for. Default=0
     * @return Array All classes for the current day, or a specified amount of days ahead.
     */
    public function getSchedule($daysAhead = 0)
    {
        $date = (time() + ($daysAhead * 86400)) * 1000;
        
        $url      = $this->baseURL . "Agenda/GetMultiStudentAgendaB64/" . $this->username . "/" . $this->password . "/" . $this->BRIN . "/" . (string) $date . "/" . $this->pupilID;
        $response = json_decode(file_get_contents($url), true);
        
        
        return $response["data"];
    }
    
    /**
     * Changes the status of the given homework.
     * 
     * @param Int $homeworkID The "huiswerkid" returned by getHomework()
     * @param Int $appointmentID Yhe "afspraakid" returneb by getHomework()
     * @param Boolean $status The status you want to give that assignment. True is done, false is not done.
     * 
     * @return Boolean True if the status change succeeded, false if not.
     */
    public function changeHomeworkStatus($homeworkID, $appointmentID, $status)
    {
        
        $url = $this->baseURL . "Agenda/HuiswerkDoneB64/" . $this->username . "/" . $this->password . "/" . $this->BRIN . "/" . (string) $appointmentID . "/" . (string) $homeworkID . "/" . (string) $status;
        
        $responseStatus = json_decode( file_get_contents( $url ), true)["status"];
        
        return ($responseStatus == "OK");
        
    }
    
}
