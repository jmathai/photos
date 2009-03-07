<?php
  include_once PATH_CLASS . '/CTag.php';
  $t =& CTag::getInstance();
  
  if(strncmp($action, 'my.', 3) == 0)
  {
    $u_id = $user_id;
    $privacy = 'public';
  }
  else
  {
    $u_id = $_USER_ID;
    $privacy = false;
  }
  
  $userTags = $t->tags($u_id, 'WEIGHT', false, false, $privacy);
  
  echo '<script language="javascript" type="text/javascript"> var userTags = new Array(); ';
  foreach($userTags as $v)
  {
    if($v['TAG'] != '')
    {
      echo 'userTags.push("' . $v['TAG'] . '"); ';
    }
  }
  echo '</script>';
?>