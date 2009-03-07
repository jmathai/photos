<?php
  $fl =& CFlixManage::getInstance();
  
  $flix_ids = isset($_POST['flix_ids']) ? $_POST['flix_ids'] : 0;
  
  $array_flix_ids = (array)explode(',', $flix_ids);
  
  $fl->delete($array_flix_ids, $_USER_ID);
  
  $url = '/?action=flix.flix_list';
?>