<?php
  $u =& CUserManage::getInstance();
  
  $key = $_SERVER['QUERY_STRING'];
  
  $act_data = $u->activationKey($key);
  
  if($act_data)
  {
    if($u->activate($key))
    {
      $user_data = $u->user->find($act_data['U_ID']);
      $persistent_login = false;
      $user_id          = $user_data['U_ID'];
      $username         = $user_data['U_USERNAME'];
      $account_perm     = $user_data['U_ACCOUNTTYPE'];
      $email            = $user_data['U_EMAIL'];
      $is_trial         = $user_data['U_ISTRIAL'];
      
      include_once PATH_DOCROOT . '/login_manual.act.php';
      
      $url = '/?action=fotobox.fotobox_main';
    }
    else
    {
      $url = '/?action=confirm.main&type=user_key_activated';
    }
  }
  else
  {
    $url = '/?action=confirm.main&type=user_key_not_found';
  }
?>