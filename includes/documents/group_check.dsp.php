<?php
  include_once PATH_CLASS . '/CGroup.php';
  
  if(isset($_GET['group_id']))
  {
    $groupId = intval($_GET['group_id']);
    $gp =& CGroup::getInstance();
    $groupData = $gp->groupData($groupId);
    $groupName = $groupData['G_NAME'];
    
    $action = $_GET['action'];
    
    if($gp->isMember($_USER_ID, $groupId) == false && $action != 'group.member_accept')
    {
      $url = '/?action=confirm.main&type=group_failed';
      header('Location: ' . $url);
    }
  }
?>