<?php
  $pm = &CPrivateMessage::getInstance();
  $u_id = $_GET['u_id'];
  $id = $_GET['id'];
  $type = $_GET['type'];
  
  if($type == 'Received')
  {
    $pm->deleteReceivedMessage($u_id, $id);
    $url = '/?action=pm.inbox';
  }
  else if($type == 'Sent')
  {
    $pm->deleteSentMessage($u_id, $id);
    $url = '/?action=pm.outbox';
  }
?>