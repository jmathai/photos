<?php
/*******************************************************************************************
  * Name:  CLogging.php
  *
  * Class to handle Frequently Asked Questions
  *
  * Usage:
  * 
  *******************************************************************************************/
class CLogging {
 /*
  *******************************************************************************************
  * Name
  *   addHit
  * Description
  *   Method to log general hit action
  *
  * Input (one of the following combinations)
  *   $data
  * Output
  *   $return             bool
  ******************************************************************************************
  */
  function addHit($data = false)
  {
    if($data !== false)
    {
      $data = $this->dbh->asql_safe($data);
      
      $keys = array_keys($data);
      
      $sql  = 'INSERT INTO log_hits(' . implode(',', $keys) . ') '
            . 'VALUES(' . implode(',', $data) . ')';
            
      $this->dbh->execute($sql);
      
      return true;
    }
    else
    {
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   addHitFlix
  * Description
  *   Method to log general hit action from within the fotoflix
  *
  * Input (one of the following combinations)
  *   $data
  * Output
  *   $return             bool
  ******************************************************************************************
  */
  function addHitFlix($data = false)
  {
    if($data !== false)
    {
      $data = $this->dbh->asql_safe($data);
      
      $keys = array_keys($data);
      
      $sql  = 'INSERT INTO log_flix_clicks(' . implode(',', $keys) . ', lfc_dateTime) '
            . 'VALUES(' . implode(',', $data) . ', NOW())';
            
      $this->dbh->execute($sql);
      
      return true;
    }
    else
    {
      return false;
    }
  }
  
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
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
 /*******************************************************************************************
  * Description
  *   Constructor
  *******************************************************************************************/
  function CLogging()
  {
  }
}
?>