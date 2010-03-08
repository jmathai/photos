<?php
 /*
  *******************************************************************************************
  * Name:  CComment.php
  *
  * Class to handle comment functions
  *
  * Usage:
  * 
  ******************************************************************************************
  */
class CComment
{
  function comments($element_id = false, $type = 'foto')
  {
    $return = array();
    if($element_id !== false)
    {
      $element_id = $this->dbh->sql_safe($element_id);

      $sql  = 'SELECT c.c_id AS C_ID, c.c_by_u_id AS C_BY_U_ID, c.c_for_u_id AS C_FOR_U_ID, c.c_element_id AS C_ELEMENT_ID, c.c_name AS C_NAME, c.c_comment AS C_COMMENT, c.c_time AS C_TIME, u.u_username AS C_USERNAME, u2.u_username AS C_BY_USERNAME, up.up_value AS C_AVATAR, up2.up_value AS C_AVATAR_KEY '
            . 'FROM ((comments AS c LEFT JOIN users AS u ON u.u_id = c.c_by_u_id) '
            . 'LEFT JOIN users AS u2 ON u2.u_id = c.c_by_u_id '
            . "LEFT JOIN user_prefs AS up ON (c.c_by_u_id = up.up_u_id AND up.up_name = 'AVATAR')) "
            . "LEFT JOIN user_prefs AS up2 ON (c.c_by_u_id = up2.up_u_id AND up2.up_name = 'AVATAR_KEY') "
            . "WHERE c.c_type = '{$type}' AND c.c_element_id = " . $element_id . " "
            . "AND c_status = 'Active' "
            . 'ORDER BY c.c_id ASC';
      
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
  function addComment($element_id = false, $by_user_id = 0, $for_user_id = 0, $comment = false, $type = 'foto', $raw_name = null)
  {
    include_once PATH_INCLUDE . '/functions.php'; // used to sanitize() comment
    include_once PATH_CLASS . '/CUser.php'; // use for activity
    include_once PATH_CLASS . '/CUserManage.php'; // use for activity
    
    $return = false;
    if($element_id !== false && $comment !== false)
    {
      $u  =& CUser::getInstance();
      $um =& CUserManage::getInstance();
      $element_id =intval($element_id);
      $comment    = $this->dbh->sql_safe(sanitize($comment, array('PRESERVE_ANCHORS' => true)));
      $typeSafe   = $this->dbh->sql_safe($type);
      $by_user_id   = intval($by_user_id);
      $for_user_id  = intval($for_user_id);
      $raw_name = $this->dbh->sql_safe(sanitize($raw_name));
      
      $sql  = 'INSERT INTO comments(c_by_u_id, c_for_u_id, c_element_id, c_name, c_comment, c_type, c_time) '
            . 'VALUES(' . $by_user_id . ', ' . $for_user_id . ', ' . $element_id . ', ' . $raw_name . ', ' . $comment . ', ' . $typeSafe . ', ' . NOW . ')';
      
      $this->dbh->execute($sql);
      
      $return = $this->dbh->insert_id();
      
      switch($type)
      {
        case 'blog':
          $element_rep = $element_id; 
          break;
        case 'flix':
          include_once PATH_CLASS . '/CFlix.php';
          $f =& CFlix::getInstance();
          $flixData = $f->search(array('FLIX_ID' => $element_id));
          $element_rep = $flixData['US_KEY'];
          break;
        case 'foto':
          include_once PATH_CLASS . '/CFotobox.php';
          $fb =& CFotobox::getInstance();
          $fotoData = $fb->fotoData($element_id);
          $element_rep = $fotoData['P_KEY'];
          break;
      }
      
      $forUserData = $u->find($for_user_id);
      $byUserData = $u->find($by_user_id);
      $um->addActivity($by_user_id, $return, 'newComment', $byUserData['U_USERNAME'], $forUserData['U_USERNAME'], $element_rep, $type);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   commentsForUser
  * Description
  *   Method to retrieve the comments for a user
  * Input
  *   user_id - id of user
  * Output
  *   array
  ******************************************************************************************
  */
  function commentsForUser($user_id = false, $type = false)
  {
    $sql = 'SELECT c_id AS C_ID, c_by_u_id AS C_BY_U_ID, c_for_u_id AS C_FOR_U_ID, c_element_id AS C_ELEMENT_ID, c_comment AS C_COMMENT, c_type AS C_TYPE, c_time AS C_TIME, u.u_username AS C_USERNAME, u2.u_username AS C_BY_USERNAME, up.up_value AS C_AVATAR, up2.up_value AS C_AVATAR_KEY '
         . 'FROM ((comments AS c LEFT JOIN users AS u ON u.u_id = c.c_for_u_id) '
         . 'LEFT JOIN users AS u2 ON u2.u_id = c.c_by_u_id '
         . "LEFT JOIN user_prefs AS up ON (c.c_by_u_id = up.up_u_id AND up.up_name = 'AVATAR')) "
         . "LEFT JOIN user_prefs AS up2 ON (c.c_by_u_id = up2.up_u_id AND up2.up_name = 'AVATAR_KEY') "
         . 'WHERE c_for_u_id = ' . $this->dbh->sql_safe($user_id) . ' '
         . "AND c_status = 'Active' ";
         
    if($type !== false)
    {
      $sql .= ' AND c_type = ' . $this->dbh->sql_safe($type) . ' ';
    }
    
    $sql .= 'ORDER BY C_ID DESC';
         
    return $this->dbh->query_all($sql);
  }
  
 /*
  *******************************************************************************************
  * Name
  *   delete
  * Description
  *   Method to delete a comment
  * Input
  *   c_id - comment to delete
  * Output
  *   array
  ******************************************************************************************
  */
  function delete($c_id = false)
  {
    if($c_id !== false)
    {
      $sql = 'UPDATE comments '
           . "SET c_status = 'Deleted' "
           . 'WHERE c_id = ' . intval($c_id) . ' ';
           
      $this->dbh->execute($sql);
    }
    
    return;
    
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
