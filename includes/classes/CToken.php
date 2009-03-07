<?php
class CToken
{
  function setToken()
  {
    $key = md5(uniqid(rand(), true));
    $GLOBALS['dbh']->execute("INSERT INTO tokens(t_string, t_dateTime) VALUES('{$key}', NOW())");
    
    return $key;
  }
  
  function getToken($token)
  {
    $token_safe = $this->dbh->sql_safe($token);
    $sql = 'SELECT t_string AS TOKEN FROM tokens WHERE t_string = ' . $token_safe;
    $check = $GLOBALS['dbh']->query_first($sql);
    
    return isset($check['TOKEN']) ? $check['TOKEN'] : false;
  }
  
  function clearToken($token)
  {
    $token_safe = $this->dbh->sql_safe($token);
    $GLOBALS['dbh']->execute('DELETE FROM tokens WHERE t_string = ' . $token_safe);
  }
  
  function useToken($token)
  {
    $return = $this->getToken($token);
    if($return !== false)
    {
      $this->clearToken($token);
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
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
  function CToken()
  {
    
  }
}
?>