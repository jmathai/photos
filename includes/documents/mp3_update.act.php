<?php
  $fb =& CFotoboxManage::getInstance();
  
  if($_USER_ID == $_POST['um_u_id'])
  {
    $array_omit = array(
                    'sessionid' => true
                  );
    
    $_p = array();
    foreach($_POST as $k => $v)
    {
      if(!isset($array_omit[$k]))
      {
        $_p[$k] = $v;
      }
    }
    
    $fb->updateMp3($_p);
    
    $url = '/popup/mp3_manage/updated';
  }
?>