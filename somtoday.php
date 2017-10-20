<?php

/**
 * @author Rick Bakker <rickbakkr@gmail.com>
 * @version 2.0.0
 * @license MIT
 * 
 * Please be aware of this:
 * Even though the class is functional at the time of release, SOM might do changes in the near future which will break the class! 
 *
 */

class SOMtoday
{
    
    public $pupilID;
    
    public $fullname;
    
    public $username;
    
    private $password;
    
    public $schoolName;
  
    public $type;
    
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
     * @param String $type Tells the class to either use -elo
     * @param String $BRIN The BRIN-code of your school, a unique identifier
     */
    function __construct($username, $password, $schoolName, $type = "pupil", $BRIN = null)
    {
        
        $this->username   = $username;
        $this->schoolName = $schoolName;
        if(is_null($BRIN))
        {
            $this->BRIN = $this->brinLookup($this->schoolName);
        } else {
            $this->BRIN = $BRIN;
        }
        $this->type = $type;
        
        $login = $this->authenticate($username, $password);
        
    }
    
    /*
     * Method to get the institution's BRIN based on their abbreviation.
     *
     * @param String $schoolName Plain-text abbreviation
     * @return String BRIN of the institiution
     */
    private function brinLookup($schoolIdentifier)
    {
        $servers = file_get_contents('https://servers.somtoday.nl/');
        $serverList = json_decode($servers, true);
            
        if(is_array($serverList)) 
        {
			$id = array_search($schoolIdentifier, array_column($serverList[0]['instellingen'], 'afkorting'));
			return $serverList[0]['instellingen'][$id]['brin'];
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
        $hash = sha1($password, true);
        $hash = base64_encode($hash);
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
    private function authenticate($username, $password)
    {
        
        $passwordHash = $this->hashAndEncodePassword($password);
        $username     = base64_encode($username);
        $identifier = "elo";
        $baseURL = "https://" . $this->schoolName . "-" . $identifier . ".somtoday.nl/services/mobile/v10/";
        
        $url = $baseURL . "Login/GetMD/" . $username . "/" . $passwordHash . "/" . $this->BRIN . "/";
        
        $response = json_decode(file_get_contents($url), true);
      
        if ($response["error"] == "SUCCESS") {
            
            $fullName = $response["leerlingen"][0]["fullName"];
            $pupilID  = (string) $response["leerlingen"][0]["leerlingId"];
            
            $this->fullName = $fullName;
            $this->baseURL  = $baseURL;
            $this->pupilID  = $pupilID;
            $this->password = $passwordHash;
            $this->username = $username;
            $this->dToken = $response["device"]["dToken"];
            $this->aToken = $response["device"]["aToken"];
            
            return array(
                "success" => true,
                "fullname" => $fullName,
                "pupil_id" => $pupilID,
                "dToken"  => $response["device"]["dToken"],
                "aToken"  => $response["device"]["aToken"]
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
        $url = $this->baseURL . "Cijfers/Cijfers/" . $this->aToken . "/" . $this->pupilID;
        
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
        
        $url = $this->baseURL . "Agenda/Huiswerk/" . $this->aToken . "/" . $daysAhead . "/" . $this->pupilID;
        
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
        
        $url = $this->baseURL . "Agenda/Agenda/" . $this->aToken . "/" . $date . "/" . $this->pupilID;
        $response = json_decode(file_get_contents($url), true);
        
        
        return $response["data"];
    }
    
    /**
     * Changes the status of the given homework.
     * 
     * @param Int $homeworkID The "huiswerkid" returned by getHomework()
     * @param Int $appointmentID The "afspraakid" returned by getHomework()
     * @param Boolean $done The status you want to give that assignment. True is done, false is not done.
     * 
     * @return Boolean True if the status change succeeded, false if not.
     */
    public function changeHomeworkStatus($homeworkID, $appointmentID, $done)
    {
        $url = $this->baseURL . "Agenda/Vink/" . $this->aToken . "/" . (string) $appointmentID . "/" . (string) $homeworkID . "/" . (string) $done;
        
        $responseStatus = json_decode( file_get_contents( $url ), true)["status"];
        
        return ($responseStatus == "OK");
        
    }
    
}
