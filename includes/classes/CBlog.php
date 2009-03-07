<?php
 /*
  *******************************************************************************************
  * Name:  CBlog.php
  *
  * General class for user blogs.
  *
  * Usage:
  *   include_once('CBlog.php');
  *   $blog = CBlog::getInstance();
  *   $blog_entries = $blog->entries($user_id);
  * 
  ******************************************************************************************
  */
class CBlog
{
 /*
  *******************************************************************************************
  * Name
  *   entries
  * Description
  *   Get entries for a user's blog
  *
  * Input (one of the following combinations)
  *   $userId                 int   (user_id)
  *   $limit (optional)       int
  *   $offset (optional)      int
  * Output
  *   array
  ******************************************************************************************
  */
  function entries($userId = false, $limit = false, $offset = false)
  {
    $retval = array();
    if($userId !== false)
    {
      $userId = intval($userId);
      $sql = 'SELECT ube_id AS B_ID, ube_u_id AS B_U_ID, ube_subject AS B_SUBJECT, ube_body AS B_BODY, '
           . 'ube_permaLink AS B_PERMALINK, UNIX_TIMESTAMP(ube_datePosted) AS B_DATEPOSTED, ube_comments AS B_COMMENTS '
           . 'FROM user_blog_entries '
           . "WHERE ube_u_id = {$userId} AND ube_status = 'Active' "
           . 'ORDER BY ube_datePosted DESC ';
      if($limit !== false)
      {
        $sql .= 'LIMIT ' . intval($limit) . ' ';
      }
      
      if($offset !== false)
      {
        $sql .= 'OFFSET ' . intval($offset) . ' ';
      }
      
      $retval = $this->dbh->query_all($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   entry
  * Description
  *   Get entry for a user's blog
  *
  * Input (one of the following combinations)
  *   $userId                 int   (user_id)
  *   $entryId                int   (user_id)
  * Output
  *   array
  ******************************************************************************************
  */
  function entry($userId = false, $entryId = false)
  {
    $retval = array();
    if($userId !== false && $entryId !== false)
    {
      $userId = intval($userId);
      $entryId = intval($entryId);
      $sql = 'SELECT ube_id AS B_ID, ube_u_id AS B_U_ID, ube_subject AS B_SUBJECT, ube_body AS B_BODY, '
           . 'ube_permaLink AS B_PERMALINK, UNIX_TIMESTAMP(ube_datePosted) AS B_DATEPOSTED, ube_comments AS B_COMMENTS '
           . 'FROM user_blog_entries '
           . "WHERE ube_id = {$entryId} AND ube_u_id = {$userId} AND ube_status = 'Active' ";
      
      $retval = $this->dbh->query_first($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   incrementComment
  * Description
  *   increment the count of comments for a blog entry
  *
  * Input (one of the following combinations)
  *   $entryId                int   (user_id)
  * Output
  *   bool
  ******************************************************************************************
  */
  function incrementComment($entryId = false)
  {
    $retval = false;
    if($entryId !== false)
    {
      $sql = 'UPDATE user_blog_entries SET ube_comments = (ube_comments+1) WHERE ube_id = ' . intval($entryId);
      $this->dbh->execute($sql);
      $retval = true;
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   add
  * Description
  *   Add an entry to a user's blog
  *
  * Input (one of the following combinations)
  *   $data                 array
  * Output
  *   int (entry id)
  ******************************************************************************************
  */
  function add($data = false)
  {
    $retval = 0;
    if(isset($data['ube_u_id']))
    {
      $dataSafe = $this->dbh->asql_safe($data);
      $keys = array_keys($data);
      
      $sql  = 'INSERT INTO user_blog_entries(' . implode(', ', $keys) . ', ube_dateCreated, ube_dateModified) '
            . 'VALUES(' . implode(', ', $dataSafe) . ', NOW(), NOW())';
            
      $this->dbh->execute($sql);
      
      $retval = $this->dbh->insert_id();
      
      $permaLink = $this->dbh->sql_safe(preg_replace('/\W+/', '_', trim($data['ube_subject'])));
      
      $sql  = "UPDATE user_blog_entries SET ube_permaLink = {$permaLink} WHERE ube_id = {$retval}";
      $this->dbh->execute($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   update
  * Description
  *   Update an entry to a user's blog
  *
  * Input (one of the following combinations)
  *   $data                 array
  * Output
  *   boolean
  ******************************************************************************************
  */
  function update($data)
  {
    if(array_key_exists('ube_id', $data) && array_key_exists('ube_u_id', $data))
    {
      $user_id = $data['u_id'];
      $data = $this->dbh->asql_safe($data);
      
      $sql = 'UPDATE user_blog_entries SET ';
      
      foreach($data as $k => $v)
      {
        $sql .= $k .' = ' . $v . ', ';
      }
      
      $sql  .=  'ube_dateModified = Now() '
            .   'WHERE ube_id = ' . $data['ube_id'] . ' AND ube_u_id = ' . $data['ube_u_id'];
      
      $this->dbh->execute($sql);
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   update
  * Description
  *   Update an entry to a user's blog
  *
  * Input (one of the following combinations)
  *   $data                 array
  * Output
  *   boolean
  ******************************************************************************************
  */
  function delete($userId = false, $entryId = false)
  {
    $retval = false;
    
    if($userId !== false && $entryId !== false)
    {
      $userId = intval($userId);
      $entryId= intval($entryId);
      
      $sql = "UPDATE user_blog_entries SET ube_status = 'Deleted' WHERE ube_id = {$entryId} AND ube_u_id = {$userId}";
      
      $this->dbh->execute($sql);
      
      $retval = true;
    }
    
    return $retval;
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
  *   CUser
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   Boolean
  ******************************************************************************************
  */
  function CBlog()
  {
  }
}
?>