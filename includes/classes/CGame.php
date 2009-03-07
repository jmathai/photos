<?php
 /*******************************************************************************************
  * Name:  CGame.php
  *
  * Class to handle general game stuff
  *
  * Usage:
  * 
  *******************************************************************************************/
class CGame {
 /*******************************************************************************************
  * Description
  *   Method to retrieve games
  *
  * Input (one of the following combinations)
  *   $premium            bool
  * Output
  *   $return             array
  *******************************************************************************************/
  function games($premium = false)
  {
    $sql  = 'SELECT gm_id AS G_ID, gm_type AS G_TYPE, gm_name AS G_NAME, gm_description AS G_DESCRIPTION, gm_max_fotos AS G_MAX_FOTOS, gm_premium AS G_PREMIUM '
          . 'FROM games ';
    if($premium === false)
    {
      $sql .= "WHERE gm_premium <> 'Y' ";
    }
    
    $return = $this->dbh->query_all($sql);
    
    return $return;
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
      include_once PATH_CLASS . '/CFotobox.php';
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
      $inst->stamp= $stamp > 0 ? $stamp : FF_YM_STAMP;
      $this->fb   =& CFotobox::getInstance();
    }
    
    return $inst;
  }
  
 /*******************************************************************************************
  * Description
  *   Constructor
  *******************************************************************************************/
  function CGame()
  {
  }
}
?>