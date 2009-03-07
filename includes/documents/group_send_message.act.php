<?php
  $g = &CGroup::getInstance();
  $pm = &CPrivateMessage::getInstance();
  
  $group_id = $_GET['group_id'];
  $subject = $_POST['_subject'];
  $message = $_POST['_message'];
  $user_id = $_USER_ID;
  $who = $g->members(array('GROUP_ID' => $group_id));
  
  $pm->send($user_id, $who, $subject, $message);

  $url = '/?action=group.send_message&group_id=' . $group_id . '&message=success';
?>