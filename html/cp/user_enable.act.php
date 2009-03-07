<?php
  $um =& CUserManage::getInstance();
  $um->enable( $_GET['u_id'] );
  
  $cm =& CMail::getInstance();
  
  $to      = $user_info['U_EMAIL'];
  $subject = 'Account Enabled';
  $message = 'Your account has been re-enabled.  Please contact customer support if you have any questions.';
  $headers = 'From: jaisen@fotoflix.com' . "\r\n" .
             'Reply-To: support@fotoflix.com' . "\r\n" .
             'X-Mailer: PHP/' . phpversion();

  $cm->send( $to, $subject, $message, $headers );

  $url = '/cp/?action=users.search_results&u_dateCreatedFrom=' . $_GET['u_dateCreatedFrom'] . '&u_dateCreatedTo=' . $_GET['u_dateCreatedTo'];
?>