<?php

class CIdat{
  function CIdat() {
    include_once PATH_CLASS . '/CDatabase.php';
    $this->dbh =& $GLOBALS['dbh'];
  }

  function removeApplication($app_name='') {
    if($key == '') {
      return(-1);
    }
    
    $sql = "DELETE FROM idat WHERE STRCMP(LEFT(QualifiedKey, " . strlen($app_name) . "), '" . $app_name . "') = 0";

    $this->dbh->execute($sql);
    $affected_rows = $this->dbh->affected_rows();

    return($affected_rows);
  }
  
  function addField($key='', $initial_id=1) {
    if($key == '') {
      return(-1);
    }
    
    $sql = "INSERT INTO idat (QualifiedKey, Value) VALUES ('" . $key . "', " . $initial_id . ")";

    $this->dbh->execute($sql);
    $affected_rows = $this->dbh->affected_rows();
  }

  function removeField($key='') {
    if($key == '') {
      return(-1);
    }

    $sql = "DELETE FROM idat WHERE STRCMP(QualifiedKey, '" . $key . "') = 0";

    $this->dbh->execute($sql);
    $affected_rows = $this->dbh->affected_rows();

    return($affected_rows);
  }

  function nextID($key='') {
    if($key == '') {
      return(-1);
    }

    $sql_lock   = 'LOCK TABLES idat WRITE';
    $sql_get    = "SELECT CurrentID AS NextID FROM idat WHERE STRCMP(QualifiedKey, '" . $key . "') = 0";
    $sql_set    = "UPDATE idat SET CurrentID = CurrentID + 1 WHERE STRCMP(QualifiedKey, '" . $key . "') = 0";
    $sql_unlock = 'UNLOCK TABLES';

    //$this->dbh->execute($sql_lock);
    $res_id = $this->dbh->query($sql_get);
    if($this->dbh->num_rows($res_id) > 0) {
      $this->dbh->execute($sql_set);
    }
    
    //$this->dbh->execute($sql_unlock);

    if($this->dbh->num_rows($res_id) > 0) {
      $row_id = $this->dbh->fetch_assoc($res_id);
      return((int)$row_id['NextID']);
    }
    else {
      return(-1);
    }
  }

  function finish() {
    $this->dbh->close();
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
}
?>