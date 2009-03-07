<?php
 /*
  *******************************************************************************************
  * Name:  CNews.php
  *
  * Class to handle news funcitons
  *
  * Usage:
  * 
  ******************************************************************************************
  */
  class CNews
  {
   /*
    *******************************************************************************************
    * Name
    *   news
    * Description
    *   Retrieve news records
    * Input
    *   news($mode = 'string')
    * Output
    *   Array
    ******************************************************************************************
    */
    function news($mode = 'unarchived')
    {
      $sql  = 'SELECT sn_id AS N_ID, UNIX_TIMESTAMP(sn_date) AS N_DATE, sn_headline AS N_HEADLINE, sn_body AS N_BODY '
            . 'FROM site_news ';
      switch($mode)
      {
        case 'unarchived':
          $sql .= " WHERE sn_archived = 'N' ";
          break;
        case 'archived':
          $sql .= " WHERE sn_archived = 'Y' ";
          break;
      }
      
      $sql .= ' ORDER BY sn_date DESC';
      
      
      $return = $this->dbh->query_all($sql);
      
      return $return;
    }
    
   /*
    *******************************************************************************************
    * Name
    *   newsData
    * Description
    *   Retrieve news records
    * Input
    *   news($mode = 'string')
    * Output
    *   Array
    ******************************************************************************************
    */
    function newsData($id = false)
    {
      $return = array();
      
      if($id !== false)
      {
        $id   = $this->dbh->sql_safe($id);
        $sql  = 'SELECT sn_id AS N_ID, UNIX_TIMESTAMP(sn_date) AS N_DATE, sn_headline AS N_HEADLINE, sn_body AS N_BODY '
              . 'FROM site_news '
              . 'WHERE sn_id = ' . $id;
        
        $return = $this->dbh->query_first($sql);
      }
      
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
      $inst      = new $class;
      $inst->dbh =& $GLOBALS['dbh'];
    }
    
    return $inst;
  }
    
   /*
    *******************************************************************************************
    * Name
    *   CNews
    * Description
    *   Constructor
    * Input
    *   None
    * Output
    *   None
    ******************************************************************************************
    */
    function CNews()
    {
    }
  }
?>