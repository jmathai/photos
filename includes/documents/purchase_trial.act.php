<?php
  $user_id_mask = isset($user_id_mask) ? $user_id_mask : $_GET['key'];
  $user_id_mask_array = (array)explode('.', $user_id_mask);
  $user_id = $user_id_mask[1];
  
  $urlMode = isset($urlMode) ? $urlMode : 'trial';
  
  $u =& CUser::getInstance();
  
  if(!isset($user_data))
  {
    $user_data = $u->inactive($user_id);
  }
  
  $persistent_login = false;
  $user_id = $user_data['U_ID'];
  $email = '';
  $username = $user_data['U_USERNAME'];
  $session_id = '';
  $account_type = 'TMP';
  
  $u->temporaryCookie($user_id);
  
  include_once PATH_DOCROOT . '/login_manual.act.php';
  
  switch($urlMode)
  {
    case 'renew':
      $url = '/?action=account.renew';      
      break;
    default:
      $url = '/?action=home.upgrade_form';
      break;
  }
?>