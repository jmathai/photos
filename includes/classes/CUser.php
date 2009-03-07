<?php
 /*
  *******************************************************************************************
  * Name:  CUser.php
  *
  * General class for user interaction.
  * This class performs read-only functions on the database.
  *
  * Usage:
  *   include_once('CUser.php');
  *   $user = CUser::getInstance();
  *   $user_data = $user->find($user_id);
  * 
  ******************************************************************************************
  */
class CUser
{
 /*
  *******************************************************************************************
  * Name
  *   find
  * Description
  *   Method to retrieve ACTIVE user's data
  *   Use CUser::inactive for non ACTIVE users
  *
  * Input (one of the following combinations)
  *   $id                 int   (user_id)
  *   $sess_id            str   (session_id)
  *   $email              str   (email)
  *   $username, password str   (username, password)
  * Output
  *   array
  ******************************************************************************************
  */
  function find()
  {
    $dateExpires = $this->dbh->sql_safe(date('Y-m-d', NOW));
    if(func_num_args() == 1)
    {
      $arg0 = func_get_arg(0);
      if(is_numeric($arg0)) // userId
      {
        $user_id = $arg0;
        $user_id = $this->dbh->sql_safe($user_id);
        $sql  = 'SELECT u.u_id AS U_ID, u.u_parentId AS U_PARENTID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, u.u_isTrial AS U_ISTRIAL '
              . 'FROM users AS u '
              . 'WHERE u.u_id = ' . $user_id . " AND u.u_status = 'Active' AND u.u_dateExpires >= {$dateExpires}";
        //echo '<!-- ' . $sql . ' -->';
        $rs = $this->dbh->query($sql);
      }
      else
      if(strlen($arg0) == 32 && strstr($arg0, '@') === false) // userKey
      {
        $key = $this->dbh->sql_safe($arg0);
        $sql  = 'SELECT u.u_id AS U_ID, u.u_parentId AS U_PARENTID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, u.u_isTrial AS U_ISTRIAL '
              . 'FROM users AS u '
              . 'WHERE u.u_key = ' . $key . " AND u.u_status = 'Active' AND u.u_dateExpires >= {$dateExpires}";
        $rs = $this->dbh->query($sql);
      }
      else
      if(strlen($arg0) == 13 && strstr($arg0, '@') === false) // sessionHash
      {
        $sess_hash = $this->dbh->sql_safe($arg0);
        $sql  = 'SELECT u.u_id AS U_ID, u.u_parentId AS U_PARENTID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, u.u_isTrial AS U_ISTRIAL '
              . 'FROM users AS u, user_session AS us '
              . 'WHERE u.u_id = us.us_ud_id AND us.us_hash = ' . $sess_hash . " AND u.u_status = 'Active' AND u.u_dateExpires >= {$dateExpires}";
        $rs = $this->dbh->query($sql);
      }
      else
      if(strstr($arg0, '@') !== false) // emailAddress
      {
        $email= $this->dbh->sql_safe($arg0);
        $sql  = 'SELECT u.u_id AS U_ID, u.u_parentId AS U_PARENTID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, u.u_isTrial AS U_ISTRIAL '
              . 'FROM users AS u '
              . 'WHERE u.u_email = ' . $email . " AND u.u_status = 'Active' AND u.u_dateExpires >= {$dateExpires}";
        $rs = $this->dbh->query($sql);
      }
      else
      {
        return false;
      }
      
      if($this->dbh->num_rows($rs) == 1)
      {
        return $this->dbh->fetch_assoc($rs);
      }
      else
      {
        return false;
      }
    }
    else
    if(func_num_args() == 2) // username, password
    {
      $username = $this->dbh->sql_safe(func_get_arg(0));
      $password = $this->dbh->sql_safe($this->_encrypt(func_get_arg(1)));
      
      $sql  = 'SELECT u.u_id AS U_ID, u.u_parentId AS U_PARENTID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, u.u_isTrial AS U_ISTRIAL '
            . 'FROM users AS u '
            . 'WHERE u.u_username = ' . $username . ' AND u.u_password = ' . $password . " AND u.u_status = 'Active' AND u.u_dateExpires >= {$dateExpires}";
      $rs = $this->dbh->query($sql);
      
      if($this->dbh->num_rows($rs) == 1)
      {
        return $this->dbh->fetch_assoc($rs);
      }
      else
      {
        return false;
      }
    }
    else 
    if(func_num_args() == 3) // username, password (expired)
    {
      $username = $this->dbh->sql_safe(func_get_arg(0));
      $password = $this->dbh->sql_safe($this->_encrypt(func_get_arg(1)));
      
      $sql  = 'SELECT u.u_id AS U_ID, u.u_parentId AS U_PARENTID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, u.u_isTrial AS U_ISTRIAL '
            . 'FROM users AS u '
            . 'WHERE u.u_username = ' . $username . ' AND u.u_password = ' . $password . " AND (u.u_status = 'Active' OR u.u_status = 'Expired') ";
      
      $rs = $this->dbh->query($sql);
      
      if($this->dbh->num_rows($rs) == 1)
      {
        return $this->dbh->fetch_assoc($rs);
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   findTemp
  * Description
  *   Method to retrieve TEMP user's data
  *
  * Input (one of the following combinations)
  *   $key			str   (username, password)
  * Output
  *   array
  ******************************************************************************************
  */
  function findIncomplete()
  {
  	$arg0 = func_get_arg(0);
  	if(strlen($arg0) == 32 && strstr($arg0, '@') === false) // userKey
    {
      $key = $this->dbh->sql_safe($arg0);
      $sql  = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_password AS U_PASSWORD, u.u_email AS U_EMAIL, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_secret AS U_SECRET, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED '
            . 'FROM user_incompletes AS u '
            . 'WHERE u.u_key = ' . $key . ' ';
      $rs = $this->dbh->query($sql);
      
      if($this->dbh->num_rows($rs) == 1)
      {
        return $this->dbh->fetch_assoc($rs);
      }
      else
      {
        return false;
      }
    }
    else 
    {
    	return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   checkTemp
  * Description
  *   Method to check against incomplete user table
  *
  * Input (one of the following combinations)
  *   $email              str   (email)
  * Output
  *   array
  ******************************************************************************************
  */
  function checkIncomplete()
  {
  	$arg0 = func_get_arg(0);
  	if(strstr($arg0, '@') !== false) // emailAddress
    {
      $email= $this->dbh->sql_safe($arg0);
      $sql  = 'SELECT u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_secret AS U_SECRET, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED '
            . 'FROM user_incompletes AS u '
            . 'WHERE u.u_email = ' . $email . ' ';
      $rs = $this->dbh->query($sql);
      
      if($this->dbh->num_rows($rs) == 1)
      {
        return $this->dbh->fetch_assoc($rs);
      }
      else
      {
        return false;
      }
    }
    else 
    {
    	return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   countFriends
  * Description
  *   Method to count the number of friends a user has
  *
  * Output
  *   int
  ******************************************************************************************
  */
  function countFriends($userId = false)
  {
    $retval = 0;
    if($userId !== false)
    {
      $sql = 'SELECT COUNT(*) AS CNT FROM user_friends WHERE uf_u_id = ' . intval($userId) . " AND uf_status = 'Confirmed'";
      $rs = $this->dbh->query_first($sql);
      $retval = intval($rs['CNT']);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getFriends
  * Description
  *   Method to get a list of friends for a user
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function getFriends($userId = false, $status = false)
  {
    $retval = false;
    
    if($userId !== false)
    {
      $userId = intval($userId);
      
      $sql = 'SELECT u.u_id AS U_ID, u.u_username AS U_USERNAME, up_value AS U_AVATAR '
           . 'FROM (users AS u INNER JOIN user_friends AS uf ON u_id = uf_friendId) '
           . "LEFT JOIN user_prefs AS up ON u.u_id = up.up_u_id AND up_name = 'AVATAR' "
           . 'WHERE uf.uf_u_id = ' . $userId . ' ';
      
      if($status !== false)
      {
        $status = $this->dbh->sql_safe($status);
        $sql .= 'AND uf_status = ' . $status . ' ';
      }
      else
      {
        $sql .= "AND uf_status != 'Deleted' ";
      }
      
      $sql .= 'ORDER BY uf_status, u_username ';
      
      $retval = $this->dbh->query_all($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getFriendActivitiesFull
  * Description
  *   Method to get detail activity of a user's friends
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function getFriendActivitiesFull($userId = false, $days = 30)
  {
    $retval = false;
    
    if($userId !== false)
    {
      $userId = intval($userId);
      
      // careful with this query
      // omitted ua.ua_id for speed (use of index) - may not be a big deal anymore
      /*$sql = 'SELECT ua.ua_u_id AS A_U_ID, ua.ua_element_id AS A_ELEMENT, ua.ua_type AS A_TYPE, UNIX_TIMESTAMP(ua.ua_dateCreated) AS A_TIMECREATED, '
           . 'u.u_username AS A_USERNAME, uf.up_thumb_path AS A_THUMB, u2.u_username AS A_COMMENT_USER, up.up_value AS A_AVATAR '
           . 'FROM ((((user_activities AS ua LEFT JOIN users AS u on ua.ua_u_id = u.u_id ) '
           . 'LEFT JOIN user_fotos AS uf ON ua.ua_element_id = uf.up_id) '
           . 'LEFT JOIN comments AS c ON ua.ua_element_id = c.c_id) '
           . 'LEFT JOIN users AS u2 ON c.c_for_u_id = u2.u_id) '
           . "LEFT JOIN user_prefs AS up ON u.u_id = up.up_u_id AND up.up_name = 'AVATAR' "
           . "WHERE ua.ua_u_id IN(
                SELECT uf_friendId FROM user_friends WHERE uf_u_id = {$userId} AND uf_status = 'Confirmed'
              ) AND ua.ua_dateCreated > '" . date('Y-m-d', strtotime('-' . intval($days) . ' days')) . "' "
           . 'ORDER BY ua.ua_dateCreated DESC ';*/
      $sql = 'SELECT ua.ua_u_id AS A_U_ID, ua.ua_element_id AS A_ELEMENT, ua.ua_type AS A_TYPE, UNIX_TIMESTAMP(ua.ua_dateCreated) AS A_TIMECREATED, '
           . 'ua.ua_extra_1 AS A_EXTRA_1, ua.ua_extra_2 AS A_EXTRA_2, ua.ua_extra_3 AS A_EXTRA_3, ua.ua_extra_4 AS A_EXTRA_4, up.up_value AS A_AVATAR, '
           . "DATE_FORMAT(ua.ua_dateCreated, '%Y%m%d') AS A_DATEKEY "
           . "FROM user_activities AS ua LEFT JOIN user_prefs AS up ON ua.ua_u_id = up.up_u_id AND up.up_name = 'AVATAR' "
           . "WHERE ua.ua_u_id IN(
                SELECT uf_friendId FROM user_friends WHERE uf_u_id = {$userId} AND uf_status = 'Confirmed'
              ) AND ua.ua_dateCreated > '" . date('Y-m-d', strtotime('-' . intval($days) . ' days')) . "' "
           . 'ORDER BY ua.ua_id DESC ';
      
      $rs = $this->dbh->query_all($sql);
      
      $retval = array();
      $key = '';
      foreach($rs as $v)
      {
        $key = $v['A_DATEKEY'] . $v['A_TYPE'] . $v['A_EXTRA_1'];
        $retval[$key][] = $v;
        /*switch($v['A_TYPE'])
        {
          case 'newPhoto':
            $retval[$v['A_USERNAME']][$v['A_TYPE']][$key][] = $v['A_THUMB'];
            break;
          case 'newComment':
            $retval[$v['A_USERNAME']][$v['A_TYPE']][$key][] = array('time' => $v['A_TIMECREATED'], 'user' => $v['A_COMMENT_USER']);
          case 'newSlideshow':
          case 'newVideo':
            $retval[$v['A_USERNAME']][$v['A_TYPE']][$key][] = array('time' => $v['A_TIMECREATED']);
            break;
        }*/
      }
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getFriendActivities
  * Description
  *   Method to get activity of a user's friends
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function getFriendActivities($userId = false, $days = 30)
  {
    $retval = false;
    
    if($userId !== false)
    {
      $userId = intval($userId);
      
      // careful with this query
      // omitted ua.ua_id for speed (use of index) - may not be a big deal anymore
      $sql = 'SELECT COUNT(*) AS A_COUNT, ua.ua_u_id AS A_U_ID, ua.ua_element_id AS A_ELEMENT, ua.ua_type AS A_TYPE, UNIX_TIMESTAMP(ua.ua_dateCreated) AS A_TIMECREATED, DATE_FORMAT(ua.ua_dateCreated, \'%Y%m%d\') AS A_DATEKEY, '
           . 'u.u_username AS A_USERNAME, up.up_value AS A_AVATAR '
           . 'FROM (user_activities AS ua INNER JOIN users AS u on ua.ua_u_id = u.u_id )'
           . "LEFT JOIN user_prefs AS up ON u.u_id = up.up_u_id AND up_name = 'AVATAR' "
           . "WHERE ua.ua_u_id IN(
                SELECT uf_friendId FROM user_friends WHERE uf_u_id = {$userId} AND uf_status = 'Confirmed'
              ) AND ua.ua_dateCreated > '" . date('Y-m-d', strtotime('-' . intval($days) . ' days')) . "' "
           . 'GROUP BY ua.ua_u_id, ua.ua_type ';
      
      
      $rs = $this->dbh->query_all($sql);
      
      $retval = array();
      foreach($rs as $v)
      {
        $key = $v['A_DATEKEY'] . '-' . $v['A_U_ID'] . '-' . $v['A_TYPE'];
        $retval[$key] = $v;
      }
      
      krsort($retval);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   isFriend
  * Description
  *   Method to check if a user is another user's friend
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function isFriend($userId = false, $friendId = false)
  {
    $retval = false;
    
    if($userId !== false && $friendId !== false)
    {
      $userId = intval($userId);
      $friendId = intval($friendId);
      
      $sql = 'SELECT uf_status AS UF_STATUS FROM user_friends WHERE uf_u_id = ' . $userId . ' AND uf_friendId = ' . $friendId;
      $retval = $this->dbh->query_first($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getMessage
  * Description
  *   Method to get a specific message
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function getMessage($userId = false, $messageId)
  {
    $retval = false;
    if($userId !== false && $messageId !== false)
    {
      $userId = intval($userId);
      $messageId = intval($messageId);
      $sql = 'SELECT ui_id AS UI_ID, ui_senderId AS UI_SENDERID, u_username AS UI_SENDER, ui_subject AS UI_SUBJECT, ui_message AS UI_MESSAGE, UNIX_TIMESTAMP(ui_dateCreated) AS UI_DATECREATED, ui_status AS UI_STATUS '
           . 'FROM user_inbox LEFT JOIN users ON ui_senderId = u_id '
           . "WHERE ui_id = {$messageId} AND ui_u_id = {$userId} AND ui_status != 'Deleted'";
      
      $retval = $this->dbh->query_first($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getMessages
  * Description
  *   Method to get a user's messages
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function getMessages($userId = false)
  {
    $retval = array();
    if($userId !== false)
    {
      $userId = intval($userId);
      $sql = 'SELECT ui_id AS UI_ID, ui_senderId AS UI_SENDERID, u_username AS UI_SENDER, ui_subject AS UI_SUBJECT, ui_message AS UI_MESSAGE, UNIX_TIMESTAMP(ui_dateCreated) AS UI_DATECREATED, ui_status AS UI_STATUS '
           . 'FROM user_inbox LEFT JOIN users ON ui_senderId = u_id '
           . "WHERE ui_u_id = {$userId} AND ui_status != 'Deleted' "
           . 'ORDER BY ui_id DESC ';
      
      $retval = $this->dbh->query_all($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   countMessages
  * Description
  *   Method to get a count of new messages
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function countMessages($userId)
  {
    $retval = 0;
    if($userId !== false)
    {
      $userId = intval($userId);
      $sql = 'SELECT COUNT(*) AS CNT '
           . 'FROM user_inbox '
           . "WHERE ui_u_id = {$userId} AND ui_status = 'Unread'";
      
      $rs = $this->dbh->query_first($sql);
      $retval = $rs['CNT'];
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   childAccounts
  * Description
  *   Method to get child accounts
  *   Use CUser::childAccounts
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function childAccounts($userId = false)
  {
    $retval = false;
    
    if($userId !== false)
    {
      $userId   = intval($userId);
      $sql      = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
                . 'FROM users AS u '
                . 'WHERE u.u_parentId = ' . $userId . " AND (u.u_status <> 'Active' OR CAST(u.u_dateExpires AS UNSIGNED) < '" . NOW . "')"; 
      
      $retval = $this->dbh->query_all($sql);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   inactive
  * Description
  *   Method to get inactive user info
  *   Use CUser::inactive to get an inactive user
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function inactive()
  {
    $return = false;
    
    if(func_num_args() == 1)
    {
      
      $user_id  = $this->dbh->sql_safe(func_get_arg(0));
      $sql      = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
                . 'FROM users AS u '
                . 'WHERE u.u_id = ' . $user_id . " AND (u.u_status <> 'Active' OR CAST(u.u_dateExpires AS UNSIGNED) < '" . NOW . "')"; 
      
      $return = $this->dbh->query_first($sql);
    }
    else
    if(func_num_args() == 2)
    {
      $username = $this->dbh->sql_safe(func_get_arg(0));
      $password = $this->dbh->sql_safe(md5(func_get_arg(1)));
      
      $sql      = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
                . 'FROM users AS u '
                . 'WHERE u.u_username = ' . $username . ' AND u.u_password = ' . $password . " AND (u.u_status <> 'Active' OR CAST(u.u_dateExpires AS UNSIGNED) < '" . NOW . "')";
               
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   userByKey
  * Description
  *   Method to get user information by user key
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function userByKey($key = false)
  {
    $return = false;
    
    if(strlen($key) == 32)
    {
      $key  = $this->dbh->sql_safe(func_get_arg(0));
      
      $sql  = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
            . 'FROM users AS u '
            . 'WHERE u.u_key = ' . $key . " AND u_status = 'Active'"; 
      
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
/*
  *******************************************************************************************
  * Name
  *   userByUsername
  * Description
  *   Method to get user information by username
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function userByUsername($username = false)
  {
    $return = false;
    
    if($username !== false)
    {
      $username = $this->dbh->sql_safe($username);
      
      $sql  = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
            . 'FROM users AS u '
            . 'WHERE u.u_username = ' . $username . ' '; 
      
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   userByHash
  * Description
  *   Method to get user by md5 hash of username-password
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function userByHash($hash)
  {
    $hash = $this->dbh->sql_safe($hash);
    
    $sql  = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
          . 'FROM users AS u '
          . "WHERE md5(CONCAT_WS('-', u.u_username, u.u_password)) = " . $hash . " AND u.u_status = 'Active' AND u.u_dateExpires >= NOW()"; 
    
    $return = $this->dbh->query_first($sql);
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   validateCredentials
  * Description
  *   Method to see if a username / email is valid
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function validateCredentials($string = null, $type = null)
  {
    $return = false;
    if($string !== null && $type !== null)
    {
      $stringSafe = $this->dbh->sql_safe($string);
      $sql  = 'SELECT u.u_id AS U_ID '
            . 'FROM users AS u ';
      switch($type)
      {
        case 'email':
          $sql .= 'WHERE u_email = ' . $stringSafe;
          break;
        case 'username':
          $sql .= 'WHERE u_username = ' . $stringSafe;
          break;
        default:
          $sql .= 'WHERE 0';
      }
    
      $return = $this->dbh->query_first($sql);
      
      // only need to check if no record was found in users table
      // we need to make sure no record is in the temp table
      if($return === false)
      {
      	$sql  = 'SELECT u.u_username AS U_USERNAME '
            . 'FROM user_incompletes AS u ';
	      switch($type)
	      {
	        case 'email':
	          $sql .= 'WHERE u_email = ' . $stringSafe;
	          break;
	        case 'username':
	          $sql .= 'WHERE u_username = ' . $stringSafe;
	          break;
	        default:
	          $sql .= 'WHERE 0';
	      }
	      
	      $return = $this->dbh->query_first($sql);
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   search
  * Description
  *   Method to search for users
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function search($params)
  {
    $where = 'WHERE 1 ';
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_businessName AS U_BUSINESSNAME, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
         . 'FROM users AS u ';
    
    if(isset($params['GROUP_ID']))
    {
      $sql .= 'INNER JOIN user_group_map AS ugm ON u.u_id = ugm.u_id ';
      $where .= 'AND ugm.g_id = ' . intval($params['GROUP_ID']) . ' ';
    }
  
    if(isset($params['CP_USER_ID']))
    {
      $where .= 'AND u.u_id > ' . intval($params['CP_USER_ID']) . ' ';
      $where .= 'AND u.u_status = \'Active\' ';
      $where .= 'AND u.u_dateExpires > NOW() ';
    }
    else if(isset($params['USER_ID']))
    {
      $where .= 'AND u.u_id = ' . intval($params['USER_ID']) . ' ';
    }
    
    if(isset($params['PARENT_ID']))
    {
      $where .= 'AND u.u_parentId = ' . intval($params['PARENT_ID']) . ' ';
    }
    
    if(isset($params['USERNAME']))
    {
      $where .= 'AND u.u_username LIKE ' . $this->dbh->sql_safe('%' . $params['USERNAME'] . '%') . ' ';
    }
    
    if(isset($params['USER_KEY']))
    {
      $where .= 'AND u.u_key = ' . $this->dbh->sql_safe($params['USER_KEY']) . ' ';
    }
    
    if(isset($params['NAMELAST']))
    {
      $where .= 'AND u.u_nameLast LIKE ' . $this->dbh->sql_safe('%' . $params['NAMELAST'] . '%') . ' ';
    }
    
    if(isset($params['NAMEFIRST']))
    {
      $where .= 'AND u.u_nameFirst LIKE ' . $this->dbh->sql_safe('%' . $params['NAMEFIRST'] . '%') . ' ';
    }
    
    $sql .= $where;
    
    if(isset($params['LIMIT']))
    {
      $sql .= 'LIMIT ' . intval($params['LIMIT']) . ' ';
    }
    
    if(isset($params['OFFSET']))
    {
      $sql .= 'OFFSET ' . intval($params['OFFSET']) . ' ';
    }
    
    $return = $this->dbh->query_all($sql);
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   usersByPool
  * Description
  *   Method to get users by a pool id
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function usersByPool($group_id, $keywords, $offset, $limit)
  {
    $keywords= $this->dbh->sql_safe('%' . $keywords . '%');
    
    $sql  = 'SELECT SQL_CALC_FOUND_ROWS u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, '
          . 'ugm.g_id AS U_GROUP_ID, gi.gi_g_id AS GI_G_ID, gi.gi_status AS GI_STATUS '
          . 'FROM ((users AS u INNER JOIN pool_users_map AS pum ON u.u_id = pum.pum_u_id) LEFT JOIN user_group_map AS ugm ON u.u_id = ugm.u_id '
          . 'AND ugm.g_id = ' . intval($group_id) . ') '
          . 'LEFT JOIN group_invite AS gi ON u.u_id = gi.gi_u_id '
          . 'AND gi.gi_g_id = ' . intval($group_id) . ' '
          . 'WHERE pum.pum_pu_id = ' . intval($group_id) . ' '
          . 'AND (u.u_username LIKE ' . $keywords . ' OR u.u_nameFirst LIKE ' . $keywords . ' OR u.u_nameLast LIKE ' . $keywords . ' OR u.u_email LIKE ' . $keywords . ") AND u.u_status = 'active' "
          . 'LIMIT ' . intval($limit) . ' OFFSET ' . intval($offset);
    
    $rs = $this->dbh->query_all($sql);
    $rs[0]['TOTAL_ROWS'] = $GLOBALS['dbh']->found_rows();
    
    foreach($rs as $k => $v)
    {
      $rs[$k]['AVATAR'] = $this->pref($v['U_ID'], 'AVATAR');
    }
    
    return $rs;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   my
  * Description
  *   Method to get user information by username and checks date
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function my()
  {
    $username = $this->dbh->sql_safe(func_get_arg(0));
    
    $sql      = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
              . 'FROM users AS u '
              . 'WHERE u.u_username = ' . $username . " AND u.u_status = 'Active' AND u.u_dateExpires >= NOW()"; 
    
    return $this->dbh->query_first($sql);
  }
  
 /*
  *******************************************************************************************
  * Name
  *   inPromotion
  * Description
  *   
  *   Use CUser::inPromotion($userId, $promotionName)
  *
  * Output
  *   int
  ******************************************************************************************
  */
  function inPromotion($userId = false, $promotionName = false)
  {
    $retval = 0;
    if($userId !== false && $promotionName !== false)
    {
      $result = $this->dbh->query_first('SELECT p_id FROM promotions WHERE p_u_id = ' . intval($userId) . ' AND p_name = ' . $this->dbh->sql_safe($promotionName));
      $retval = intval($result['p_id']);
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   subAccount
  * Description
  *   
  *   Use CUser::subAccount($id)
  *       CUser::subAccount($username, $password)
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function subAccount()
  {
    $argCnt = func_num_args();
    $sql  = 'SELECT usa_id AS SA_ID, usa_u_id AS SA_U_ID, usa_username AS SA_USERNAME, usa_password AS SA_PASSWORD, usa_email AS SA_EMAIL, UNIX_TIMESTAMP(usa_dateLastLogin) AS SA_LASTLOGIN '
          . 'FROM user_sub_accounts ';

    if($argCnt == 1)
    {   
      $sql .= 'WHERE usa_id = ' . $this->dbh->sql_safe(func_get_arg(0)) . ' ';
    }
    else
    {
      $sql .= 'WHERE usa_username = ' . $this->dbh->sql_safe(func_get_arg(1)) . ' AND usa_password = ' . $this->dbh->sql_safe(func_get_arg(2)) . ' ';
    }
    
    $sql .= "AND usa_status = 'active'";
    
    $retval = $this->dbh->query_first($sql);
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   subAccounts
  * Description
  *   
  *   Use CUser::subAccounts($parentId)
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function subAccounts($parentId = false)
  {
    $sql  = 'SELECT usa_id AS SA_ID, usa_u_id AS SA_U_ID, usa_username AS SA_USERNAME, usa_password AS SA_PASSWORD, usa_email AS SA_EMAIL, UNIX_TIMESTAMP(usa_dateLastLogin) AS SA_LASTLOGIN '
          . 'FROM user_sub_accounts '
          . 'WHERE usa_u_id = ' . $this->dbh->sql_safe($parentId) . " AND usa_status = 'active' "
          . 'ORDER BY usa_username';
    
    $retval = $this->dbh->query_all($sql);
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getToken
  * Description
  *   
  *   Use CUser::getToken($token)
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function getToken($token = false)
  {
    $sql  = 'SELECT ut_token AS T_TOKEN, ut_u_id AS T_U_ID, ut_sess_hash AS T_SESS_HASH '
          . 'FROM user_tokens '
          . 'WHERE ut_token = ' . $this->dbh->sql_safe($token) . ' AND ut_expires > ' . NOW . ' ';
    
    $retval = $this->dbh->query_first($sql);
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   profile
  * Description
  *   
  *   Use CUser::profile($user_id)
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function profile($user_id = false)
  {
    $return = array();
    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql  = 'SELECT p_u_id AS P_U_ID, p_profile AS P_PROFILE '
            . 'FROM user_profiles '
            . 'WHERE p_u_id = ' . $user_id;
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   prefs
  * Description
  *   get all preferences for a user
  
  * Use CUser::preferences($user_id)
  *
  * Output
  *   mixed
  ******************************************************************************************
  */
  function prefs($user_id = false)
  {
    $return = array();
    
    if($user_id !== false)
    {
      $user_id= $this->dbh->sql_safe($user_id);
      $sql    = 'SELECT up_name AS P_NAME, up_value AS P_VALUE FROM user_prefs WHERE up_u_id = ' . $user_id;
      
      $prefs = $this->dbh->query_all($sql);
      foreach($prefs as $v)
      {
        $return[$v['P_NAME']] = $v['P_VALUE'];
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   pref
  * Description
  *   get specific preference for a user
  
  * Use CUser::preferences($user_id, $preference)
  *
  * Output
  *   mixed
  ******************************************************************************************
  */
  function pref($user_id = false, $preference = '')
  {
    $return = false;
    
    if($user_id !== false)
    {
      $user_id= $this->dbh->sql_safe($user_id);
      $preference = $this->dbh->sql_safe($preference);
      $sql    = 'SELECT up_value AS P_VALUE FROM user_prefs WHERE up_u_id = ' . $user_id . ' AND up_name = ' . $preference;
      
      $pref = $this->dbh->query_first($sql);
      if($pref['P_VALUE'] != '')
      {
        $return = $pref['P_VALUE'];
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getTipByKey
  * Description
  *   Method to get a tip by key
  * Input
  *   $tipKey    string
  * Output
  *   array / boolean
  ******************************************************************************************
  */
  function getTipByKey($tipKey = false)
  {
    $sql = 'SELECT t_id AS T_ID, t_title AS T_TITLE, t_body AS T_BODY FROM tips WHERE t_key = ' . $this->dbh->sql_safe($tipKey) . " AND t_status = 'active'";
    $return = $this->dbh->query_first($sql);
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getNextTip
  * Description
  *   Method to get the next tip
  * Input
  *   $tipId    int
  * Output
  *   array / boolean
  ******************************************************************************************
  */
  function getNextTip($tipId = 0)
  {
    $tipId = intval($tipId);
    
    $sql = 'SELECT t_id AS T_ID, t_title AS T_TITLE, t_body AS T_BODY FROM tips WHERE t_id > ' . $tipId . " AND t_status = 'active'";
    $return = $this->dbh->query_first($sql);
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getRandomTip
  * Description
  *   Method to get the next tip
  * Input
  *   $tipId    int
  * Output
  *   array / boolean
  ******************************************************************************************
  */
  function getRandomTip()
  {
    $sql = "SELECT COUNT(*) AS CNT FROM tips WHERE t_status = 'active'";
    $check = $this->dbh->query_first($sql);
    $rand = intval(rand(0, ($check['CNT']-1)));
    
    $sql = "SELECT t_id AS T_ID, t_title AS T_TITLE, t_body AS T_BODY FROM tips WHERE t_status = 'active' LIMIT 1 OFFSET {$rand}";
    $return = $this->dbh->query_first($sql);
    
    return $return;
  }
  
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
      $sql  = 'SELECT p_password AS P_PASSWORD, p_description AS P_DESCRIPTION, p_colors AS P_COLORS '
            . 'FROM user_pages '
            . 'WHERE p_u_id = ' . $user_id;
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   verifyPassword
  * Description
  *   
  *   Use CUser::verifyPassword($userId, $password)
  *
  * Output
  *   boolean
  ******************************************************************************************
  */
  function verifyPassword($userId = false, $password = false)
  {
    $retval = false;
    if($userId !== false && $password !== false)
    {
      $sql = 'SELECT u_id FROM users WHERE u_id = ' . intval($userId) . ' AND u_password = ' . $this->dbh->sql_safe(md5($password));
      //echo $sql;
      $rs = $this->dbh->query($sql);
      
      if($this->dbh->num_rows($rs) == 1)
      {
        $retval = true;
      }
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   suggestUsername
  * Description
  *   
  *   Use CUser::suggestUsername($username)
  *
  * Output
  *   mixed
  ******************************************************************************************
  */
  function suggestUsername($username = '')
  {
    $username_safe = $this->dbh->sql_safe($username);
    $sql= "SELECT u_id AS U_ID FROM users WHERE u_username = {$username_safe}";
    $ar = $this->dbh->query_first($sql);
    if(isset($ar['U_ID']))
    {
      $return = array();
      for($i=0; $i<3; $i++)
      {
        $return[] = $username . rand(100, 999);
      }
      
      return $return;
    }
    else 
    {
      return true;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   checkCancel
  * Description
  *   
  *   Use CUser::checkCancel($user_id)
  *
  * Output
  *   mixed
  ******************************************************************************************
  */
  function checkCancel($user_id = false)
  {
    $return = false;
    
    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql = 'SELECT uc_confirmationId FROM user_cancellations WHERE uc_u_id = ' . $user_id;
      
      $ar = $this->dbh->query_first($sql);
      
      if(isset($ar['uc_confirmationId']))
      {
        $return = $ar['uc_confirmationId'];
      }
    }
    
    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   blogs
  * Description
  *   
  *   Use CUser::blogs($user_id) to get blogs
  *
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  function blogs($user_id = false, $ids =false)
  {
    $return = array();
    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql  = 'SELECT ub_id AS B_ID, ub_name AS B_NAME, ub_blogId AS B_BLOGID, ub_url AS B_URL, ub_username AS B_USERNAME, ub_password AS B_PASSWORD, ub_type AS B_TYPE, ub_endPoint AS B_ENDPOINT '
            . 'FROM user_blogs '
            . 'WHERE ub_u_id = ' . $user_id;
      
      if($ids !== false)
      {
        if(is_array($ids))
        {
          $ids = $this->dbh->asql_safe($ids);
          $sql .= ' AND ub_id IN(' . implode(',', $ids) . ')';
        }
        else
        {
          $ids = intval($ids);
          $sql .= ' AND ub_id = ' . $ids;
        }
      }
      
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
    
 /*
  *******************************************************************************************
  * Name
  *   expiresIn
  * Description
  *   Method to retrieve users expiring in X days
  *
  * Input
  *   $days        int
  * Output
  *   array
  ******************************************************************************************
  */
  function expiresIn($days = false)
  {
    $return = array();
    
    if($days !== false)
    {
      $time     = mktime(0, 0, 0, date('m', NOW), date('d', NOW), date('Y', NOW));
      $expiry   = $time + (86400 * $days);
      $expiry_date = $this->dbh->sql_safe(date('Y-m-d', $expiry));
      
      $sql      = 'SELECT u.u_id AS U_ID, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
                . 'FROM users AS u '
                . 'WHERE u.u_dateExpires = ' . $expiry_date . " AND u.u_status = 'Active'"; 
               
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   space
  * Description
  *   Method to get space available for user (in KB)
  *
  * Output
  *   int
  ******************************************************************************************
  */
  function space($user_id = false)
  {
    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $ar = $this->dbh->fetch_assoc(
              $this->dbh->query('SELECT u_spaceTotal AS SPACE FROM users WHERE u_id = ' . $user_id)
            );
      
      return (int)$ar['SPACE'];
    }
    else
    {
      return 0;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   groupMembers
  * Description
  *   Method to get members for group (called from CGroup::members)
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function groupMembers($params = false)
  {
    $return = array();
    if(isset($params['GROUP_ID']))
    {
      $group_id = $this->dbh->sql_safe($params['GROUP_ID']);

      
      $sql  = 'SELECT u.u_id AS U_ID, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, UNIX_TIMESTAMP(ugm.dateCreated) AS U_JOINED '
            . 'FROM users AS u INNER JOIN user_group_map AS ugm ON u.u_id = ugm.u_id '
            . 'WHERE ugm.g_id = ' . $group_id . ' ';
      $sql .= "AND u.u_status = 'Active' ";
      
      if(isset($params['LIMIT']))
      {
        $sql .= 'LIMIT ' . intval($params['LIMIT']) . ' ';
        $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
      }
      
      if(isset($params['OFFSET']))
      {
        $sql .= 'OFFSET ' . intval($params['OFFSET']) . ' ';
      }

      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   userIdEnc
  * Description
  *   Encode a user id - safe to pass a userid and hash through get/post to later validate with userIdDec
  *
  * Input
  *   $id        int
  * Output
  *   string
  ******************************************************************************************
  */
  function userIdEnc($id)
  {
    $retval = 0;
    
    if($id > 0)
    {
      $retval = $id . '-' . md5($id . '_ff');
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   userIdDec
  * Description
  *   Decode an encoded string from userIdEnc
  *
  * Input
  *   $str    string
  * Output
  *   array
  ******************************************************************************************
  */
  function userIdDec($str)
  {
    $retval = array('match' => false);
    
    if(preg_match('/^\d+\-\w{32}$/', $str))
    {
      $retval['hash'] = substr($str, -32, 32);
      $retval['id'] = substr($str, 0, strrpos($str, '-'));
      $retval['idHash'] = md5($retval['id'] . '_ff');
      $retval['match'] = $retval['idHash'] == $retval['hash'] ? true : false;
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   temporaryCookie
  * Description
  *   Method to set temporary cookie
  *
  * Input
  *   $user_id        int
  * Output
  *   boolean
  ******************************************************************************************
  */
  function temporaryCookie($user_id)
  {
    if(is_numeric($user_id))
    {
      setcookie(FF_SESSION_TMP_KEY, $user_id, NOW + 1800, FF_SESSION_PATH, FF_SESSION_DOMAIN);
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
  *   searchInGroup
  * Description
  *   search in a group
  *
  * Input
  *   $username - username of user
  *   $email - email of user
  *   $firstName - first name of user
  *   $lastName - last name of user
  *   $group_id - id of group to search in
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function searchInGroup($username = false, $email = false, $firstName = false, $lastName = false, $group_id = false, $limit = 25, $offset = 0)
  {
    $where = 'WHERE 1 ';
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
         . 'FROM users AS u ';
         
    if($group_id !== false)
    {
      $sql .= 'INNER JOIN user_group_map AS ugm ON u.u_id = ugm.u_id ';
      $where .= 'AND ugm.g_id = ' . $group_id . ' ';
    }
    
    if($username !== false)
    {
      $where .= "AND u.u_username LIKE '%" . $username . "%' ";
    }
    
    if($email !== false)
    {
      $where .= "AND u.u_email LIKE '%" . $email . "%' ";
    }
    
    if($firstName !== false)
    {
      $where .= "AND u.u_nameFirst LIKE '%" . $firstName . "%' ";
    }
    
    if($lastName !== false)
    {
      $where .= "AND u.u_nameLast LIKE '%" . $lastName . "%' ";
    }
    
    $sql .= $where . ' '
          . 'ORDER BY u.u_id DESC '
          . 'LIMIT ' . $limit . ' '
          . 'OFFSET ' . $offset;

    return $this->dbh->query_all($sql);
  }
  
 /*
  *******************************************************************************************
  * Name
  *   _checkUsername
  * Description
  *   Private function to check validity of username
  *
  * Input
  *   $username       str
  * Output
  *   Boolean
  ******************************************************************************************
  */
  function _checkUsername($username = '', $email = '', $check_exists = true, $check_temp = true)
  {
    if(strlen($username) < 4 || strlen($username) > 16 || preg_match('/\W/', $username) || strlen($email) < 4)
    {
      return false;
    }
    else
    if($check_exists === true)
    {
      $username = $this->dbh->sql_safe($username);
      $email = $this->dbh->sql_safe($email);
      $rsUser = $this->dbh->query($sql = 'SELECT u_id FROM users WHERE u_username = ' . $username . ' OR u_email = ' . $email);
      
      if($check_temp === true)
      {
      	$rsUserTemp = $this->dbh->query($sql = 'SELECT u_username FROM user_incompletes WHERE u_username = ' . $username . ' OR u_email = ' . $email);
      	
      	if($this->dbh->num_rows($rsUser) == 0 && $this->dbh->num_rows($rsUserTemp) == 0)
	      {
	        return true;
	      }
	      else
	      {
	        return false;
	      }
      }
      else
      {
      	if($this->dbh->num_rows($rsUser) == 0)
	      {
	        return true;
	      }
	      else
	      {
	        return false;
	      }
      }
    }
    else
    {
      return true;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   _encrypt
  * Description
  *   Private method to encrypt strings
  *
  * Input
  *   string
  * Output
  *   string
  ******************************************************************************************
  */
  function _encrypt($string)
  {
    return md5($string);
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
  function CUser()
  {
  }
}
?>