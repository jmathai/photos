<?php
  $u  =& CUser::getInstance();
  $um =& CUserManage::getInstance();
  $m  =& CMail::getInstance();
  
  if(strstr($_POST['u_email'], '@') && strstr($_POST['u_email'], '.'))
  {
    $user_data = $u->find($_POST['u_email']);
    
    if($user_data !== false && stristr($user_data['U_SECRET'], $_POST['u_secret']))
    {
      $new_password = $um->resetPassword($user_data['U_ID']);
      $full_name = strlen($user_data['U_NAMEFIRST'] . $user_data['U_NAMELAST']) > 0 ? $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'] : $user_data['U_USERNAME'];
      $message_body = $full_name . ",\n\nA request was submitted to reset your password."
                    . "Your new login information is below:\n"
                    . 'Username: ' . $user_data['U_USERNAME'] . "\n"
                    . 'Password: ' . $new_password . "\n\n"
                    . "You can change your password in your account section after logging in.\n\n"
                    . "Thanks, \nThe Photagious team";
      
      $mail_headers = "MIME-Version: 1.0\n"
                    . "Content-type: text/plain; charset=iso-8859-1\n"
                    . "Return-Path: " . FF_EMAIL_FROM . "\n"
                    . "From: " . FF_EMAIL_FROM_FORMATTED . "\n";
                      
      $m->send(
                $user_data['U_EMAIL'],
                'Password Reset',
                $message_body,
                $mail_headers,
                '-f' . FF_EMAIL_FROM
               );
      $url = '/?action=home.password_reset_form&message=password_reset';
    }
    else
    if($user_data !== false)
    {
      $url = '/?action=home.password_reset_form&message=secret_question&email=' . $_POST['u_email'];
    }
    else
    {
      $url = '/?action=home.password_reset_form&message=email_not_found&email=' . $_POST['u_email'];
    }
  }
  else
  {
    $url = '/?action=home.password_reset_form&message=email_not_valid&email=' . $_POST['u_email'];
  }
?>