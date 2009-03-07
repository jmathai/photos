<?php
  $g =& CGroupManage::getInstance();
  
  $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;

  $array_flix_ids = (array)explode(',', $ids);

  $g->unshareFlix($_GET['group_id'], $array_flix_ids, $_USER_ID);

  $url = '/?action=fotogroup.flix_contributions&group_id=' . $_GET['group_id'];

?>