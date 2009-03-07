<?php

/*******************************************************************************************
 * Name:        CBoard.php
 * Class Name:  CBoard
 *------------------------------------------------------------------------------------------
 * Mod History: Kevin Hornschemeier   01/22/2006
 *------------------------------------------------------------------------------------------
 * Class to handle the dicussion board
 * 
 *******************************************************************************************/

class CBoard
{
  
  var $m_color;
  var $m_count;
  
  
  /*******************************************************************************************
  * Name
  *   getInstance
  *
  * Description
  *   Static method to invoke this class
  *
  * Input
  *   
  * Output
  *   Class object
  *******************************************************************************************/
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;
    
    // if this is the first time the class is instantiated
    // then create and return the class
    // otherwise, just return the current instance
    if($inst === null)
    {
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
  /*******************************************************************************************
  * Name
  *   CBoard
  *
  * Description
  *   Class constructor (initializes variables)
  *
  * Input
  *   
  * Output
  *
  *******************************************************************************************/
  function CBoard()
  {
  }
  
  
  /*******************************************************************************************
  * Name
  *   categories
  *
  * Description
  *   Retrieves all the visible categories
  *
  * Input
  *   
  * Output
  *   array
  *******************************************************************************************/
  function categories()
  {    
    $return = array();
    
    $sql = 'SELECT bc.bc_id AS BC_ID, bc.bc_title AS BC_TITLE, bc.bc_active AS BC_ACTIVE'
         . ' FROM board_category AS bc'
         . " WHERE bc.bc_visible = 'Y' "
         . ' ORDER BY bc.bc_order';
         
    $result = $this->dbh->query_all($sql);
    
    $return = $result;
    
    return $return;
  }


  /*******************************************************************************************
  * Name
  *   boardsByCategory
  *
  * Description
  *   Retrieves all the visible boards for a specified category
  *
  * Input
  *   $cID      int     (category_id)
  *
  * Output
  *   array
  *******************************************************************************************/
  function boardsByCategory( $cID )
  {    
    $return = array();
    
    $sql = 'SELECT b.b_id AS B_ID, b.b_title AS B_TITLE, b.b_description AS B_DESCRIPTION, UNIX_TIMESTAMP(b.b_dateCreated) AS B_DATECREATED, b.b_u_id AS B_UID, b.b_bp_id AS B_BPID, b.b_active AS B_ACTIVE'
         . ' FROM board AS b'
         . " WHERE b.b_bc_id = '" . $cID . "'"
         . " AND b.b_visible = 'Y'"
         . ' ORDER BY b.b_order';
         
    $result = $this->dbh->query_all($sql);
    
    $return = $result;
    
    return $return;
  }
  
  
  /*******************************************************************************************
  * Name
  *   board
  *
  * Description
  *   Retrieves information about a specific board
  *
  * Input
  *   $bID      int     (board_id)
  *
  * Output
  *   array
  *******************************************************************************************/
  function board( $bID )
  {    
    $return = array();
    
    $sql = 'SELECT b.b_id AS B_ID, b.b_bc_id AS B_CATEGORYID, b.b_title AS B_TITLE, b.b_description AS B_DESCRIPTION, UNIX_TIMESTAMP(b.b_dateCreated) AS B_DATECREATED, '
         . ' b.b_bp_id AS B_LASTPOSTID, b.b_u_id AS B_UID, b.b_active AS B_ACTIVE'
         . ' FROM board AS b'
         . " WHERE b.b_id = '" . $bID . "'"
         . " AND b.b_visible = 'Y'";
         
    $result = $this->dbh->query_first($sql);
    
    $return = $result;
    
    return $return;
  }
  
  
  /*******************************************************************************************
  * Name
  *   posts
  *
  * Description
  *   Retrieves all the posts from a board
  *
  * Input
  *   $bID      int     (board_id)
  *
  * Output
  *   array
  *******************************************************************************************/
  function posts( $bID )
  {    
    $return = array();
    
    $sql = 'SELECT bp.bp_id AS BP_ID, bp.bp_b_id AS BP_BID, bp.bp_u_id AS BP_UID, bp.bp_title AS BP_TITLE, UNIX_TIMESTAMP(bp.bp_dateCreated) AS BP_DATECREATED, bp.bp_replies AS BP_REPLIES, '
         . ' bp.bp_lastPostID AS BP_LASTPOSTID, bp.bp_views AS BP_VIEWS, bp.bp_lastViewID AS BP_LASTVIEWID, bp.bp_sticky AS BP_STICKY, bp.bp_active AS BP_ACTIVE'
         . ' FROM board_post AS bp'
         . " WHERE bp.bp_visible = 'Y'"
         . " AND bp.bp_parentID = '0'"
         . " AND bp.bp_b_id = '" . $bID . "'"
         . ' ORDER BY bp.bp_STICKY ASC, bp.bp_dateCreated DESC';
         
    $result = $this->dbh->query_all($sql);
    
    $return = $result;
    
    return $return;
  }

  /*******************************************************************************************
  * Name
  *   postsByPages
  *
  * Description
  *   Retrieves all the posts from a board by pages
  *
  * Input
  *   $bID      int     (board_id)
  *   $offset   int
  *   $limit    int
  *
  * Output
  *   array
  *******************************************************************************************/
  function postsByPages( $bID, $offset = 0, $limit = 20 )
  {    
    $return = array();
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS bp.bp_id AS BP_ID, bp.bp_b_id AS BP_BID, bp.bp_u_id AS BP_UID, bp.bp_title AS BP_TITLE, UNIX_TIMESTAMP(bp.bp_dateCreated) AS BP_DATECREATED, bp.bp_replies AS BP_REPLIES, '
         . ' bp.bp_lastPostID AS BP_LASTPOSTID, bp.bp_views AS BP_VIEWS, bp.bp_lastViewID AS BP_LASTVIEWID, bp.bp_sticky AS BP_STICKY, bp.bp_active AS BP_ACTIVE'
         . ' FROM board_post AS bp'
         . " WHERE bp.bp_visible = 'Y'"
         . " AND bp.bp_parentID = '0'"
         . " AND bp.bp_b_id = '" . $bID . "'"
         . ' ORDER BY bp.bp_STICKY ASC, bp.bp_dateCreated DESC'
         . ' LIMIT ' . $limit . ''
         . ' OFFSET ' . $offset . '';
         
    $result = $this->dbh->query_all($sql);
    
    $return = $result;
    
    return $return;
  }
  
  
  /*******************************************************************************************
  * Name
  *   singlePost
  *
  * Description
  *   Retrieves information about a specific post
  *
  * Input
  *   $pID      int     (post_id)
  *
  * Output
  *   array
  *******************************************************************************************/
  function singlePost( $pID )
  {    
    $return = array();
    
    $sql = 'SELECT bp.bp_id AS BP_ID, bp.bp_b_id AS BP_BID, bp.bp_u_id AS BP_UID, bp.bp_title AS BP_TITLE, UNIX_TIMESTAMP(bp.bp_dateCreated) AS BP_DATECREATED, bp.bp_replies AS BP_REPLIES, '
         . ' bp.bp_lastPostID AS BP_LASTPOSTID, bp.bp_views AS BP_VIEWS, bp.bp_lastViewID AS BP_LASTVIEWID, bp.bp_sticky AS BP_STICKY, bp.bp_active AS BP_ACTIVE, bp.bp_parentID AS BP_PARENTID, '
         . ' bpc.bpc_content AS BP_CONTENT'
         . ' FROM board_post AS bp, board_postcontent as bpc'
         . ' WHERE bp.bp_id = bpc.bpc_bp_id'
         . " AND bp.bp_visible = 'Y'"
         . " AND bp.bp_id = '" . $pID . "'";
         
    $result = $this->dbh->query_first($sql);
    
    $return = $result;
    
    return $return;
  }
  
  
  /*******************************************************************************************
  * Name
  *   post
  *
  * Description
  *   Retrieves information about a specific post and all of its replies
  *
  * Input
  *   $pID      int     (post_id)
  *
  * Output
  *   array
  *******************************************************************************************/
  function post( $pID )
  {    
    $return = array();
    
    $sql = 'SELECT bp.bp_id AS BP_ID, bp.bp_b_id AS BP_BID, bp.bp_u_id AS BP_UID, bp.bp_title AS BP_TITLE, UNIX_TIMESTAMP(bp.bp_dateCreated) AS BP_DATECREATED, bp.bp_replies AS BP_REPLIES, '
         . ' bp.bp_lastPostID AS BP_LASTPOSTID, bp.bp_views AS BP_VIEWS, bp.bp_lastViewID AS BP_LASTVIEWID, bp.bp_sticky AS BP_STICKY, bp.bp_active AS BP_ACTIVE, bp.bp_parentID AS BP_PARENTID, '
         . ' bpc.bpc_content AS BP_CONTENT'
         . ' FROM board_post AS bp, board_postcontent as bpc'
         . ' WHERE bp.bp_id = bpc.bpc_bp_id'
         . " AND bp.bp_visible = 'Y'"
         . " AND (bp.bp_id = '" . $pID . "'"
         . " OR bp.bp_parentID = '" . $pID . "')"
         . ' ORDER BY bp.bp_parentID ASC, bp.bp_dateCreated ASC';
         
    $result = $this->dbh->query_all($sql);
    
    $return = $result;
    
    return $return;
  }
  
  
  /*******************************************************************************************
  * Name
  *   postByPage
  *
  * Description
  *   Retrieves information about a specific post and all of its replies
  *
  * Input
  *   $pID      int     (post_id)
  *   $offset   int
  *   $limit    int
  *
  * Output
  *   array
  *******************************************************************************************/
  function postByPage( $pID, $offset = 0, $limit = 10 )
  {    
    $offset = intval( $offset );
    $limit = intval( $limit );
    
    $return = array();
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS bp.bp_id AS BP_ID, bp.bp_b_id AS BP_BID, bp.bp_u_id AS BP_UID, bp.bp_title AS BP_TITLE, UNIX_TIMESTAMP(bp.bp_dateCreated) AS BP_DATECREATED, bp.bp_replies AS BP_REPLIES, '
         . ' bp.bp_lastPostID AS BP_LASTPOSTID, bp.bp_views AS BP_VIEWS, bp.bp_lastViewID AS BP_LASTVIEWID, bp.bp_sticky AS BP_STICKY, bp.bp_active AS BP_ACTIVE, bp.bp_parentID AS BP_PARENTID, '
         . ' bpc.bpc_content AS BP_CONTENT'
         . ' FROM board_post AS bp, board_postcontent as bpc'
         . ' WHERE bp.bp_id = bpc.bpc_bp_id'
         . " AND bp.bp_visible = 'Y'"
         . " AND (bp.bp_id = '" . $pID . "'"
         . " OR bp.bp_parentID = '" . $pID . "')"
         . ' ORDER BY bp.bp_parentID ASC, bp.bp_dateCreated ASC'
         . ' LIMIT ' . $limit . ''
         . ' OFFSET ' . $offset . '';
         
    $result = $this->dbh->query_all($sql);
    
    $return = $result;
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   countPosts
  *
  * Description
  *   Method to count posts for a board
  *
  * Input
  *   $board_id         int
  *
  * Output
  *   $return           int
  ******************************************************************************************
  */
  function countPosts($board_id = false)
  {
    $return = 0;
    if($board_id !== false)
    {
      $board_id = intval($board_id);
      
      $sql = 'SELECT COUNT(bp_id) AS _CNT FROM board_post WHERE bp_b_id = ' . $board_id . " AND bp_active = 'Y'";
      
      $result = $this->dbh->query_first($sql);
      
      $return = intval($result['_CNT']);
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   countTopics
  *
  * Description
  *   Method to count topics for a board
  *
  * Input
  *   $board_id         int
  *
  * Output
  *   $return           int
  ******************************************************************************************
  */
  function countTopics($board_id = false)
  {
    $return = 0;
    if($board_id !== false)
    {
      $board_id = intval($board_id);
      
      $sql = 'SELECT COUNT(bp_id) AS _CNT FROM board_post WHERE bp_b_id = ' . $board_id . " AND bp_parentID = 0 AND bp_active = 'Y'";
      
      $result = $this->dbh->query_first($sql);
      
      $return = intval($result['_CNT']);
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   addNewTopic
  *
  * Description
  *   Method to insert a new topic
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  *
  * Output
  *   $return           int/boolean   insert_id()/false
  ******************************************************************************************
  */
  function addNewTopic($data = false)
  {
    $return = false;
    
    if(is_array($data))
    {     
      //$data = $this->dbh->asql_safe($data);
      $sql  = 'INSERT INTO board_post(bp_b_id, bp_parentID, bp_u_id, bp_title, bp_dateCreated ) '
            . 'VALUES(' . intval($data['bp_b_id']) . ', 0, ' . intval($data['bp_u_id']) . ', ' . $this->dbh->sql_safe($data['bp_title']) . ', NOW()' . ')';
          
      $this->dbh->execute($sql);
      $bp_id = $this->dbh->insert_id();
      
      $sql = 'INSERT INTO board_postcontent(bpc_bp_id, bpc_content) '
           . 'VALUES(' . $bp_id . ', ' . $this->dbh->sql_safe($data['bpc_content']) . ')';
           
      $this->dbh->execute($sql);
      
      $sql = 'UPDATE board '
           . 'SET b_bp_id = ' . $bp_id . ' '
           . 'WHERE b_id = (SELECT bp_b_id FROM board_post WHERE bp_id = ' . $bp_id . ')';
           
      $this->dbh->execute($sql);
      
      $return = $bp_id;
      
      if(intval($data['bp_b_id']) >= 100)
      {
        $sql = 'INSERT INTO group_feed(gf_g_id, gf_u_id, gf_type, gf_type_id, gf_dateCreated, gf_date_id) '
           . "VALUES (" . $data['bp_b_id'] . ", " . $data['bp_u_id'] . ", 'Forum_post', " . $bp_id . ", NOW(), '" . date('ymd') . "') ";
           
        $this->dbh->execute($sql);
      }
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   adddReply
  *
  * Description
  *   Method to insert a new reply
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  *
  * Output
  *   $return           int/boolean   insert_id()/false
  ******************************************************************************************
  */
  function addReply($data = false)
  {
    $return = false;
    
    if(is_array($data))
    {     
      $data = $this->dbh->asql_safe($data);
      $sql  = 'INSERT INTO board_post(bp_b_id, bp_parentID, bp_u_id, bp_dateCreated) '
            . 'VALUES(' . $data['bp_b_id'] . ', ' . $data['bp_p_id'] . ', ' . $data['bp_u_id'] . ', NOW())';
          
      $this->dbh->execute($sql);
      $bp_id = $this->dbh->insert_id();
      
      $sql = 'INSERT INTO board_postcontent(bpc_bp_id, bpc_content) '
           . 'VALUES(' . $bp_id . ', ' . $data['bpc_content'] . ')';
           
      $this->dbh->execute($sql);
      $return = $bp_id;
      
      $sql = 'UPDATE board '
           . 'SET b_bp_id = ' . $bp_id . ' '
           . 'WHERE b_id = (SELECT bp_b_id FROM board_post WHERE bp_id = ' . $bp_id . ')';
           
      $this->dbh->execute($sql);
      
      $sql = 'UPDATE board_post '
           . 'SET bp_replies = bp_replies + 1,'
           . ' bp_lastPostID = ' . $bp_id . ' '
           . 'WHERE bp_id = ' . $data['bp_p_id'];
      
      $this->dbh->execute($sql);
      
    }
    
    return $return;
  }
  
  
  /*
  *******************************************************************************************
  * Name
  *   addView
  *
  * Description
  *   Method to insert a new view and update the view count
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  *
  * Output
  *   $return           int/boolean   insert_id()/false
  ******************************************************************************************
  */
  function addView($data = false)
  {
    $return = false;
       
    $data = $this->dbh->asql_safe($data);
    $sql  = 'INSERT INTO board_views(bv_bp_id, bv_u_id, bv_dateViewed) '
          . 'VALUES(' . $data['bv_bp_id'] . ', ' . intval($data['bv_u_id']) . ', NOW())';
          
    $this->dbh->execute($sql);
    $bv_id = $this->dbh->insert_id();
    $return = $bv_id;
      
    $sql = 'UPDATE board_post '
         . 'SET bp_views = bp_views + 1,'
         . ' bp_lastViewID = ' . $data['bv_bp_id'] . ' '
         . 'WHERE bp_id = ' . $data['bv_bp_id'];
      
    $this->dbh->execute($sql);
    
    return $return;
  }
  
  
  /*
  *******************************************************************************************
  * Name
  *   getNumberPosts
  *
  * Description
  *   Method to return the number of posts for a user
  *
  * Input
  *   $uid              string (user_id) 
  *
  * Output
  *   $return           int (count)
  ******************************************************************************************
  */
  function getNumberPosts( $uid = '' )
  { 
    $return = false;
    $uid = intval($uid);
    $sql = 'SELECT count(*) AS BP_COUNT '
         . 'FROM board_post '
         . 'WHERE bp_u_id = ' . $uid . '';
    
    $return = $this->dbh->query_first($sql);
    
    return $return;
  }
  
  
  
  /*
  *******************************************************************************************
  * Name
  *   _formatQuoting
  *
  * Description
  *   Method to format any [quote][/quote] tags
  *
  * Input
  *   $data             string
  *
  * Output
  *   $return           string
  ******************************************************************************************
  */
  function _formatQuoting( $data = '' )
  { 
    $this->m_count = 0;
    $this->m_color = 0;
    
    return preg_replace_callback('/\[quote\]|\[\/quote\]/', array(__CLASS__, '_quoting'), $data); 
  }
  
  
  /*
  *******************************************************************************************
  * Name
  *   _quoting
  *
  * Description
  *   Helping method to replace [quote] and [/quote]
  *
  * Input
  *   $matches          string
  *
  * Output
  *   $return           string
  ******************************************************************************************
  */
  function _quoting($matches)
  { 
    if($matches[0] == '[quote]')
    {
      $return = '<div style="border:solid white 1px; margin-right:30px; margin-top:7px; padding:7px;" class="bg_medium">';
      $this->m_count++;
      $this->m_color += 15;
    }
    else
    {
      $return = "</div>\n";
      $this->m_count--;
      
      if( $this->m_count == 0 )
      {
        $this->m_color = 0;
      }
    
    }
    
    return $return;
  }
  
  
  /*
  *******************************************************************************************
  * Name
  *   _formatItalic
  *
  * Description
  *   Method to format any [i][/i] tags
  *
  * Input
  *   $data             string
  *
  * Output
  *   $return           string
  ******************************************************************************************
  */
  function _formatItalic( $data = '' )
  { 
    return preg_replace_callback('/\[i\]|\[\/i\]/', array(__CLASS__, '_italic'), $data); 
  }
  
  
  /*
  *******************************************************************************************
  * Name
  *   _italic
  *
  * Description
  *   Helping method to replace [i] and [/i]
  *
  * Input
  *   $matches          string
  *
  * Output
  *   $return           string
  ******************************************************************************************
  */
  function _italic($matches)
  { 
    if($matches[0] == '[i]')
    {
      $return = '<div class="italic">';
    }
    else
    {
      $return = "</div>";
    }
    
    return $return;
  }
  
  
  /*
  *******************************************************************************************
  * Name
  *   parseContent
  *
  * Description
  *   Method to format any [photo], [slideshow], or [quote] tags
  *
  * Input
  *   $data             string
  *
  * Output
  *   $return           string
  ******************************************************************************************
  */
  function parseContent( $data )
  {
    $data = $this->_formatQuoting( $data );
    $data = $this->_formatItalic( $data );
    $data = preg_replace_callback(array('/\[(photo)\](.*?)\[\/photo\]/', '/\[(slideshow)\](.*?)\[\/slideshow\]/'), array(__CLASS__, '_ffSrc'), $data);
    return $data;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   _ffSrc
  *
  * Description
  *   Method to retrieve a foto/flix thumbnail src
  *
  * Input
  *   $matches          string
  *
  * Output
  *   $return           string
  ******************************************************************************************
  */
  function _ffSrc( $matches )
  {
    $return = $matches[1];
    
    switch( $matches[1] )
    {
      case 'photo':
        
        include_once PATH_CLASS . '/CFotobox.php';
    
        $fb = &CFotobox::getInstance();
        $matches[2] = trim( $matches[2] );
        
        $fotoData = $fb->fotoData( $matches[2] );
        if( $fotoData !== false )
        {
          $return = ' <a href="/handler/photo/' . $matches[2] . '/" target="_blank"><img src="' . PATH_FOTO . $fotoData['P_THUMB_PATH'] . '" width="75" height="75" vspace="3" border="0" class="border_white_2px" /></a>&nbsp;';
        }
        else 
        {
          $return = '&nbsp;<img src="' . PATH_FOTO . '/thumbnail/nofoto.jpg" width="75" height="75" border="0" class="border_white_2px" />&nbsp;';
        }
        
        break;
        
      case 'slideshow':
        
        include_once PATH_CLASS . '/CFlix.php';
        include_once PATH_CLASS . '/CFotobox.php';
    
        $fb = &CFotobox::getInstance();
        $f = &CFlix::getInstance();
        $matches[2] = trim( $matches[2] );
        
        $flixData = $f->search(array('KEY' => $matches[2]));
        $fotoURL = $flixData['US_PHOTO']['thumbnailPath_str'];
        //$theme = $f->template( $flixData['A_TEMPLATE'] );
        if( $fotoURL != '' )
        {
          $return = '<div style="padding-top:4px; padding-bottom:4px;"><div class="flix_border" style="float:left;"><a href="/handler/slideshow/' . $matches[2] . '/" target="_blank"><img src="' . PATH_FOTO . $fotoURL . '" width="75" height="75" border="0" /></a></div><div>Title: ' . $flixData['US_NAME'] . '<br />Photos: ' . $flixData['US_FOTO_COUNT'] . '<br />Views: ' . $flixData['US_VIEWS'] . '<br clear="all" /></div></div>';
        }
        else 
        {
          $return = '<img src="' . PATH_FOTO . '/thumbnail/nofoto.jpg" width="75" height="75" border="0" />';
        }
        
        break;
        
      //case 'i':
        
        //$return = '<div class="italic">' . $matches[2] . '</div>';
        //break;
        
    }
    
    return $return; 
    
  }
  
  /*******************************************************************************************
  * Name
  *   createGroupBoard
  *
  * Description
  *   Creates a board for a group
  *
  * Input
  *   $g_id - group id
  *   $g_u_id - group creator
  *   $g_name - group name
  *   $g_description - group description
  *
  * Output
  *
  *******************************************************************************************/
  function createGroupBoard($g_id, $g_u_id, $g_name, $g_description)
  {
    $g_id = $this->dbh->sql_safe($g_id);
    $g_u_id = $this->dbh->sql_safe($g_u_id);
    $g_name = $this->dbh->sql_safe($g_name);
    $g_description = $this->dbh->sql_safe($g_description);
    
    $sql = 'INSERT INTO board (b_id, b_bc_id, b_u_id, b_title, b_description, b_dateCreated, b_bp_id, b_order, b_active, b_visible) '
         . "VALUES (" . $g_id . ", NULL, " . $g_u_id . ", " . $g_name . ", " . $g_description . ", NOW(), NULL, 0, 'Y', 'Y') ";
    
    $this->dbh->execute($sql);
    return;
  }
  
}
?>