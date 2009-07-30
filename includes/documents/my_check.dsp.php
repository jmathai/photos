<?php
  include_once PATH_CLASS . '/CUser.php';
  
  $u =& CUser::getInstance();
  
  $userData = $u->my($username);
  
  if($userData !== false)
  {
    $user_id  = $userData['U_ID'];
    $username = $userData['U_USERNAME'];
    
    $pageData = $u->page($user_id);
    
    $displayName = $userData['U_USERNAME'];
    $displayDescription = $pageData['P_DESCRIPTION'];
  
    // check auth
    if($pageData['P_PASSWORD'] != '')
    {
      if($logged_in === false)
      {
        $auth = $_FF_SESSION->value($username . '-auth');
        if($auth != 1)
        {
          // user is not authenticated to view
          $subaction = 'password';
        }
      }
      else
      { 
        if($_USER_ID != $user_id)
        {
          $auth = $u->pref($_USER_ID, $username . '-auth');
          if($auth != 1)
          {
            $subaction = 'password';
          }
        }
      }
    }
  }
  else
  {
    $subaction = 'dne';
  }
?>
