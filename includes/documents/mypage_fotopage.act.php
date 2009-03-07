<?php
  $usm=& CUserManage::getInstance();
  
  $privacy = intval($_POST['privacySetting']);
  $usm->setPrefs($_USER_ID, array('FOTO_PRIVACY' => $privacy));
  
  $url = '/?action=mypage.fotopage&message=updated';
?>