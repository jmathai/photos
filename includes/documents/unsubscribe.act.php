<?php
  $us =& CUser::getInstance();
  
  $userData = $us->userByKey($_GET['key']);
  
  //if(isset($userData['U_ID']))
  if($user_data !== false)
  {
    $GLOBALS['dbh']->execute('REPLACE INTO email_unsubscribe(eu_u_id, eu_ec_id) VALUES(' . $userData['U_ID'] . ',' . intval($_GET['ec_id']) . ')');
  }
  
  $url = '/?action=home.unsubscribe';
?>