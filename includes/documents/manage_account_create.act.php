<?php
  $u  =& CUser::getInstance();
  $um =& CUserManage::getInstance();
  
  $userData = $u->find($_USER_ID);
  
  $addData = array(
                'u_parentId' => $_POST['u_parentId'],
                'u_username' => $_POST['u_username'],
                'u_password' => md5($_POST['u_password']),
                'u_email'    => $_POST['u_email'],
                'u_dateExpires' => date('Y-m-d', $userData['U_DATEEXPIRES']),
                'u_isTrial'  => USER_IS_NOT_TRIAL,
                'u_status'   => 'Active'
              );
  
  $um->add($addData);
  
  $url = '/?action=manage.accounts';
?>