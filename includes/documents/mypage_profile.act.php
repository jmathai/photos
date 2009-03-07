<?php
  $um =& CUserManage::getInstance();
  
  $_p = $_POST;
  
  if(!preg_match('/\d{1,2}\/\d{1,2}\/\d{2,4}/', $_p['p_dateBirth']))
  {
    $_p['p_dateBirth'] = 0;
  }
  else
  {
    $dparts = explode('/', $_p['p_dateBirth']);
    $_p['p_dateBirth'] = mktime(0, 0, 0, $dparts[0], $dparts[1], substr('19' . $dparts[2], -4, 4));
  }
  
  $_p = htmlSafeArray($_p);
  
  $um->updateProfile($_p);
  
  $url = '/?action=mypage.profile&message=updated';
?>