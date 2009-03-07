<?php
 /*
  *******************************************************************************************
  * Name:  CSession.php
  *
  * General class for user interaction.
  *
  * Usage:
  *   include_once('CSession.php');
  *   $sess = new CSession;
  *   $sess->start();
  * 
  ******************************************************************************************
  */
class CSession
{
 /*
  *******************************************************************************************
  * Name
  *   unregister
  * Description
  *   Method to remove a session variable
  *
  * Input (one of the following combinations)
  *   $name                 (str)
  * Output
  *   boolean
  ******************************************************************************************
  */
  function unregister($name = false)
  {
    if($name !== false)
    {
      unset($this->sess_data[$name]);
      $name = $this->dbh->sql_safe($name);
      $this->dbh->execute("DELETE FROM user_session_data WHERE usd_name = {$name} AND us_id = '{$this->sess_id}'");
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
  *   register
  * Description
  *   Method to register a session variable
  *
  * Input (one of the following combinations)
  *   $name                 (str)
  *   $value                (str)
  * Output
  *   boolean or session id
  ******************************************************************************************
  */
  function register($name = false, $value = false)
  {
    if($name !== false && $value !== false)
    {
      $this->sess_data[$name] = $value;
      
      $name = $this->dbh->sql_safe($name);
      $value= $this->dbh->sql_safe($value);
      
      $this->dbh->execute("REPLACE INTO user_session_data SET usd_name = {$name}, usd_value = {$value}, us_id = '{$this->sess_id}'");
      return $value;
    }
    else
    {
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   value
  * Description
  *   Method to retrieve a session variable
  *
  * Input
  *   $name                 (str)
  * Output
  *   string
  ******************************************************************************************
  */
  function value($string)
  {
    if(isset($this->sess_data[$string]))
    {
      return $this->sess_data[$string];
    }
    else
    {
      return false;
    }
  }
  

 /*
  *******************************************************************************************
  * Name
  *   start
  * Description
  *   Method to start a session
  *   
  * Input (one of the following combinations)
  *   $sess_id                 (int)
  * Output
  *   boolean or session id
  ******************************************************************************************
  */
  function start($sess_id = false, $persistent_login = false)
  {
    if($sess_id === false)
    {
      $this->sess_id = $this->Idat->nextID(KEY_SESSION);
      $this->sess_hash = uniqid(FF_SESSION_UID_PREFIX);
      $this->dbh->execute($sql = "INSERT INTO user_session( us_id, us_hash, us_ud_id, us_timeAccessed, us_timeCreated ) VALUES( '{$this->sess_id}', '{$this->sess_hash}', '{$this->user_id}', Now(), Now() )");
      setcookie(FF_SESSION_KEY, $this->sess_hash, 0, FF_SESSION_PATH, FF_SESSION_DOMAIN);
      $this->sess_data['sess_hash'] = $this->sess_hash;
      return $this->sess_id;
    }
    else
    if($this->sess_id !== false)
    {
      $rs = $this->dbh->query($sql = "SELECT usd_name, usd_value FROM user_session_data WHERE us_id = '{$this->sess_id}'");
      while($data = $this->dbh->fetch_assoc($rs))
      {
          $this->sess_data[$data['usd_name']] = $data['usd_value'];
      }
      $this->sess_data['sess_hash'] = $this->sess_hash;
      
      $this->dbh->execute("UPDATE user_session SET us_timeAccessed = Now() WHERE us_id = '{$this->sess_id}'");
      
      $expiry = $this->sess_data['persistent'] == 1 ? NOW + 10368000 : 0; // if persistent is 1 then expire on date else at end of browser session
      setcookie(FF_SESSION_KEY, $this->sess_hash, $expiry, FF_SESSION_PATH, FF_SESSION_DOMAIN);

      return $this->sess_id;
    }
    else
    {
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   destroy
  * Description
  *   Method to destroy a session
  *
  * Output
  *   boolean
  ******************************************************************************************
  */
  function destroy()
  {
    $this->dbh->execute("DELETE FROM user_session WHERE us_id = '{$this->sess_id}'");
    $this->dbh->execute("DELETE FROM user_session_data WHERE us_id = '{$this->sess_id}'");
    setcookie(FF_SESSION_KEY, $this->sess_hash, NOW - 3600, FF_SESSION_PATH, FF_SESSION_DOMAIN);
    unset($this);
    return true;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   CSession
  * Description
  *   Constructor
  *
  * Output
  *   boolean
  ******************************************************************************************
  */
  function CSession($sess_identifier = false, $user_id = false)
  {
    include_once PATH_CLASS . '/CIdat.php';
    
    $this->dbh =& $GLOBALS['dbh'];
    
    $this->Idat =& CIdat::getInstance();
    
    if(is_numeric($sess_identifier))
    {
      $ar = $this->dbh->fetch_assoc(
              $this->dbh->query("SELECT us_hash FROM user_session WHERE us_id = '{$sess_identifier}'")
            );
      $this->sess_id = $sess_id;
      $this->sess_hash = $ar['us_hash'];
    }
    else
    if(strlen($sess_identifier) === 13)
    {
      $ar = $this->dbh->fetch_assoc(
              $this->dbh->query($sql = "SELECT us_id FROM user_session WHERE us_hash = '{$sess_identifier}'")
            );
      $this->sess_hash = $sess_identifier;
      $this->sess_id = $ar['us_id'];
    }
    else
    {
      $this->sess_hash = false;
      $this->sess_id = false;
    }
    
    if($user_id === false)
    {
      $ar = $this->dbh->fetch_assoc(
              $this->dbh->query("SELECT us_ud_id FROM user_session WHERE us_id = '{$this->sess_id}'")
            );
      $this->user_id = $ar['us_ud_id'];
    }
    else
    {
      $this->user_id = $user_id;
    }
    
    $this->sess_data = array();
  }
}
?>