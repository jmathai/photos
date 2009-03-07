<?php
 /*
  *******************************************************************************************
  * Name:  CFeedback.php
  *
  * Class to handle feedback stuff
  *
  * Usage:
  * 
  ******************************************************************************************
  */
class CFeedback
{
 /*
  *******************************************************************************************
  * Name
  *   add
  * Description
  *   Method to add a new flix
  *
  * Input
  *   $data   array (name / value pairs)
  * Output
  *   int/boolean     flix id/false
  ******************************************************************************************
  */
  function add($main = false)
  {
    if(is_array($main))
    {
      $main = $this->dbh->asql_safe($main);
      $keys = array_keys($main);
      
      $sql  = 'INSERT INTO feedback(' . implode(',', $keys) . ', f_dateCreated) '
            . 'VALUES(' . implode(',', $main) . ', NOW())';
      
      $this->dbh->execute($sql);
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   CFeedback
  * Description
  *   Constructor
  ******************************************************************************************
  */
  function CFeedback()
  {
    $this->dbh =& $GLOBALS['dbh'];
  }
}