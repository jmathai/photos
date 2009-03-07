<?php
  $gm = &CGroupManage::getInstance();
  
  $group_id = $_GET['group_id'];
  $key = $_GET['key'];
  $type = $_GET['type'];

  $status = 'Declined';
  if($type == 'accept')
  {
    $status = 'Accepted';
    $gm->join($_USER_ID, $group_id);
  }
  
  $gm->inviteResponse($group_id, $_USER_ID, $key, $status);
  
  if($type == 'accept')
  {
    $url = '/?action=group.home&group_id=' . $group_id;
  }
  else 
  {
    $url = '/?action=messaging.home';
  }
?>