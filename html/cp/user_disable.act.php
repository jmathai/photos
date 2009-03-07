<?php
  $u =& CUser::getInstance();
  $userData = $u->find( $_GET['u_id'] );
  
  $um =& CUserManage::getInstance();
  $um->delete( $_GET['u_id'] );
  
  $cm =& CMail::getInstance();
  
  $to      = $userData['U_EMAIL'];
  $subject = 'Your Account Has Been Terminated';
  $message = file_get_contents(PATH_INCLUDE . '/account_violation.tpl.php');
  $message = str_replace(array('{FIRST_NAME}', '{LAST_NAME}', '{SERVER_NAME}'), array($userData['U_NAMEFIRST'], $userData['U_NAMELAST'], FF_SERVER_NAME), $message);
  
  $from     = FF_EMAIL_FROM_FORMATTED;
  $headers  = "MIME-Version: 1.0\n"
            . "Content-type: text/plain; charset=iso-8859-1\n"
            . "Return-Path: {$from}\n"
            . "From: {$from}\n";

  
  $headers = 'From: jaisen@fotoflix.com' . "\r\n" .
             'Reply-To: support@fotoflix.com' . "\r\n";

  $cm->send( $to, $subject, $message, $headers );
  
  $url = '/cp/?action=users.search_results&u_username=' . $_GET['u_username'] . '&u_email=' . $_GET['u_email'] . '&u_dateCreatedFrom=' . $_GET['u_dateCreatedFrom'] . '&u_dateCreatedTo=' . $_GET['u_dateCreatedTo'];
?>