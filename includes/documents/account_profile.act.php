<?php
  $um =& CUserManage::getInstance();
  $um->update($_POST);
  
  $url = '/?action=account.profile_form&message=updated';
?>