<?php
  $pm = &CPrivateMessage::getInstance();
  $pm_id = $_GET['PM_ID'];
  $type = $_GET['type'];
  
  if($type == 'received')
  {
    $pm->deleteReceivedMessage($_USER_ID, $pm_id);
    $url = '/?action=messaging.home';
  }
  else if($type == 'sent')
  {
    $pm->deleteSentMessage($_USER_ID, $pm_id);
    $url = '/?action=messaging.home&tab=sent';
  }
?>