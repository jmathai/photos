<?php
  if(strlen($_GET['key']) == 32)
  {
    $us =& CUser::getInstance();
    $key = $_GET['key'];
    
    $userData = $us->userByHash($key);
    
    if($userData !== false)
    {
      $user_id  = $userData['U_ID'];
      $email    = $userData['U_EMAIL'];
      $username = $userData['U_USERNAME'];
      $account_perm = $userData['U_ACCOUNTTYPE'];
      $is_trial = $userData['U_ISTRIAL'];
      
      include_once PATH_DOCROOT . '/login_manual.act.php';
      
      if(isset($_GET['redirect']))
      {
        $url = $_GET['redirect'];
      }
      else
      {
        $url = '/';
      }
    }
  }
  else
  if($logged_in === true)
  {
    $url = '/';
  }
  
  if(!isset($url))
  {
    $url = '/?action=confirm.main&type=login_failed';
  }
?>