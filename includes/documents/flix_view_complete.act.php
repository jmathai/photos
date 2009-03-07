<?php
  $fl =& CFlixManage::getInstance();
  
  $complete = $action == 'flix.flix_view.act' ? false : true;
  $key = isset($_GET['key']) ? $_GET['key'] : '';
  
  $fl->viewed($key, $complete);
?>