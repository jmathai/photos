<?php
  $u  =& CUser::getInstance();
  $um =& CUserManage::getInstance();
  
  $user_data = $u->find($_USER_ID);
  
  if($user_data['U_ACCOUNTTYPE'] != FF_ACCT_TRIAL)
  {
    if($_GET['mode'] == 1)
    {
      $mode = 'Y';
      $enable_mode = 'my_enabled';
    }
    else
    {
      $mode = 'N';
      $enable_mode = 'my_disabled';
    }
    
    $update_array = array('u_id' => $_USER_ID, 'u_myEnabled' => $mode);
    $um->update($update_array);
  }
  
  $url = '/?action=flix.flix_list&message=' . $enable_mode;
?>