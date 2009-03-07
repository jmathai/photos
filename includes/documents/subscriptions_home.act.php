<?php
  $sm = &CSubscriptionManage::getInstance();
  
  $method = isset($_POST['method']) ? $_POST['method'] : 'push';
  
  foreach($_POST as $v)
  {
    if(strstr($v, '@'))
    {
      $sm->addSubscription(array('s_u_id' => $_USER_ID, 's_email' => $v, 's_method' => $method));
    }
    
    $message = 'added';
  }
  
  if(isset($_GET['email']))
  {
    $sm->deleteSubscriptions(array('s_u_id' => $_USER_ID, 's_email' => $_GET['email']));
    $message = 'deleted';
  }
  
  $url = '/?action=subscriptions.home&message=' . $message;
?>