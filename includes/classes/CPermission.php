<?php
 /*
  *******************************************************************************************
  * Name:  CPermission.php
  *
  * Class to perform permission checks for user -> action.
  *
  * Usage:
  *   $perm =& CPermission::getInstance();
  *   $user_data = $user->hasPermission($sub_account_id, $zone_action);
  * 
  ******************************************************************************************
  */
class CPermission
{
 /*
  *******************************************************************************************
  * Name
  *   hasPermission
  * Description
  *   Method to get inactive user info
  *   Use CPermission::hasPermission to get an inactive user
  *   Uses an internal cache to prevent duplicate queries
  * Input
  *   $sub_account, $zone_action[, $permission]
  * Output
  *   array/boolean
  ******************************************************************************************
  */
  static function hasPermission()
  {
    $retval   = false;
    $cntArgs  = func_num_args();
    
    $accountId  = func_get_arg(0);
    $zoneAction = func_get_arg(1);
    
    if($cntArgs >= 2)
    {
      $accountId_safe   = $this->dbh->sql_safe($accountId);
      $zoneAction_safe  = $this->dbh->sql_safe($zoneAction);
      $sql  = 'SELECT up_permission AS P_PERMISSION '
            . 'FROM user_permissions '
            . 'WHERE up_usa_id = ' . $accountId_safe . ' AND up_action = ' . $zoneAction_safe;
      
      if($cntArgs < 3) // return array of permissions
      {
        $cacheKey = $accountId . '-' . $zoneAction; // set cacheKey to be accountId-zoneAction
        
        if(!isset($this->cache[$cacheKey])) // if request does not exist in the cache then query the database
        {
          $perms = $this->dbh->query_first($sql);
          $perm  = intval($perms['P_PERMISSION']);
          $retval = array(
                      'C' => (($this->permMasks['C'] & $perm) > 0 ? true : false),
                      'R' => (($this->permMasks['R'] & $perm) > 0 ? true : false),
                      'U' => (($this->permMasks['U'] & $perm) > 0 ? true : false),
                      'D' => (($this->permMasks['D'] & $perm) > 0 ? true : false)
                    );
          
          //echo "for $zoneAction create\n{$this->permMasks['U']} & $perm = " . ($this->permMasks['U'] & $perm) . "\n";
          $this->cache[$cacheKey] = $retval;
          //echo "pull from database CRUD<br/>";
        }
        else // if the request exists in the cache the return the cached value
        {
          $retval = $this->cache[$cacheKey];
          //echo "pull from database $cacheKey<br/>";
        }
      }
      else // return single boolean permission
      {
        $maskKey = func_get_arg(2);
        $cacheKeyA = $accountId . '-' . $zoneAction;
        $cacheKeyB = $cacheKeyA . '-' . $maskKey;
        
        if(isset($this->cache[$cacheKeyB])) // if the specific key has been requested then return the cached value
        {
          $retval = $this->cache[$cacheKeyB];
          //echo "pull from cache $cacheKeyB<br/>";
        }
        else
        if(isset($this->cache[$cacheKeyA])) // if the array key has been requested then return the cached key value
        {
          $retval = $this->cache[$cacheKeyA][$maskKey];
          
          $this->cache[$cacheKeyB] = $retval;
          //echo "pull from cache $cacheKeyA<br/>";
        }
        else // no request with this information ... return from database and cache
        {
          $perms = $this->dbh->query_first($sql);
          $perm  = $perms['P_PERMISSION'];
          
          $retval = (($this->permMasks[$maskKey] & $perm) > 0 ? true : false);
          
          $this->cache[$cacheKeyB] = $retval;
          //echo "pull from database $maskKey<br/>";
        }
      }
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   setPermission
  * Description
  *   Sets a permission
  * Input
  *   $accountId  (int)
  *   $action     (string)
  *   $bit        (string) C R U D
  *   $value      (int) 1 = on, 0 = off
  * Output
  *   boolean
  ******************************************************************************************
  */
  function setPermission($accountId = false, $action = false, $bit = false, $value = false)
  {
    $retval = false;
    
    if($accountId !== false)
    {
      $accountId = $this->dbh->sql_safe($accountId);
      $action = $this->dbh->sql_safe($action);
      $sql = "SELECT up_id AS P_ID, up_permission AS P_PERMISSION FROM user_permissions WHERE up_usa_id = {$accountId} AND up_action = {$action}";
      
      $tmp = $this->dbh->query_first($sql);
      $currentId = $tmp['P_ID'];
      $currentPerm = $tmp['P_PERMISSION'];
      
      $maskPerm = $this->permMasks[$bit];
      
      if($value == 1) // turn bit on
      {
        $updatePerm = $currentPerm | $maskPerm;
        
        echo "turn on from $currentPerm to $updatePerm using $maskPerm";
      }
      else
      if($value == 0) // turn bit off
      {
        $maskPerm = $maskPerm ^ 15;
        $updatePerm = $currentPerm & $maskPerm;
        echo "turn off from $currentPerm to $updatePerm using $maskPerm";
      }
      
      $currentId_safe = $this->dbh->sql_safe($currentId);
      $updatePerm = $this->dbh->sql_safe($updatePerm);
      $sql = "INSERT INTO user_permissions (up_usa_id, up_action, up_permission) VALUES ({$accountId}, {$action}, {$updatePerm}) ON DUPLICATE KEY UPDATE up_permission={$updatePerm}";
      //UPDATE user_permissions SET up_permission = {$updatePerm} WHERE up_id = {$currentId_safe}
      $this->dbh->execute($sql);
      
      $retval = true;
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   types
  * Description
  *   Retrieve permission types
  * Output
  *   array
  ******************************************************************************************
  */
  function types()
  {
    $sql = "SELECT upt_name AS NAME, upt_defaultValue AS VALUE FROM user_permission_types WHERE upt_active = 'Y' ORDER BY upt_name";
    
    return $this->dbh->query_all($sql);
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
      $inst->cache  = array(); // initialize internal cache
      $inst->permMasks = array('C' => 1, 'R' => 2, 'U' => 4, 'D' => 8);
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
  function CPermission()
  {
  }
}
?>