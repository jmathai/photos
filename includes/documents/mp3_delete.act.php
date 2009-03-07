<?php
  $fbm =& CFotoboxManage::getInstance();
  
  $mp3_id = $_POST['um_id'];
  
  $fbm->deleteMp3($mp3_id, $_USER_ID);
  
  $url = '/popup/mp3_manage';
?>