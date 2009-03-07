<?php
  $p =& CMyPage::getInstance();
  
  $_qty = array(3, 6, 9, 12, 15);
  
  if(in_array($_GET['qty'], $_qty))
  {
    $data = array('p_u_id' => $_USER_ID, 'p_flixQuantity' => $_GET['qty']);
    $p->update($data);
  }
  
  $url = '/?action=mypage.flixpage&message=qty_changed';
?>