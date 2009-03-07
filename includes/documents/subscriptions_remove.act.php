<?php
  $sm = &CSubscriptionManage::getInstance();
  
  if(strlen($_GET['key']) == 32)
  {
    $sm->deleteSubscriptions(array('s_key' => $_GET['key']));
  }
  
  $url = '/?action=confirm.main&type=subscription_removed';
?>