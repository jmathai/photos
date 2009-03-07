<?php
 /*
  *******************************************************************************************
  * Name:  CMyPage.php
  *
  * General class for user page interaction
  * This class performs read and write functions on the database.
  *
  * Usage:
  *   include_once('CMyPage.php');
  *   $user =& CMyPage::getInstance();
  ******************************************************************************************
  */
class CMyPage
{
 /*
  *******************************************************************************************
  * Name
  *   page
  * Description
  *   Method to get page data
  * Input
  *   $user_id    int
  * Output
  *   array
  ******************************************************************************************
  */
  function page($user_id = false)
  {
    $return = array();
    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql  = 'SELECT p_nameType AS P_NAMETYPE, p_description AS P_DESCRIPTION, p_flixQuantity AS P_FLIXQUANTITY, p_colors AS P_COLORS '
            . 'FROM user_pages '
            . 'WHERE p_u_id = ' . $user_id;
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   update
  * Description
  *   update page prefs
  * Output
  *   boolean
  ******************************************************************************************
  */
  function update($data = false)
  {
    if($data['p_u_id'] !== false)
    {
      $data   = $this->dbh->asql_safe($data);
      
      $sql  = 'SELECT COUNT(p_u_id) AS _CHK FROM user_pages WHERE p_u_id = ' . $data['p_u_id'];
      $ar   = $this->dbh->query_first($sql);
      
      if($ar['_CHK'] == 1)
      {
        include_once PATH_INCLUDE . '/functions.php';
        if(isset($data['p_description']))
        {
          $data['p_description'] = sanitize($data['p_description']);
        }
        
        if(isset($data['p_colors']))
        {
          $data['p_colors'] = sanitize($data['p_colors']);
        }
        
        $sql = 'UPDATE user_pages SET ';
        foreach($data as $k => $v)
        {
          $sql .= $k . '=' . $v . ', ';
        }
        $sql = substr($sql, 0, -2);
        $sql .= ' WHERE p_u_id = ' . $data['p_u_id'];
      }
      else
      {
        $keys = array_keys($data);
        $sql = 'INSERT INTO user_pages(' . implode(',', $keys) . ') VALUES(' . implode(',', $data) . ')';
      }
      
      $this->dbh->execute($sql);
    }
    return true;
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
}