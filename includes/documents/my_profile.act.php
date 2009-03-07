<?php
  $usm =& CUserManage::getInstance();
  $usm->updateProfile($_USER_ID, strip_tags(str_replace(array("\n","\r"), '', $_POST['fck_instance']), '<strong><b><i><u><p><br><img><a><table><div><span><tr><td><th><ol><ul><li>'));
  
  $url = $_POST['redirect'] . '?updated';
?>