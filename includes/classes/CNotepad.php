<?php
 /*
  *******************************************************************************************
  * Name:  CNotepad.php
  *
  * Class to handle notepad functions
  *
  * Usage:
  * 
  ******************************************************************************************
  */
 
class CNotepad
{
 /*
  *******************************************************************************************
  * Name
  *   note
  * Description
  *   Method to get a note
  * Output
  *   Array
  ******************************************************************************************
  */
  function note($user_id = false, $note_id = false)
  {
    $return = array();
    if($note_id !== false)
    {
      $user_id  = $this->dbh->sql_safe($user_id);
      $note_id  = $this->dbh->sql_safe($note_id);
      $sql      = 'SELECT n_id AS N_ID, n_u_id AS N_U_ID, n_tags AS N_TAGS, n_note AS N_NOTE FROM notepad WHERE n_id = ' . $note_id . ' AND n_u_id = ' . $user_id . " AND n_active = 'Y'";
      $return   = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   notes
  * Description
  *   Method to get a user's notes
  * Output
  *   Array
  ******************************************************************************************
  */
  function notes($user_id = false, $order = false)
  {
    $return = array();
    if($note_id !== false)
    {
      $user_id  = $this->dbh->sql_safe($user_id);
      $sql      = 'SELECT n_id AS N_ID, n_u_id AS N_U_ID, n_tags AS N_TAGS, n_note AS N_NOTE FROM notepad WHERE n_u_id = ' . $user_id . " AND n_active = 'Y' ";
      switch($order)
      {
        case 'CREATED_ASC':
          $sql .= 'ORDER BY n_dateCreated ASC';
          break;
        case 'CREATED_DESC':
          $sql .= 'ORDER BY n_dateCreated DESC';
          break;
        case 'MODIFIED_ASC':
          $sql .= 'ORDER BY n_dateModified ASC';
          break;
        case 'MODIFIED_DESC':
        default:
          $sql .= 'ORDER BY n_dateModified DESC';
          break;
      }
      $return   = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   add
  * Description
  *   Method to add a note
  * Output
  *   int/bool  id of note added on success / false on failure
  ******************************************************************************************
  */
  function add($data = false)
  {
    $return = false;
    if(is_array($data))
    {
      $key      = $this->dbh->sql_safe(md5($data['n_u_id'] . NOW));
      if($data['n_tags'] != '')
      {
        $tags     = (array)explode(',', $data['n_tags']);
        foreach($tags as $k => $v)
        {
          $tags[$k] = preg_replace('/\W/', '', $v);
        }
        $data['n_tags'] = ',' . implode(',', $tags) . ',';
      }
      $data['n_tags'] = ',' . implode(',', $tags) . ',';
      $data     = $this->dbh->asql_safe($data);
      $sql      = 'INSERT into notepad(n_u_id, n_key, n_tags, n_note, n_dateCreated, n_dateModified, n_active) '
                . 'VALUES(' . $data['n_u_id'] . ', ' . $key . ', ' . $data['n_tags'] . ', ' . $data['n_note'] . ", NOW(), NOW(), 'Y')";
      $this->dbh->execute($sql);
      
      $return = $this->dbh->insert_id();
    }
    
    return $return;
  }
 
 /*
  *******************************************************************************************
  * Name
  *   update
  * Description
  *   Method to update a note
  * Output
  *   int/bool  id of note added on success / false on failure
  ******************************************************************************************
  */
  function update($data = false)
  {
    $return = false;
    if(is_array($data))
    {
      $note_id  = intval($data['n_id']);
      if($data['n_tags'] != '')
      {
        $tags     = (array)explode(',', $data['n_tags']);
        foreach($tags as $k => $v)
        {
          $tags[$k] = preg_replace('/\W/', '', $v);
        }
        $data['n_tags'] = ',' . implode(',', $tags) . ',';
      }
      $data     = $this->dbh->asql_safe($data);
      $sql      = 'UPDATE notepad SET ';
      
      foreach($data as $k => $v)
      {
        $sql .= $k . ' = ' . $v . ', ';
      }
      
      $sql .= 'n_dateModified = NOW() '
            . 'WHERE n_id = ' . $note_id;
      $this->dbh->execute($sql);

      $return = $note_id;
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
  
 /*
  *******************************************************************************************
  * Name
  *   CNotepad
  * Description
  *   Constructor
  * Input
  *   None
  * Output
  *   None
  ******************************************************************************************
  */
  function CNotepad()
  {
  }
}
?>