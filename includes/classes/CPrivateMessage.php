<?php

/*******************************************************************************************
 * Name:        CPrivateMessage.php
 * Class Name:  CPrivateMessage
 *------------------------------------------------------------------------------------------
 * Mod History: Kevin Hornschemeier   05/04/2006
 *------------------------------------------------------------------------------------------
 * Class to handle private messaging between users
 * 
 *******************************************************************************************/

class CPrivateMessage
{ 
  
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
  *   CPrivateMessage
  *
  * Description
  *   Class constructor (initializes variables)
  *
  * Input
  *   
  * Output
  *
  *******************************************************************************************/
  function CPrivateMessage()
  {
  }
  
  /*******************************************************************************************
  * Name
  *   isBanned
  *
  * Description
  *   Determines if the user is banned by another user
  *
  * Input
  *   $isBanned - possible banned user
  *   $byThisUser - by this user
  *
  * Output
  *   boolean - if the user is banned or not
  *
  *******************************************************************************************/
  function isBanned($isBanned, $byThisUser)
  {
    $isBanned = $this->dbh->sql_safe($isBanned);
    $byThisUser = $this->dbh->sql_safe($byThisUser);
    
    $sql = 'SELECT * '
         . 'FROM private_message_ban '
         . 'WHERE pmb_u_id = ' . $byThisUser . ' '
         . 'AND pmb_who = ' . $isBanned; 
    
    $rs = $this->dbh->query_all($sql);
    $numRows = count($rs);
    if( $numRows == 0 )
    {
      return false;
    }
    else 
    {
      return true;
    }
  }
  
  /*******************************************************************************************
  * Name
  *   ban
  *
  * Description
  *   bans a user
  *
  * Input
  *   $uid - user id
  *   $who - the user they are banning
  *
  * Output
  *   
  *
  *******************************************************************************************/
  function ban($uid, $who)
  {
    $uid = $this->dbh->sql_safe($uid);
    $who = $this->dbh->sql_safe($who);
    
    $sql = 'INSERT INTO private_message_ban (pmb_u_id, pmb_who, pmb_dateCreated) '
         . 'VALUES (' . $uid . ', ' . $who . ', NOW())';
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   unBan
  *
  * Description
  *   unbans a user
  *
  * Input
  *   $uid - user id
  *   $who - user they are unbanning
  *
  * Output
  *   
  *
  *******************************************************************************************/
  function unBan($uid, $who)
  {
    $uid = $this->dbh->sql_safe($uid);
    $who = $this->dbh->sql_safe($who);
    
    $sql = 'DELETE FROM private_message_ban '
         . 'WHERE pmb_u_id = ' . $uid . ' '
         . 'AND pmb_who = ' . $who;
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   hasOptedOut
  *
  * Description
  *   Determines if the user has opted out of pm
  *
  * Input
  *   $uid - user id
  *
  * Output
  *   boolean - if the user has opted out or not
  *
  *******************************************************************************************/
  function hasOptedOut($uid)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'SELECT * '
         . 'FROM private_message_optout '
         . 'WHERE pmo_u_id = ' . $uid; 
    
    $rs = $this->dbh->query_all($sql);
    $numRows = count($rs);
    if( $numRows == 0 )
    {
      return false;
    }
    else 
    {
      return true;
    }
  }
  
  /*******************************************************************************************
  * Name
  *   optOut
  *
  * Description
  *   Opts the user out
  *
  * Input
  *   $uid - user id
  *
  * Output
  *   
  *
  *******************************************************************************************/
  function optOut($uid)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'INSERT INTO private_message_optout (pmo_u_id, pmo_dateCreated) '
         . 'VALUES (' . $uid . ', NOW())';
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   optIn
  *
  * Description
  *   Opts the user in
  *
  * Input
  *   $uid - user id
  *
  * Output
  *   
  *
  *******************************************************************************************/
  function optIn($uid)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'DELETE FROM private_message_optout '
         . 'WHERE pmo_u_id = ' . $uid;
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   send
  *
  * Description
  *   send a private message
  *
  * Input
  *   $uid - user id
  *   $who - who the pm is sent to
  *   $subject - subject of the message
  *   $message - contents of the message
  *
  * Output
  *   boolean -  1: successful
  *             -1:   sender has opted out
  *             -2:   sender is banned from sending to this person
  *
  *******************************************************************************************/
  function send($uid, $who, $subject, $message)
  {
    $uid_safe = $this->dbh->sql_safe($uid);
    $subject = $this->dbh->sql_safe($subject);
    $message = $this->dbh->sql_safe($message);
    
    if(is_array($who))
    {
      $who_safe = $this->dbh->asql_safe($who);
      
      foreach($who as $k => $v)
      {
        if( $this->hasOptedOut($uid) === true )
        {
        }
        else if( $this->isBanned($uid, $v['U_ID']) === true )
        {
        }
        else 
        {
          $sql = 'INSERT INTO private_message_content (pmc_content, pmc_dateCreated) '
               . 'VALUES (' . $message . ', NOW())';
          $this->dbh->execute($sql);
          $insert_id = $this->dbh->insert_id();
          
          $sql = 'INSERT INTO private_message (pm_sender_id, pm_receiver_id, pm_subject, pm_pmc_id, pm_status, pm_type, pm_dateCreated) '
               . 'VALUES (' . $uid_safe . ', ' . $v['U_ID'] . ', ' . $subject . ', ' . $insert_id . ", 'New', 'Sent', NOW())";
          $this->dbh->execute($sql);
          
          $sql = 'INSERT INTO private_message (pm_sender_id, pm_receiver_id, pm_subject, pm_pmc_id, pm_status, pm_type, pm_dateCreated) '
               . 'VALUES (' . $uid_safe . ', ' . $v['U_ID'] . ', ' . $subject . ', ' . $insert_id . ", 'New', 'Received', NOW())";
          $this->dbh->execute($sql);
        }
      }
    }
    else 
    {
      $who_safe = $this->dbh->sql_safe($who);
      
      if( $this->hasOptedOut($uid) === true )
      {
        return -1;
      }
      else if( $this->isBanned($uid, $who) === true )
      {
        return -2;
      }
      else 
      {
        $sql = 'INSERT INTO private_message_content (pmc_content, pmc_dateCreated) '
             . 'VALUES (' . $message . ', NOW())';
        $this->dbh->execute($sql);
        $insert_id = $this->dbh->insert_id();
        
        $sql = 'INSERT INTO private_message (pm_sender_id, pm_receiver_id, pm_subject, pm_pmc_id, pm_status, pm_type, pm_dateCreated) '
             . 'VALUES (' . $uid_safe . ', ' . $who_safe . ', ' . $subject . ', ' . $insert_id . ", 'New', 'Sent', NOW())";
        $this->dbh->execute($sql);
        
        $sql = 'INSERT INTO private_message (pm_sender_id, pm_receiver_id, pm_subject, pm_pmc_id, pm_status, pm_type, pm_dateCreated) '
             . 'VALUES (' . $uid_safe . ', ' . $who_safe . ', ' . $subject . ', ' . $insert_id . ", 'New', 'Received', NOW())";
        $this->dbh->execute($sql);
        
        return 1;
      }
    }
  }
  
  /*******************************************************************************************
  * Name
  *   newMessagesExist
  *
  * Description
  *   Checks to see if new messages exist for this user
  *
  * Input
  *   $uid - user id
  *
  * Output
  *   boolean - whether a message exists or not
  *
  *******************************************************************************************/
  function newMessagesExist($uid)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'SELECT * '
         . 'FROM private_message '
         . 'WHERE pm_receiver_id = ' . $uid . ' '
         . "AND pm_status = 'New'";
         
    $rs = $this->dbh->query_all($sql);
    $numRows = count($rs);
    if( $numRows == 0 )
    {
      return false;
    }
    else 
    {
      return true;
    }
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   getReceivedMessages
  *
  * Description
  *   Get all the messages received for a user
  *
  * Input
  *   $uid - user id
  *   $limit - messages limit
  *   $offset - messages offset
  *
  * Output
  *   array of messages
  *
  *******************************************************************************************/
  function getReceivedMessages($uid, $limit = false, $offset = false)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS pm.pm_id AS PM_ID, pm.pm_sender_id AS PM_SENDER_ID, pm.pm_receiver_id AS PM_RECEIVER_ID, pm.pm_subject AS PM_SUBJECT, pm.pm_pmc_id AS PM_CONTENT_ID, pm.pm_status AS PM_STATUS, pm.pm_type AS PM_TYPE, UNIX_TIMESTAMP(pm.pm_dateCreated) AS PM_DATECREATED, u.u_username AS U_SENDER_USERNAME '
         . 'FROM private_message pm, users u '
         . 'WHERE pm_receiver_id = ' . $uid . ' '
         . 'AND pm.pm_sender_id = u.u_id '
         . "AND pm.pm_type = 'Received' "
         . "AND pm.pm_status <> 'Deleted' "
         . 'ORDER BY pm_status, pm_dateCreated DESC';
         
    if( $limit !== false )
    {
      $sql .= ' LIMIT ' . $limit;
    }
    
    if( $offset !== false )
    {
      $sql .= ' OFFSET ' . $offset;
    }
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   getSentMessages
  *
  * Description
  *   Get all the messages sent by this user
  *
  * Input
  *   $uid - user id
  *   $limit - messages limit
  *   $offset - messages offset
  *
  * Output
  *   array of messages
  *
  *******************************************************************************************/
  function getSentMessages($uid, $limit = false, $offset = false)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS pm.pm_id AS PM_ID, pm.pm_sender_id AS PM_SENDER_ID, pm.pm_receiver_id AS PM_RECEIVER_ID, pm.pm_subject AS PM_SUBJECT, pm.pm_pmc_id AS PM_CONTENT_ID, pm.pm_status AS PM_STATUS, pm.pm_type AS PM_TYPE, UNIX_TIMESTAMP(pm.pm_dateCreated) AS PM_DATECREATED, u.u_username AS U_RECEIVER_USERNAME '
         . 'FROM private_message pm, users u '
         . 'WHERE pm_sender_id = ' . $uid . ' '
         . 'AND pm.pm_receiver_id = u.u_id '
         . "AND pm.pm_type = 'Sent' "
         . "AND pm.pm_status <> 'Deleted' "
         . 'ORDER BY pm_status, pm_dateCreated DESC';
         
    if( $limit !== false )
    {
      $sql .= ' LIMIT ' . $limit;
    }
    
    if( $offset !== false )
    {
      $sql .= ' OFFSET ' . $offset;
    }
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   getDeletedMessages
  *
  * Description
  *   Get all the deleted messages for a user
  *
  * Input
  *   $uid - user id
  *   $limit - messages limit
  *   $offset - messages offset
  *
  * Output
  *   array of messages
  *
  *******************************************************************************************/
  function getDeletedMessages($uid, $limit = false, $offset = false)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS pm.pm_id AS PM_ID, pm.pm_sender_id AS PM_SENDER_ID, pm.pm_receiver_id AS PM_RECEIVER_ID, pm.pm_subject AS PM_SUBJECT, pm.pm_pmc_id AS PM_CONTENT_ID, pm.pm_status AS PM_STATUS, pm.pm_type AS PM_TYPE, UNIX_TIMESTAMP(pm.pm_dateCreated) AS PM_DATECREATED, u.u_username AS U_SENDER_USERNAME '
         . 'FROM private_message pm, users u '
         . 'WHERE (pm_receiver_id = ' . $uid . ' '
         . 'OR pm_sender_id = ' . $uid . ') '
         . 'AND pm.pm_sender_id = u.u_id '
         . "AND pm.pm_status = 'Deleted' "
         . 'ORDER BY pm_status, pm_dateCreated DESC';
         
    if( $limit !== false )
    {
      $sql .= ' LIMIT ' . $limit;
    }
    
    if( $offset !== false )
    {
      $sql .= ' OFFSET ' . $offset;
    }
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   getMessage
  *
  * Description
  *   Get a single message for a user
  *
  * Input
  *   $uid - user id
  *   $id - message id
  *
  * Output
  *   array - message fields
  *
  *******************************************************************************************/
  function getReceivedMessage($uid, $id)
  {
    $uid = $this->dbh->sql_safe($uid);
    $id = $this->dbh->sql_safe($id);
    
    $sql = 'SELECT pm.pm_id AS PM_ID, pm.pm_sender_id AS PM_SENDER_ID, pm.pm_receiver_id AS PM_RECEIVER_ID, pm.pm_subject AS PM_SUBJECT, pm.pm_pmc_id AS PM_CONTENT_ID, pm.pm_status AS PM_STATUS, pm.pm_type AS PM_TYPE, UNIX_TIMESTAMP(pm.pm_dateCreated) AS PM_DATECREATED, u.u_username AS U_SENDER_USERNAME, pmc.pmc_content AS PMC_CONTENT '
         . 'FROM private_message pm, users u, private_message_content pmc '
         . 'WHERE pm_receiver_id = ' . $uid . ' '
         . 'AND pm.pm_sender_id = u.u_id '
         . 'AND pm.pm_pmc_id = pmc.pmc_id '
         . 'AND pm.pm_id = ' . $id . ' '
         . "AND pm.pm_type = 'Received' "
         . "AND pm.pm_status <> 'Deleted' ";
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   getSentMessage
  *
  * Description
  *   Get a single message sent by this user
  *
  * Input
  *   $uid - user id
  *   $id - message id
  *
  * Output
  *   array - message fields
  *
  *******************************************************************************************/
  function getSentMessage($uid, $id)
  {
    $uid = $this->dbh->sql_safe($uid);
    $id = $this->dbh->sql_safe($id);
    
    $sql = 'SELECT pm.pm_id AS PM_ID, pm.pm_sender_id AS PM_SENDER_ID, pm.pm_receiver_id AS PM_RECEIVER_ID, pm.pm_subject AS PM_SUBJECT, pm.pm_pmc_id AS PM_CONTENT_ID, pm.pm_status AS PM_STATUS, pm.pm_type AS PM_TYPE, UNIX_TIMESTAMP(pm.pm_dateCreated) AS PM_DATECREATED, u.u_username AS U_RECEIVER_USERNAME, pmc.pmc_content AS PMC_CONTENT '
         . 'FROM private_message pm, users u, private_message_content pmc '
         . 'WHERE pm_sender_id = ' . $uid . ' '
         . 'AND pm.pm_receiver_id = u.u_id '
         . 'AND pm.pm_pmc_id = pmc.pmc_id '
         . 'AND pm.pm_id = ' . $id . ' '
         . "AND pm.pm_type = 'Sent' "
         . "AND pm.pm_status <> 'Deleted' ";
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   markAsRead
  *
  * Description
  *   Marks a single message as read
  *
  * Input
  *   $uid - user id
  *   $id - message id
  *
  * Output
  *
  *
  *******************************************************************************************/
  function markReceivedAsRead($uid, $id)
  {
    $uid = $this->dbh->sql_safe($uid);
    $id = $this->dbh->sql_safe($id);
    
    $sql = 'UPDATE private_message '
         . "SET pm_status = 'Read' "
         . 'WHERE pm_receiver_id = ' . $uid . ' '
         . 'AND pm_id = ' . $id . ' '
         . "AND pm_type = 'Received'";
         
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   markSentAsRead
  *
  * Description
  *   Marks a single message as read
  *
  * Input
  *   $uid - user id
  *   $id - message id
  *
  * Output
  *
  *
  *******************************************************************************************/
  function markSentAsRead($uid, $id)
  {
    $uid = $this->dbh->sql_safe($uid);
    $id = $this->dbh->sql_safe($id);
    
    $sql = 'UPDATE private_message '
         . "SET pm_status = 'Read' "
         . 'WHERE pm_sender_id = ' . $uid . ' '
         . 'AND pm_id = ' . $id . ' '
         . "AND pm_type = 'Sent'";
         
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   deleteReceivedMessage
  *
  * Description
  *   Deletes a single message
  *
  * Input
  *   $uid - user id
  *   $id - message id
  *
  * Output
  *
  *
  *******************************************************************************************/
  function deleteReceivedMessage($uid, $id)
  {
    $uid = $this->dbh->sql_safe($uid);
    $id = $this->dbh->sql_safe($id);
         
    $sql = 'UPDATE private_message '
         . "SET pm_status = 'Deleted' "
         . 'WHERE pm_receiver_id = ' . $uid . ' '
         . 'AND pm_id = ' . $id . ' '
         . "AND pm_type = 'Received' ";
         
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   deleteSentMessage
  *
  * Description
  *   Deletes a single message
  *
  * Input
  *   $uid - user id
  *   $id - message id
  *
  * Output
  *
  *
  *******************************************************************************************/
  function deleteSentMessage($uid, $id)
  {
    $uid = $this->dbh->sql_safe($uid);
    $id = $this->dbh->sql_safe($id);
         
    $sql = 'UPDATE private_message '
         . "SET pm_status = 'Deleted' "
         . 'WHERE pm_sender_id = ' . $uid . ' '
         . 'AND pm_id = ' . $id . ' '
         . "AND pm_type = 'Sent' ";
         
    $this->dbh->execute($sql);
    
    return;
  }
  
  /*******************************************************************************************
  * Name
  *   getBanned
  *
  * Description
  *   Gets all the users this user has banned
  *
  * Input
  *   $uid - user id
  *
  * Output
  *   array - banned users
  *
  *******************************************************************************************/
  function getBanned($uid)
  {
    $uid = $this->dbh->sql_safe($uid);
    
    $sql = 'SELECT pmb.pmb_id AS PMB_ID, pmb.pmb_u_id AS PMB_U_ID, pmb.pmb_who AS PMB_WHO, UNIX_TIMESTAMP(pmb.pmb_dateCreated) AS PMB_DATECREATED, u.u_username AS U_USERNAME '
         . 'FROM private_message_ban pmb, users u '
         . 'WHERE pmb_u_id = ' . $uid . ' '
         . 'AND pmb_who = u.u_id ';
         
    return $this->dbh->query_all($sql);
  }
}