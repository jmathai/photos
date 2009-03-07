<?php
 /*
  *******************************************************************************************
  * Name:  CForum.php
  *
  * Class to handle forum read functions
  *
  * Usage:
  * 
  ******************************************************************************************
  */
  
class CForum {
  function forums($group_id = false)
  {
    $return = array();
    if($group_id !== false)
    {
      $group_id = $this->dbh->sql_safe($group_id);
      $sql  = 'SELECT f_id AS F_ID, f_g_id AS F_G_ID, f_title AS F_TITLE, f_description AS F_DESCRIPTION, f_displayOrder AS F_DISPLAYORDER, '
            . 'UNIX_TIMESTAMP(f_lastPostTime) AS F_LASTPOSTTIME, f_lastPoster AS F_LASTPOSTER, f_lastThreadTitle AS F_LASTTHREADTITLE, '
            . 'f_lastThreadId AS F_LASTTHREADID, f_postCount AS F_POSTCOUNT, f_threadCount AS F_THREADCOUNT, UNIX_TIMESTAMP(f_dateModified) AS F_DATEMODIFIED, '
            . 'UNIX_TIMESTAMP(f_dateCreated) AS F_DATECREATED '
            . 'FROM forums '
            . 'WHERE f_g_id = ' . $group_id;
      
      $rs = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[] = $data;
      }
    }
    
    return $return;
  }
  
  //function forumData($forum_id = false)
  function forumData($group_id = false)
  {
    $return = array();
    if($group_id !== false)
    {
      $forum_id = $this->dbh->sql_safe($group_id);
      $sql  = 'SELECT f_id AS F_ID, f_g_id AS F_G_ID, f_title AS F_TITLE, f_description AS F_DESCRIPTION, f_displayOrder AS F_DISPLAYORDER, '
            . 'UNIX_TIMESTAMP(f_lastPostTime) AS F_LASTPOSTTIME, f_lastPoster AS F_LASTPOSTER, f_lastThreadTitle AS F_LASTTHREADTITLE, '
            . 'f_lastThreadId AS F_LASTTHREADID, f_postCount AS F_POSTCOUNT, f_threadCount AS F_THREADCOUNT, UNIX_TIMESTAMP(f_dateModified) AS F_DATEMODIFIED, '
            . 'UNIX_TIMESTAMP(f_dateCreated) AS F_DATECREATED '
            . 'FROM forums '
            . 'WHERE f_g_id = ' . $group_id;
      
      $return = $this->dbh->fetch_assoc(
                  $this->dbh->query($sql)
                );
    }
    
    return $return;
  }
  
  //function threads($forum_id = false)
  function threads($group_id = false)
  {
    $return = array();
    if($group_id !== false)
    {
      $forum_id = $this->dbh->sql_safe($group_id);
      $sql  = 'SELECT ft_id AS FT_ID, ft_f_id AS FT_F_ID, ft_title AS FT_TITLE, UNIX_TIMESTAMP(ft_lastPostTime) AS FT_LASTPOSTTIME, ft_lastPoster AS FT_LASTPOSTER, '
            . 'ft_replyCount AS FT_REPLYCOUNT, ft_viewCount AS FT_VIEWCOUNT, ft_open AS FT_OPEN, ft_sticky AS FT_STICKY, ft_visible AS FT_VISIBLE, '
            . 'UNIX_TIMESTAMP(ft_dateModified) AS FT_DATEMODIFIED, UNIX_TIMESTAMP(ft_dateCreated) AS FT_DATECREATED '
            . 'FROM forum_threads '
            . 'WHERE ft_g_id = ' . $group_id . ' '
            . 'ORDER BY ft_dateModified DESC';
      
      $rs = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[] = $data;
      }
    }
    
    return $return;
  }
  
  function threadData($thread_id = false)
  {
    $thread_id = $this->dbh->sql_safe($thread_id);
    $sql  = 'SELECT ft_id AS FT_ID, ft_f_id AS FT_F_ID, ft_title AS FT_TITLE, UNIX_TIMESTAMP(ft_lastPostTime) AS FT_LASTPOSTTIME, ft_lastPoster AS FT_LASTPOSTER, '
          . 'ft_replyCount AS FT_REPLYCOUNT, ft_viewCount AS FT_VIEWCOUNT, ft_open AS FT_OPEN, ft_sticky AS FT_STICKY, ft_visible AS FT_VISIBLE, '
          . 'UNIX_TIMESTAMP(ft_dateModified) AS FT_DATEMODIFIED, UNIX_TIMESTAMP(ft_dateCreated) AS FT_DATECREATED '
          . 'FROM forum_threads '
          . 'WHERE ft_id = ' . $thread_id;
    
    return $this->dbh->query_first($sql);
  }
  
  function posts($thread_id = false)
  {
    $return = array();
    if($thread_id !== false)
    {
      $thread_id = $this->dbh->sql_safe($thread_id);
      $sql  = 'SELECT fp_id AS FP_ID, fp_ft_id AS FP_FT_ID, fp_f_id AS FP_F_ID, fp_username AS FP_USERNAME, fp_u_id AS FP_U_ID, fp_title AS FP_TITLE, '
            . 'fp_post AS FP_POST, fp_ipAddress AS FP_IPADDRESS, UNIX_TIMESTAMP(fp_dateModified) AS FP_DATEMODIFIED, UNIX_TIMESTAMP(fp_dateCreated) AS FP_DATECREATED '
            . 'FROM forum_posts '
            . 'WHERE fp_ft_id = ' . $thread_id;
      
      $rs = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[] = $data;
      }
    }
    
    return $return;
  }
  
  function postData($post_id = false, $user_id = false)
  {
    $return = array();
    if($post_id !== false)
    {
      $post_id = $this->dbh->sql_safe($post_id);
      $sql  = 'SELECT fp_id AS FP_ID, fp_ft_id AS FP_FT_ID, fp_f_id AS FP_F_ID, fp_username AS FP_USERNAME, fp_u_id AS FP_U_ID, fp_title AS FP_TITLE, '
            . 'fp_post AS FP_POST, fp_ipAddress AS FP_IPADDRESS, UNIX_TIMESTAMP(fp_dateModified) AS FP_DATEMODIFIED, UNIX_TIMESTAMP(fp_dateCreated) AS FP_DATECREATED '
            . 'FROM forum_posts '
            . 'WHERE fp_id = ' . $post_id;
      if($user_id !== false)
      {
        $user_id = $this->dbh->sql_safe($user_id);
        $sql .= ' AND fp_u_id = ' . $user_id;
      }
      
      $return = $this->dbh->fetch_assoc(
                  $this->dbh->query($sql)
                );
    }
    
    return $return;
  }
  
  function countPosts($group_id = false, $user_id = false)
  {
    if($group_id !== false)
    {
      $group_id = $this->dbh->sql_safe($group_id);
      $sql  = 'SELECT COUNT(fp_id) AS _COUNT '
            . 'FROM forum_posts '
            . 'WHERE fp_g_id = ' . $group_id;
      if($user_id !== false)
      {
        $user_id = $this->dbh->sql_safe($user_id);
        $sql .= ' AND fp_u_id = ' . $user_id;
      }
      
      $ar = $this->dbh->fetch_assoc(
              $this->dbh->query($sql)
            );
      
      return $ar['_COUNT'];
    }
    else
    {
      return 0;
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
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
  function CForum()
  {
  }
}
?>
