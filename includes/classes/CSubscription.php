<?php
 /*
  *******************************************************************************************
  * Name:  CSubscription.php
  *
  * General class for user subscriptions
  * This class performs read-only functions on the database.
  * 
  ******************************************************************************************
  */
class CSubscription
{
 /*
  *******************************************************************************************
  * Name
  *   getSubscriptions
  * Description
  *   get a list of subscriptions for a user
  *
  * Input
  *   userId
  * Output
  *   array
  ******************************************************************************************
  */
  function getSubscriptions($userId)
  {
    $sql = 'SELECT s_key AS S_KEY, s_u_id AS S_U_ID, s_userId AS S_USERID, s_username AS S_USERNAME, s_email AS S_EMAIL '
         . 'FROM user_subscriptions '
         . 'WHERE s_u_id = ' . intval($userId) . " AND s_status = 'active'";
    
    return $this->dbh->query_all($sql);
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getSubscriptionsWithData
  * Description
  *   get subscriptions with data
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function getSubscriptionsForReport($time = false)
  {
    $retval = array();
    if($time !== false)
    {
      $startDate = $this->dbh->sql_safe(date('Y-m-d 00:00:00', $time));
      $endDate = $this->dbh->sql_safe(date('Y-m-d 23:59:59', $time));
      $sql = 'SELECT s_key AS S_KEY, s_email AS S_EMAIL, s_method AS S_METHOD, sd_id AS S_ID, sd_u_id AS S_U_ID, sd_elementType AS S_ELEMENTTYPE, sd_element_id AS S_ELEMENTID, sd_thumbnail AS S_THUMBNAIL, sd_dateCreated AS S_DATECREATED, sd_dateId AS S_DATEID, u_username AS S_USERNAME '
           . 'FROM (user_subscriptions AS s INNER JOIN user_subscription_data AS sd ON s.s_u_id = sd.sd_u_id) LEFT JOIN users AS u on s.s_u_id = u.u_id '
           . "WHERE sd_dateCreated BETWEEN {$startDate} AND {$endDate} AND s_status = 'active' AND sd_status = 'active' "
           . 'ORDER BY s_key, sd_dateCreated DESC ';
      
      $data = $this->dbh->query_all($sql);
      foreach($data as $v)
      {
        if(!isset($retval[$v['S_U_ID']]))
        {
          $retval[$v['S_U_ID']] = array();
        }
        
        $retval[$v['S_U_ID']][] = $v;
      }
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
  *   CSubscription
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   Boolean
  ******************************************************************************************
  */
  function CSubscription()
  {
  }
}
?>