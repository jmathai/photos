<?php
  $g =& CGroupManage::getInstance();
  
  $flix_ids = isset($_POST['flix_ids']) ? $_POST['flix_ids'] : 0;
  
  $array_flix_ids = (array)explode(',', $flix_ids);
  
  $g->unshareFlix($_GET['group_id'], $array_flix_ids, $_USER_ID);
  
  $url = '/?action=fotogroup.flix_list&group_id=' . $_GET['group_id'];
?>