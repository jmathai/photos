<?php
  $logged_in = false;
  
  if(isset($_COOKIE[FF_SESSION_KEY])) // check if the cookie is set
  {
    $_FF_SESSION = new CSession($_COOKIE[FF_SESSION_KEY]);
    $sess_id = $_FF_SESSION->start(true);
    if($sess_id > 0) // check if the cookie has a matching session
    {
      $_USER_ID   = intval($_FF_SESSION->value('user_id'));
      include_once PATH_CLASS . '/CUser.php';
      $u =& CUser::getInstance();
      $userData = $u->find($_USER_ID); // retrieve user from database (needed to check to see if the user is expired)
      
      if($userData !== false) // if the user is not expired continue
      {
        $_USER_ACCOUNT = $_FF_SESSION->value('account_type');
        $_USER_PERM = permission($_USER_ACCOUNT);
        
        if($_USER_ID > 0)
        {
          $logged_in = true;
        }
      }
      else
      if($_USER_ID > 0 && $_FF_SESSION->value('temp_user_id') == '') // if a user id is associated with this session then send them to the update billing form which tells them their account is expired
      {
        $_FF_SESSION->register('temp_user_id', $_USER_ID);
        $url = 'https://' . FF_SERVER_NAME . '/?action=account.billing_update_form';
        header('Location: ' . $url);
        die();
      }
    }
    else // force a new session - cookie exists but no valid session
    {
      $_FF_SESSION = new CSession(false, false);
      $_FF_SESSION->start(false, false);
      
      $_USER_ID = $_USER_PERM = 0;
    }
  }
  else
  if(!isset($session_bypass)) // normal user (do not bypass a session creation)
  {
    $_FF_SESSION = new CSession(false, false);
    $_FF_SESSION->start(false, false);
    
    $_USER_ID = $_USER_PERM = 0;
  }
  else // bypass the session
  {
    $_USER_ID = $_USER_PERM = 0;
  }
