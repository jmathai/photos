<?php
  $g =& CGroupManage::getInstance();
  
  $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
  
  $array_foto_ids = (array)explode(',', $ids);
  
  $g->unshareFotos($_GET['group_id'], $_USER_ID, $array_foto_ids);
  
  $url = '/?action=fotogroup.group_fotos&group_id=' . $_GET['group_id'];
?>