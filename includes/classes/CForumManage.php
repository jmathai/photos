<?php
 /*
  *******************************************************************************************
  * Name:  CForumManage.php
  *
  * Class to handle forum write functions
  *
  * Usage:
  * 
  ******************************************************************************************
  */
class CForumManage {
  
  function addTopic($data = false)
  {
    if(is_array($data))
    {
      include_once PATH_INCLUDE . '/functions.php';
      $data['f_title'] = sanitize($data['f_title']);
      $data['f_description'] = sanitize($data['f_description']);
      $dataSafe = $this->dbh->asql_safe($data);
      
      $keys = array_keys($dataSafe);
      
      $sql  = 'INSERT INTO forums(' . implode(", ", $keys) . ', f_dateModified, f_dateCreated) ' . LF
            . 'VALUES(' . implode(", ", $dataSafe) . ', NOW(), NOW())';
      
      $this->dbh->execute($sql);
      
      return $this->dbh->insert_id();
    }
    else
    {
      return false;
    }
  }
  
  function addThread($data = false)
  {
    if(is_array($data))
    {
      include_once PATH_INCLUDE . '/functions.php';
      $data['ft_title'] = sanitize($data['ft_title']);
      $dataSafe = $this->dbh->asql_safe($data);
      
      $keys = array_keys($dataSafe);
      
      $sql  = 'INSERT INTO forum_threads(' . implode(", ", $keys) . ', ft_dateModified, ft_dateCreated) ' . LF
            . 'VALUES(' . implode(", ", $dataSafe) . ', NOW(), NOW())';
      
      $this->dbh->execute($sql);
      $insert_id = $this->dbh->insert_id();
      
      $this->dbh->execute('UPDATE forums SET f_lastThreadId = ' . $insert_id . ', f_lastPoster = ' . $dataSafe['ft_lastPoster'] . ', f_lastPostTime = NOW() WHERE f_g_id = ' . $dataSafe['ft_g_id']);
      
      return $insert_id;
    }
    else
    {
      return false;
    }
  }
  
  function addPost($data = false)
  {
    if(is_array($data))
    {
      include_once PATH_INCLUDE . '/functions.php';
      $data['fp_title'] = sanitize($data['fp_title']);
      $data['fp_post'] = sanitize($data['fp_post']);
      
      $dataSafe = $this->dbh->asql_safe($data);
      
      $keys = array_keys($dataSafe);
      
      $this->dbh->execute('UPDATE forum_threads SET ft_lastPoster = ' . $dataSafe['fp_username'] . ', ft_lastPostTime = NOW() WHERE ft_g_id = ' . $dataSafe['fp_g_id']);
      
      $sql  = 'INSERT INTO forum_posts(' . implode(", ", $keys) . ', fp_dateModified, fp_dateCreated) ' . LF
            . 'VALUES(' . implode(", ", $dataSafe) . ', NOW(), NOW())';
      
      $this->dbh->execute($sql);
      
      return $this->dbh->insert_id();
    }
    else
    {
      return false;
    }
  }
  
  function incrementView($thread_id = false)
  {
    if($thread_id !== false)
    {
      $sql = 'UPDATE forum_threads SET ft_viewCount = ft_viewCount + 1 WHERE ft_id = ' . $this->dbh->sql_safe($thread_id);
      
      $this->dbh->execute($sql);
      
      return true;
    }
    else
    {
      return false;
    }
  }
  
  function incrementReply($thread_id = false)
  {
    if($thread_id !== false)
    {
      $sql = 'UPDATE forum_threads SET ft_replyCount = ft_replyCount + 1 WHERE ft_id = ' . $this->dbh->sql_safe($thread_id);
      
      $this->dbh->execute($sql);
      
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
  
  function CForumManage()
  {
  }
}
?>
