<?php
  $u =& CUser::getInstance();
  
  $userData = $u->search(array('USER_ID' => $_GET['id'], 'PARENT_ID' => $_USER_ID));
  
  if(count($userData) > 0)
  {
    $user_id = $userData[0]['U_ID'];
    $email   = $userData[0]['U_EMAIL'];
    $username= $userData[0]['U_USERNAME'];
    $is_trial= $userData[0]['U_ISTRIAL'];
    $account_perm = $userData[0]['U_ACCOUNTTYPE'];
    
    include_once PATH_DOCROOT . '/login_manual.act.php';
    
    $url = '/';
  }
  else
  {
    $url = '/?action=manage.accounts&message=loginFailed';
  }
?>