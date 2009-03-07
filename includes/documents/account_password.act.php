<?php
  $us =& CUser::getInstance();
  $usm =& CUserManage::getInstance();
  
  if($_POST['u_password'] == $_POST['u_password_confirm'])
  {
    if($us->verifyPassword($_USER_ID, $_POST['u_password_current']))
    {
      $usm ->updatePassword($_USER_ID, $_POST['u_password']);
      $url = '/?action=account.password_form&message=password_updated';
    }
    else
    {
      $url = '/?action=account.password_form&message=current_password_wrong';
    }
  }
  else
  {
    $url = '/?action=account.password_form&message=passwords_do_not_match';
  }
?>