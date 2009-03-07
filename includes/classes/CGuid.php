<?php
/*******************************************************************************************
 * Class Name:  CGuid
 *------------------------------------------------------------------------------------------
 * Mod History: Chip Kellam, Mar 31 2003
 *------------------------------------------------------------------------------------------
 * Class to create a "mock" guid. (format: random_num-microtime-ip_address)
 *
 * Usage:
 *   include('/export/home/jacor-common/classes/CGuid.php');
 *   $oGuid = new CGuid();
 *   $guid  = $oGuid->create();
 * 
 *******************************************************************************************/
 
class CGuid {
  
 /*
  *******************************************************************************************
  * Name
  *   getInstance
  * Description
  *   Static method to invoke this class
  * Output
  *   Class object
  ******************************************************************************************
  */
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;
    
    if($inst === null)
    {
      $inst         = new $class;
      $inst->_srand = false;
    }
    
    return $inst;
  }
  
 /*******************************************************************************************
  * Description
  *   constructor, initializes variables
  *******************************************************************************************/
  function CGuid() {
    //$this->_srand = false;
  }

 /*******************************************************************************************
  * Description
  *   internal function to seed the random number generator
  *******************************************************************************************/
  function _seedRandom() {
    if(!$this->_srand) {
      srand((double)microtime() * 1000000);
      $this->_srand = true;
    }
  }

 /*******************************************************************************************
  * Description
  *   mimics dechex() but can be used for large numbers
  *
  * Input
  *   $dec - decimal to be converted
  *
  * Output
  *   hex number
  *******************************************************************************************/
  function dechex_large($dec) {
    $hex = ($dec == 0) ? '0' : '';

    while ($dec > 0) {
      $hex = dechex($dec - floor($dec / 16) * 16) . $hex;
      $dec = floor($dec / 16);
    }

    return $hex;
  }

 /*******************************************************************************************
  * Description
  *   mimics hexdec() but can be used for large numbers
  *
  * Input
  *   $hex - hex number to be converted
  *
  * Output
  *   decimal number
  *******************************************************************************************/
  function hexdec_large($hex){ 
    $numlength = strlen($hex); 
    $decnumber = 0; 

    for($i=1; $i<=$numlength; $i++){ 
      $place      = $numlength - $i; 
      $operand    = hexdec(substr($hex, $place, 1)); 
      $exponent   = pow(16, $i-1); 
      $decValue   = $operand * $exponent; 
      $decnumber += $decValue; 
    } 

    return $decnumber; 
  }

 /*******************************************************************************************
  * Description
  *   create, creates our GUID
  *
  * Output
  *   our GUID
  *******************************************************************************************/
  function create() {
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $this->_seedRandom();

    $guid_parts['random']   = substr('00000000' . dechex( rand(0, 2147483647) ), -8);
    $guid_parts['time']     = substr('000000000000' . $this->dechex_large(time()), -12);
    $guid_parts['time']    .= substr('000000' . dechex((double)microtime() * 1000000), -6);
    $guid_parts['ip']       = '';

    if($ip_address != '') {
      $ip_address_parts = explode('.', $ip_address);
      
      for($i=0; $i<4; $i++) {
        $guid_parts['ip'] .= substr('00' . dechex($ip_address_parts[$i]), -2);
      }
    }

    $guid = $guid_parts['random'] . '-' . $guid_parts['time'] . '-' . $guid_parts['ip'];

    return $guid;
  }

 /*******************************************************************************************
  * Description
  *   retrieves random number part of our GUID
  *
  * Input
  *   $guid - GUID to analyze
  *
  * Output
  *   random number used in the GUID
  *******************************************************************************************/
  function getNumber($guid='') {
    if(strlen($guid) <= 0) {
      return;
    }
    
    $guid_parts = split('-', $guid);
    return hexdec($guid_parts[0]);
  }

 /*******************************************************************************************
  * Description
  *   retrieves timestamp (with microtime) part of our GUID
  *
  * Input
  *   $guid - GUID to analyze
  *
  * Output
  *   timestamp.milliseconds used in the GUID
  *******************************************************************************************/
  function getTimestamp($guid='') {
    if(strlen($guid) <= 0) {
      return;
    }
    
    $guid_parts = split('-', $guid);
    $time       = substr($guid_parts[1], 0, strlen($guid_parts[1])-6);
    $microtime  = substr($guid_parts[1], -6);
    $time       = $this->hexdec_large($time) . '.' . hexdec($microtime);
    return($time);
  }

 /*******************************************************************************************
  * Description
  *   retrieves the IP Address part of our GUID
  *
  * Input
  *   $guid - GUID to analyze
  *
  * Output
  *   IP address used in GUID
  *******************************************************************************************/
  function getIP($guid='') {
    if(strlen($guid) <= 0) {
      return;
    }

    $guid_parts = split('-', $guid);
    $ip_address = hexdec(substr($guid_parts[2],0,2)) . '.' .
                  hexdec(substr($guid_parts[2],2,2)) . '.' .
                  hexdec(substr($guid_parts[2],4,2)) . '.' .
                  hexdec(substr($guid_parts[2],6,2));
    return $ip_address;
  }
}
?>