<?php
  $fbm =& CFotoboxManage::getInstance();
  
  $fbm->viewed($_GET['key'], $_GET['user_id']);
?>