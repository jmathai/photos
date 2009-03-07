<?php
  $us =& CUser::getInstance();
  $usm=& CUserManage::getInstance();
  
  $user_id = intval($_POST['user_id']);
  $pageData = $us->page($user_id);
  
  $userData = $us->find($user_id);
  
  if($pageData['P_PASSWORD'] == $_POST['p_password'])
  {
    if($logged_in === false)
    {
      $_FF_SESSION->register($userData['U_USERNAME'] . '-auth', 1);
    }
    else
    {
      $usm->setPrefs($_USER_ID, array($userData['U_USERNAME'] . '-auth' => 1));
    }
    
    $url = $_POST['redirect'];
  }
  else
  {
    $url = '/users/' . $userData['U_USERNAME'] . '/password/failed/';
  }
?>