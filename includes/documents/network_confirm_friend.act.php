<?php
  $um =& CUserManage::getInstance();
  $u  =& CUser::getInstance();
  
  $um->addFriend($_USER_ID, $_GET['friendId'], 'Confirmed');
  $um->addFriend($_GET['friendId'], $_USER_ID, 'Confirmed');
  
  $userData = $u->find($_USER_ID);
  $friendData = $u->find($_GET['friendId']);
  
  $um->addActivity($_USER_ID, $friendData['U_ID'], 'newFriend', $userData['U_USERNAME'], $friendData['U_USERNAME']);
  $um->addActivity($friendData['U_ID'], $_USER_ID, 'newFriend', $friendData['U_USERNAME'], $userData['U_USERNAME']);
  
  $url = '/users/' . $userData['U_USERNAME'] . '/friends/';
?>