<?php
 /*
  *******************************************************************************************
  * Name:  CSubscriptionManage.php
  *
  * General class for user subscriptions
  * This class performs read-only functions on the database.
  * 
  ******************************************************************************************
  */
class CSubscriptionManage
{
  
  /*
  *******************************************************************************************
  * Name
  *   addSubscription
  *
  * Description
  *   inserts a new subscription
  *
  * Input
  *   $params - array of key => value pairs to insert
  *
  * Output
  *   false or insert_id
  *
  ******************************************************************************************
  */
  function addSubscription($params = false)
  {
    $return = false;
    if($params !== false)
    {
      if(isset($params['s_email']))
      {
        include_once PATH_CLASS . '/CUser.php';
        include_once PATH_CLASS . '/CUserManage.php';
        $us =& CUser::getInstance();
        $usm=& CUserManage::getInstance();
        $otherUser = $us->find(trim($params['s_email']));
        if($otherUser !== false)
        {
          $params['s_userId'] = $otherUser['U_ID'];
          $params['s_username'] = $otherUser['U_USERNAME'];
          $usm->addFriend($params['s_userId'], $params['s_u_id']);
        }
      }
      
      $params['s_key'] = md5(uniqid(rand(), true));
      $params = $this->dbh->asql_safe($params);
      $keys = array_keys($params);
      
      $sql  = 'INSERT INTO user_subscriptions (' . implode(', ', $keys) . ') '
            . 'VALUES(' . implode(', ', $params) . ") "
            . "ON DUPLICATE KEY UPDATE s_status = 'active'";
      
      $this->dbh->execute($sql);
      $return = true;
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   deleteSubscriptions
  *
  * Description
  *   deletes a subscription
  *
  * Input
  *   $params - array of key => value critera for WHERE clause
  *
  * Output
  *   true or false
  *
  ******************************************************************************************
  */
  function deleteSubscriptions($params = false)
  {
    $return = false;
    if($params !== false)
    {
      $params = $this->dbh->asql_safe($params);
      
      $sql = 'UPDATE user_subscriptions '
           . 'SET s_status = \'deleted\' '
           . 'WHERE 1 ';

      foreach($params as $k => $v)
      {
        $sql .= 'AND ' . $k .' = ' . $v . ' ';
      }
      
      $this->dbh->execute($sql);
      $return = true;
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   blacklist
  *
  * Description
  *   blacklists a subscription
  *
  * Input
  *   $params - array of key => value critera for WHERE clause
  *
  * Output
  *   true or false
  *
  ******************************************************************************************
  */
  function blacklist($params = false)
  {
    $return = false;
    if($params !== false)
    {
      $params = $this->dbh->asql_safe($params);
      
      $sql = 'UPDATE user_subscriptions '
           . 'SET s_status = \'blacklist\' '
           . 'WHERE 1 ';

      foreach($params as $k => $v)
      {
        $sql .= 'AND ' . $k .' = ' . $v . ' ';
      }
      
      $this->dbh->execute($sql);
      $return = true;
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   addData
  *
  * Description
  *   inserts new subscription data
  *
  * Input
  *   $params - array of key => value pairs to insert
  *
  * Output
  *   false or insert_id
  *
  ******************************************************************************************
  */
  function addData($params = false)
  {
    $return = false;
    if($params !== false)
    {
      $params = $this->dbh->asql_safe($params);
      $keys = array_keys($params);
      
      $sql  = 'REPLACE INTO user_subscription_data (' . implode(', ', $keys) . ', sd_dateCreated, sd_dateId, sd_status) '
            . 'VALUES(' . implode(', ', $params) . ', NOW(), ' . date('mdY', NOW) . ', \'active\')';
      
      $this->dbh->execute($sql);
      $return = $this->dbh->insert_id();
    }
    
    return $return;
  }
 
  /*
  *******************************************************************************************
  * Name
  *   deleteData
  *
  * Description
  *   deletes a subscription data
  *
  * Input
  *   $params - array of key => value critera for WHERE clause
  *
  * Output
  *   true or false
  *
  ******************************************************************************************
  */
  function deleteData($params = false)
  {
    $return = false;
    if($params !== false)
    {
      $params = $this->dbh->asql_safe($params);
      
      $sql = 'UPDATE user_subscription_data '
           . 'SET sd_status = \'deleted\' '
           . 'WHERE 1 ';

      foreach($params as $k => $v)
      {
        $sql .= 'AND ' . $k .' = ' . $v . ' ';
      }
      
      $this->dbh->execute($sql);
      $return = true;
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
      $inst      = new $class;
      $inst->dbh =& $GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   CSubscriptionManage
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   Boolean
  ******************************************************************************************
  */
  function CSubscriptionManage()
  {
  }
}
?>