<?php
  $usm =& CUserManage::getInstance();
  $sm  =& CSubscriptionManage::getInstance();
  
  $data = array('p_u_id' => $_USER_ID, 'p_password' => $_POST['p_password'], 'p_description' => $_POST['p_description']);
  
  $_p = htmlSafeArray($data);
  
  // delete all subscriptions then add 'em
  $subscriptions = (array)explode("\n", $_POST['addSubscription']);
  $sm->deleteSubscriptions(array('s_u_id' => $_USER_ID));
  foreach($subscriptions as $v)
  {
    if(strstr($v, '@'))
    {
      $sm->addSubscription(array('s_u_id' => $_USER_ID, 's_email' => $v, 's_method' => 'push'));
    }
  }
  
  $usm->updatePage($_p);
  
  $url = $_POST['redirect'];
?>