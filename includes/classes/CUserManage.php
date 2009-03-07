<?php
 /*
  *******************************************************************************************
  * Name:  CUserManage.php
  *
  * General class for user interaction.
  * This class performs write functions on the database.
  *
  * Usage:
  *   include_once('CUser.php');
  *   $user = new User;
  *   $recordset_groups = $user->getGroups($user_id);
  * 
  ******************************************************************************************
  */
class CUserManage
{
 /*
  *******************************************************************************************
  * Description
  *   Method to add   a user record
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  * Output
  *   $return           int/boolean   (user_id)
  ******************************************************************************************
  */
  function add($data)
  { 
    if(is_array($data))
    {
      if(array_key_exists('u_username', $data))
      {
        if($this->user->_checkUsername($data['u_username'], $data['u_email'], true, false))
        {
          include_once PATH_CLASS . '/CIdat.php'; // need for Idat
          include_once PATH_INCLUDE . '/functions.php'; // need for randomString()
          $idat =& CIdat::getInstance();
          
          $_randId          = $idat->nextID('fotoflix.user_id');
          $data['u_key'] = substr($_randId . randomString(), 0, 32);
          
          $status = $data['u_status'];
          
          //$data['u_password'] = md5($data['u_password']);
          $data = $this->dbh->asql_safe($data);
          $keys = array_keys($data);
          
          $sql  = 'INSERT INTO users(' . implode(', ', $keys) . ', u_dateCreated, u_dateModified) '
                . 'VALUES(' . implode(', ', $data) . ', NOW(), NOW())';
          
          $this->dbh->execute($sql);
          
          $return = $this->dbh->insert_id();
                    
          $sql = 'DELETE FROM user_incompletes WHERE u_key = ' . $data['u_key'] . ' ';
          $this->dbh->execute($sql);
          
          if($status == 'Pending')
          {
            $key = $this->dbh->sql_safe(md5(uniqid(rand(), true)));
            $this->dbh->execute($sql = 'INSERT INTO user_activation(ua_u_id, ua_key) VALUES(' . $return . ', ' . $key . ')');
          }
          return $return;
        }
        else
        {
          array_push($this->error, 'Username / Email (' . $data['u_username'] .' / ' . $data['u_email'] . ') contained invalid characters.');
        }
      }
      else
      {
        array_push($this->error, 'No username was specified.');
      }
    }
    else
    {
      array_push($this->error, 'Malformed data sent to update user.');
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to add a temp user record
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  * Output
  *   $return           int/boolean   (user_id)
  ******************************************************************************************
  */
  function addTemp($data)
  { 
    if(is_array($data))
    {
      if(array_key_exists('u_username', $data))
      {
        if($this->user->_checkUsername($data['u_username'], $data['u_email']))
        { 
          $data['u_password'] = md5($data['u_password']);
          $data = $this->dbh->asql_safe($data);
          $keys = array_keys($data);
          
          $sql  = 'INSERT INTO user_incompletes(' . implode(', ', $keys) . ', u_dateCreated) '
                . 'VALUES(' . implode(', ', $data) . ', NOW())';
                
          $this->dbh->execute($sql);
          
          $return = $this->dbh->insert_id();
                    
          return $return;
        }
        else
        {
          array_push($this->error, 'Username / Email (' . $data['u_username'] .' / ' . $data['u_email'] . ') contained invalid characters.');
        }
      }
      else
      {
        array_push($this->error, 'No username was specified.');
      }
    }
    else
    {
      array_push($this->error, 'Malformed data sent to update temp user.');
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to add an email record
  *
  * Input
  *   $email            string
  * Output
  *   $return           boolean
  ******************************************************************************************
  */
  function addEmail($email = false)
  {
    if(!empty($email))
    {
      $email = $this->dbh->sql_safe($email);
      $sql = 'REPLACE INTO email_addresses(email) VALUES(' . $email . ')';
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
  * Description
  *   Method to update a user record
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  * Output
  *   $return           int/boolean   (user_id)
  ******************************************************************************************
  */
  function update($data)
  {
    if(is_array($data))
    {
      if(array_key_exists('u_id', $data))
      {
        if($this->user->find($data['u_id']) || $this->user->inactive($data['u_id']))
        {
          //if(array_key_exists('u_username', $data) && array_key_exists('u_email', $data))
          if(isset($data['u_username']) && isset($data['u_email']))
          {
            $continue = $this->user->_checkUsername($data['u_username'], $data['u_email'], false);
          }
          else
          {
            $continue = true;
          }
          
          if($continue === true)
          {
            $user_id = $data['u_id'];
            $data = $this->dbh->asql_safe($data);
            
            $sql = 'UPDATE users SET ';
            
            foreach($data as $k => $v)
            {
              $sql .= $k .' = ' . $v . ', ';
            }
            
            $sql  .=  'u_dateModified = Now() '
                  .   'WHERE u_id = ' . $data['u_id'];
            
            $this->dbh->execute($sql);
            
            return $user_id;
          }
          else
          {
            array_push($this->error, 'Username / Email (' . $data['u_username'] . ' / ' . $data['u_email'] . ') was not found in our database or contained invalid characters.');
            return false;
          }
        }
        else
        {
          array_push($this->error, 'Does not exist.');
          return false;
        }
      }
      else
      {
        array_push($this->error, 'No user id was specified.');
        return false;
      }
    }
    else
    {
      array_push($this->error, 'Malformed data sent to update user.');
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Description
  *   Method to inactivate (delete) a user record
  *
  * Input
  *   $user_id        int   user_id
  * Output
  *   boolean
  ******************************************************************************************
  */
  function delete($user_id = false)
  {
    if(is_numeric($user_id) && $user_id != '')
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $this->dbh->execute("UPDATE users SET u_status = 'Disabled' WHERE u_id = {$user_id}");
      $this->dbh->execute("UPDATE ecom_recur SET er_status = 'Disabled' WHERE er_u_id = {$user_id}");
      return true;
    }
    else
    {
      array_push($this->error, 'No user id specified to delete user.');
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   updateDetails
  * Description
  *   Updates details for sub account
  *
  * Output
  *   boolean
  ******************************************************************************************
  */
  function updateSubAccount($data = false)
  {
    $retval = false;
    
    if(isset($data['usa_id']))
    {
      $accountId = $data['usa_id'];
      $data = $this->dbh->asql_safe($data);
      
      $sql = 'UPDATE user_sub_accounts SET ';
      
      foreach($data as $k => $v)
      {
        $sql .= $k . '=' . $v . ',';
      }
      
      $sql = substr($sql, 0, -1);
      
      $sql .= " WHERE usa_id = {$accountId}";
      
      $this->dbh->execute($sql);
      
      $retval = true;
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to re-activate a user record
  *
  * Input
  *   $user_id        int   user_id
  * Output
  *   boolean
  ******************************************************************************************
  */
  function enable($user_id = false)
  {
    if(is_numeric($user_id) && $user_id != '')
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $this->dbh->execute("UPDATE users SET u_status = 'Active' WHERE u_id = {$user_id}");
      $this->dbh->execute("UPDATE ecom_recur SET er_status = 'Active' WHERE er_u_id = {$user_id}");
      return true;
    }
    else
    {
      array_push($this->error, 'No user id specified to delete user.');
      return false;
    }
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to cancel a membership
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  * Output
  *   $return           int/boolean   (user_id)
  ******************************************************************************************
  */
  function cancel($data = false)
  {
    if(isset($data['uc_u_id']))
    {
      // CHECK RECURRING TABLE
      $data = $this->dbh->asql_safe($data);
      $rsCheck = $this->dbh->query('SELECT er_id FROM ecom_recur WHERE er_u_id = ' . $data['uc_u_id']);
      
      if($this->dbh->num_rows($rsCheck) > 0)
      {
        $this->dbh->execute("UPDATE ecom_recur SET er_status = 'Disabled' WHERE er_u_id = {$data['uc_u_id']}");
      }
      
      // do not update status since they can continue using the service through the end of their subscription
      //$this->dbh->execute("UPDATE users SET u_status = 'Cancelled' WHERE u_id = {$data['uc_u_id']}");
      
      $keys = array_keys($data);
      
      $sql  = 'INSERT INTO user_cancellations(' . implode(',', $keys) . ', uc_dateCreated) '
            . 'VALUES(' . implode(',', $data) . ', NOW())';
      
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
  * Description
  *   Method to update a user's profile
  *
  * Input
  *   $data             arr   (key => value pairs of field => data)
  * Output
  *   $return           int/boolean   (user_id)
  ******************************************************************************************
  */
  function updateProfile($userId = false, $profile = false)
  {
    if($userId !== false && $profile !== false)
    {
      $userId = intval($userId);
      $profile= $this->dbh->sql_safe($profile);
      $sql = 'REPLACE INTO user_profiles(p_u_id, p_profile) VALUES(' . $userId . ', ' . $profile . ')';
      $this->dbh->execute($sql);
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   updatePage
  * Description
  *   update page prefs
  * Output
  *   boolean
  ******************************************************************************************
  */
  function updatePage($data = false)
  {
    if($data['p_u_id'] !== false)
    {
      $data   = $this->dbh->asql_safe($data);
      include_once PATH_INCLUDE . '/functions.php';
      if(isset($data['p_description']))
      {
        $data['p_description'] = sanitize($data['p_description']);
      }
      
      if(!empty($data['p_password']))
      {
        // updating password
        $sql = 'SELECT u_username AS U_USERNAME FROM users where u_id = ' . $data['p_u_id'] . ' ';
        $rs = $this->dbh->query_first($sql);
        
        $sql = 'DELETE FROM user_prefs '
             . "WHERE up_name = '" . $rs['U_USERNAME'] . "-auth' ";
        
        $this->dbh->execute($sql);
      }
      
      $keys = array_keys($data);
      $sql = 'REPLACE INTO user_pages(' . implode(',', $keys) . ') VALUES(' . implode(',', $data) . ')';
      
      $this->dbh->execute($sql);
    }
    return true;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to update a user password
  *
  * Input
  *   $user_id
  *   $password
  * Output
  *   boolean
  ******************************************************************************************
  */
  function updatePassword($user_id, $password)
  {
    $user_id = $this->dbh->sql_safe($user_id);
    $password= $this->dbh->sql_safe($this->user->_encrypt($password));
    
    $sql = 'UPDATE users SET u_password = ' . $password . ' WHERE u_id = ' . $user_id;
    $this->dbh->execute($sql);
    
    return true;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to add a friend
  *
  * Input
  *   $userId
  *   $friendId
  * Output
  *   boolean
  ******************************************************************************************
  */
  function addFriend($userId = false, $friendId = false, $status = 'Requested')
  {
    if($userId > 0 && $friendId > 0 && $userId != $friendId)
    {
      $userId   = intval($userId);
      $friendId = intval($friendId);
      $status   = $this->dbh->sql_safe($status);
      $sql = "REPLACE INTO user_friends(uf_u_id, uf_friendId, uf_status) VALUES({$userId}, {$friendId}, {$status})";
      
      $this->dbh->execute($sql);
      
      return true;
    }
    
    return false;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to add a friend
  *
  * Input
  *   $userId
  *   $friendId
  * Output
  *   int
  ******************************************************************************************
  */
  function addMessage($userId = false, $senderId = false, $subject = false, $message = false, $replyTo = 0)
  {
    $retval = 0;
    
    if($userId !== false && $senderId !== false && $subject !== false && $message !== false)
    {
      $userId = intval($userId);
      $replyTo = intval($replyTo);
      $senderId = intval($senderId);
      $subject = $this->dbh->sql_safe($subject);
      $message = $this->dbh->sql_safe($message);
      $sql = 'INSERT INTO user_inbox(ui_u_id, ui_senderId, ui_replyTo, ui_subject, ui_message, ui_dateCreated, ui_dateModified) '
           . "VALUES({$userId}, {$senderId}, {$replyTo}, {$subject}, {$message}, NOW(), NOW())";
      
      $this->dbh->execute($sql);
      
      $retval = $this->dbh->insert_id();
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to mark a message as a friend
  *
  * Input
  *   $userId
  *   $messageId
  * Output
  *   boolean
  ******************************************************************************************
  */
  function markMessage($userId = false, $messageId = false, $status = 'Read')
  {
    $retval = false;
    if($userId !== false && $messageId !== false)
    {
      $userId = intval($userId);
      $messageId = intval($messageId);
      $status = $this->dbh->sql_safe($status);
      $sql = "UPDATE user_inbox SET ui_status = {$status} WHERE ui_id = {$messageId} AND ui_u_id = {$userId}";
      $this->dbh->execute($sql);
      
      $retval = true;
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to add activity
  *
  * Input
  *   $userId
  *   $elementId
  *   $type
  * Output
  *   int
  ******************************************************************************************
  */
  function addActivity($userId = false, $elementId = false, $type = false, $extra1 = null, $extra2 = null, $extra3 = null, $extra4 = null)
  {
    $retval = 0;
    
    if(intval($userId) > 0 && $elementId !== false && $type !== false)
    {
      $userId = intval($userId);
      $elementId = intval($elementId);
      $type = $this->dbh->sql_safe($type);
      $extra1 = $this->dbh->sql_safe($extra1); // username of owner of the activity
      $extra2 = $this->dbh->sql_safe($extra2);
      $extra3 = $this->dbh->sql_safe($extra3);
      $extra4 = $this->dbh->sql_safe($extra4);
      $sql = 'REPLACE INTO user_activities(ua_u_id, ua_element_id, ua_type, ua_extra_1, ua_extra_2, ua_extra_3, ua_extra_4, ua_dateCreated) '
           . "VALUES({$userId}, {$elementId}, {$type}, {$extra1}, {$extra2}, {$extra3}, {$extra4}, NOW())";
      
      $this->dbh->execute($sql);
      
      $retval = $this->dbh->insert_id();
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to delete activity
  *
  * Input
  *   $userId
  *   $elementId
  *   $type
  * Output
  *   bool
  ******************************************************************************************
  */
  function deleteActivity($userId = false, $elementId = false, $type = false)
  {
    if($userId !== false && $elementId !== false && $type !== false)
    {
      $userId = intval($userId);
      if(is_array($elementId))
      {
        $elementId = $this->dbh->asql_safe($elementId);
        $elementId = implode(',', $elementId);
      }
      else
      {
        $elementId = intval($elementId);
      }
      $type = $this->dbh->sql_safe($type);
      $sql = "DELETE FROM user_activities WHERE ua_u_id = {$userId} AND ua_element_id IN({$elementId}) AND ua_type = {$type}";
      
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
  * Description
  *   Method to add a incomplete user reason
  *
  * Input
  *   $data
  * Output
  *   boolean
  ******************************************************************************************
  */
  function addIncompleteReason($data = false)
  {
    if(isset($data['uir_u_id']))
    {
      if(!isset($data['uir_customResponse']))
      {
        $data['uir_customResponse'] = '';
      }
      $keys = implode(',', array_keys($data));
      $data = $this->dbh->asql_safe($data);
      $values = implode(',', $data);
      $sql = "INSERT INTO user_incomplete_responses({$keys}, uir_dateCreated) VALUES({$values}, NOW()) ON DUPLICATE KEY UPDATE uir_customResponse={$data['uir_customResponse']}";
      $this->dbh->execute($sql);
      return true;
    }
    return false;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to reset a users password
  *
  * Input
  *   $user_id
  * Output
  *   string (new password)
  ******************************************************************************************
  */
  function resetPassword($user_id)
  {
    $new_password = $this->_generatePassword();
    $user_id = $this->dbh->sql_safe($user_id);
    $password= $this->dbh->sql_safe($this->user->_encrypt($new_password));
    
    $sql = 'UPDATE users SET u_password = ' . $password . ' WHERE u_id = ' . $user_id;
    $this->dbh->execute($sql);
    
    return $new_password;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to activate registration
  *
  * Input
  *   $key        string
  * Output
  *   bool
  ******************************************************************************************
  */
  function activate($key)
  {
    $key = $this->dbh->sql_safe($key);
    
    $ar1 = $this->dbh->fetch_assoc(
              $this->dbh->query('SELECT ua_u_id AS U_ID FROM user_activation WHERE ua_key = ' . $key)
            );
    
    if(count($ar1) == 1)
    {
      $expire_date = $this->dbh->sql_safe(date('Y-m-d', strtotime(FF_ACCT_TRIAL_LENGTH)));
      $this->dbh->execute("UPDATE users SET u_status = 'active', u_dateExpires =  {$expire_date} WHERE u_id = '{$ar1['U_ID']}'");
      $this->dbh->execute('DELETE FROM user_activation WHERE ua_key = ' . $key);
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
  *   generateToken
  * Description
  *   Generate a user token
  *
  * Input
  *   $username, $password, $expires
  *   $key, $session_hash, $expires
  *   $session_hash, $expires
  * Output
  *   string/bool
  ******************************************************************************************
  */
  function generateToken()
  {
    include_once PATH_CLASS . '/CUser.php';
    $us = CUser::getInstance();
    
    $retval = $userData = false;
    $arg0 = func_get_arg(0);
    
    $token = md5(uniqid(rand(), true)) . md5(uniqid(rand(), true));
    /*
      conditional MUST remain in order
      1
        user has an account
        key and session hash are passed in then set their session and find the person
      2
        user does NOT have an account
        session hash is passed in then the user is not logged in but has a session
      3 (Not Used?)
        user has an account
        username/password combination is passed in then find that user
    */
    if(strlen($arg0) == 32)
    {
      $userData = $us->find($arg0);
      $sessHash = func_get_arg(1);
      if(func_num_args() == 2)
      {
        $expires = intval(func_get_arg(2));
      }
      else
      {
        $expires = NOW+1800;
      }
    }
    else
    if(strlen($arg0) == 13)
    {
      $userData == false;
      $expires  = func_num_args() == 2 ? (NOW+1800) : intval(func_get_arg(2));
    }
    else
    if($arg0 !== false)
    {
      $arg1 = func_get_arg(1);
      $userData = $us->find($arg0, $arg1);
      $expires  = func_num_args() == 2 ? (NOW+1800) : intval(func_get_arg(2));
    }
    
    /*
      1
        user has an account
        insert a token with the user id and session hash
      2
        insert a token with a session hash and no user id (0)
    */
    if($userData !== false)
    {
      $userId = intval($userData['U_ID']);
      $sessHash = $this->dbh->sql_safe($sessHash);
      $expires  = $expires === false ? (NOW+1800) : intval($expires);
      $sql = "INSERT INTO user_tokens(ut_token, ut_u_id, ut_sess_hash, ut_expires) VALUES('{$token}', {$userId}, {$sessHash}, {$expires})";
      
      $this->dbh->execute($sql);
      
      $retval = $token;
    }
    else
    {
      $userId = 0;
      $userSessHash = $this->dbh->sql_safe($arg0);
      $expires  = $expires === false ? (NOW+1800) : intval($expires);
      $sql = "INSERT INTO user_tokens(ut_token, ut_u_id, ut_sess_hash, ut_expires) VALUES('{$token}', {$userId}, {$userSessHash}, {$expires})";
      
      $this->dbh->execute($sql);
      
      $retval = $token;
    }
    
    return $retval;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to add a users blog
  *
  * Input
  *   
  * Output
  *   
  ******************************************************************************************
  */
  function addBlog($data = false)
  {
    $return = false;
    if(isset($data['ub_u_id']))
    {
      $data = $this->dbh->asql_safe($data);
      $keys = array_keys($data);
      $sql  = 'INSERT INTO user_blogs(' . implode(',', $keys) . ') '
            . 'VALUES(' . implode(',', $data) . ')';
      
      $this->dbh->execute($sql);
      
      $return = $this->dbh->insert_id();
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to update a users blog
  *
  * Input
  *   
  * Output
  *   
  ******************************************************************************************
  */
  function updateBlog($data = false)
  {
    $return = false;
    if(isset($data['ub_id']))
    {
      $data = $this->dbh->asql_safe($data);
      $sql  = 'UPDATE user_blogs SET ';
      foreach($data as $k => $v)
      {
        $sql .= $k . '=' . $v . ', ';
      }
      
      $sql = substr($sql, 0, -2);
      $sql .= ' WHERE ub_id = ' . $data['ub_id'];
      
      $this->dbh->execute($sql);
      
      $return = true;
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to delete a users blog
  *
  * Input
  *   
  * Output
  *   
  ******************************************************************************************
  */
  function deleteBlog($blog_id = false, $user_id = false)
  {
    $return = false;
    if($blog_id !== false && $user_id !== false)
    {
      $blog_id = $this->dbh->sql_safe($blog_id);
      $user_id = $this->dbh->sql_safe($user_id);
      $this->dbh->execute('DELETE FROM user_blogs WHERE ub_id = ' . $blog_id . ' AND ub_u_id = ' . $user_id);
      
      $return = true;
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to add entries for earn free space
  *
  * Input
  *   $data        array
  * Output
  *   bool
  ******************************************************************************************
  */
  function addEarnSpace($data = false)
  {
    if($data !== false)
    {
      $data = $this->dbh->asql_safe($data);
      $keys = array_keys($data);
      
      $sql  = 'INSERT INTO user_earn_space(' . implode(', ', $keys) . ', ues_dateModified, ues_dateCreated) '
            . 'VALUES(' . implode(', ', $data) . ', NOW(), NOW())';
      
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
  * Description
  *   Method to execute earn space transaction
  *
  * Input
  *   $data        array
  * Output
  *   bool
  ******************************************************************************************
  */
  function addSpace($reference_id = false, $invalid = false)
  {
    if($reference_id !== false && $invalid === false)
    {
      $earn_space = $this->user->getEarnSpace($reference_id);
      
      if($earn_space !== false)
      {
        $user_id      = $this->dbh->sql_safe($earn_space['S_U_ID']);
        $reference_id = $this->dbh->sql_safe($reference_id);
        $sql1 = 'UPDATE users SET u_spaceTotal = u_spaceTotal + ' . FF_FREE_SPACE . ' WHERE u_id = ' . $user_id;
        $this->dbh->execute($sql1);
        
        $sql2 = "UPDATE user_earn_space SET ues_status = 'accepted' WHERE ues_reference = {$reference_id}";
        $this->dbh->execute($sql2);
        
        return $earn_space['S_U_ID'];
      }
      else
      {
        return false;
      }
    }
    else
    if($reference_id !== false && $invalid === true)
    {
      $reference_id = $this->dbh->sql_safe($reference_id);
      $sql = "UPDATE user_earn_space SET ues_status = 'invalid' WHERE  ues_reference = {$reference_id}";
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
  * Description
  *   Set a user preference
  *
  * Input
  *   $user_id    int
  *   $key        string
  * Output
  *   bool
  ******************************************************************************************
  */
  function setPrefs($user_id = false, $prefs = false)
  {
    if(intval($user_id) > 0 && is_array($prefs))
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $prefs = $this->dbh->asql_safe($prefs);      
      $sql = 'REPLACE INTO user_prefs(up_u_id, up_name, up_value) VALUES ';
      
      $continue = false;
      foreach($prefs as $k => $v)
      {
        $k = $this->dbh->sql_safe($k); // do not use sql_safe because we don't want single quotes around this
        $sql .= "({$user_id}, {$k}, {$v}), "; // $v is already safe'd using asql_safe above
        $continue = true;
      }
      
      if($continue === true)
      {
        $sql = substr($sql, 0, -2);
        $this->dbh->execute($sql);
      }
    }
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Remove a user preference
  *
  * Input
  *   $user_id    int
  *   $key        string
  * Output
  *   bool
  ******************************************************************************************
  */
  function removePrefs($user_id = false, $prefs = false)
  {
    if($user_id !== false && is_array($prefs))
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $prefs = $this->dbh->asql_safe($prefs);      
      foreach($prefs as $v)
      {
        $sql = 'DELETE FROM user_prefs WHERE up_u_id = ' . $user_id . ' AND up_name = ' . $v; // v is already sql_safed above
        $this->dbh->execute($sql);
      }
    }
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Deprecated!
  *   Method to check if FotoPage exists and write file to server or delete file from server
  *
  * Input
  *   $user_id    int
  *   $key        string
  * Output
  *   bool
  ******************************************************************************************
  */
  function setFotoPage($user_id = false, $key = false)
  {
    return true;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to retrieve activation key (calls method from CUser class)
  *
  * Input
  *   $id        int/string (user_id / key)
  * Output
  *   int
  ******************************************************************************************
  */
  function getEarnSpace($id)
  {
    return $this->user->getEarnSpace($id);
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to retrieve activation key (calls method from CUser class)
  *
  * Input
  *   $id        int/string (user_id / key)
  * Output
  *   int
  ******************************************************************************************
  */
  function activationKey($id)
  {
    return $this->user->activationKey($id);
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Method to retrieve error(s).  Last error or array of all errors.
  *
  * Input
  *   string
  * Output
  *   mixed
  ******************************************************************************************
  */
  function error($type = 'last')
  {
    if($type == 'last')
    {
      return $this->error[(count($this->error) - 1)];
    }
    else
    if($type == 'all')
    {
      return $this->error;
    }
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Generate new random password
  *
  * Input
  *   None
  * Output
  *   string
  ******************************************************************************************
  */
  function _generatePassword()
  {
    $chars_array = array();
    for($i = 48; $i <= 57; $i++)
    {
      $chars_array[] = chr($i);
    }
    
    for($i = 97; $i <= 122; $i++)
    {
      $chars_array[] = chr($i);
    }
    
    $cnt_chars_array = count($chars_array) - 1;
    
    $password = array();
    for($i = 0; $i < 4; $i++)
    {
      $password[] = $chars_array[rand(0, $cnt_chars_array)];
    }
    
    $return = implode('', $password);
    
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
      include_once PATH_CLASS . '/CUser.php';
      
      $inst      = new $class;
      $inst->dbh =&$GLOBALS['dbh'];
      $inst->user=&CUser::getInstance();
      $inst->error= array();
    }
    
    return $inst;
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   Boolean
  ******************************************************************************************
  */
  function CUserManage()
  {
    /*include_once PATH_CLASS . '/CUser.php';
    $this->dbh =& $GLOBALS['dbh'];
    $this->user =& new CUser;
    $this->error= array();*/
  }
}
?>
