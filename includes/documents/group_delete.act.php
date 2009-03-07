<?php
  $gm =& CGroupManage::getInstance();
  
  $group_id = $_GET['group_id'];
  
  $success = $gm->delete($group_id, $_USER_ID);
  
  $type = $success ? 'group_deleted' : 'error_general';
  
  $url = '/?action=confirm.main&type=group_deleted';
?>