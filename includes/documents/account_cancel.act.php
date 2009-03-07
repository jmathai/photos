<?php
  $us   =&  CUser::getInstance();
  $usm  =&  CUserManage::getInstance();
  $m    =&  CMail::getInstance();
  
  $userData = $us->find($_USER_ID);
  $confirmationId = substr($userData['U_ID'] . randomString(12), 0, 12);
  
  $data = array('uc_u_id' => $_USER_ID, 'uc_confirmationId' => $confirmationId, 'uc_email' => $userData['U_EMAIL'], 'uc_dateEffective' => date('Y-m-d', $userData['U_DATEEXPIRES'])); 
  
  $usm->cancel($data);
  
  $body = file_get_contents(PATH_DOCROOT . '/account_cancel.tpl.php');
  $body = str_replace(array('{NAME}', '{DATE_EXPIRES}', '{CONFIRMATION}'), array($userData['U_NAMEFIRST'], date(FF_FORMAT_DATE_LONG, $userData['U_DATEEXPIRES']), $confirmationId), $body);
  
  $from     = FF_EMAIL_FROM_FORMATTED;
  $from_email = FF_EMAIL_FROM;
  $mail_headers   = "MIME-Version: 1.0\n"
                  . "Content-type: text/plain; charset=iso-8859-1\n"
                  . "Return-Path: {$from}\n"
                  . "From: {$from}\n";
                  
  $m->send(
            $userData['U_EMAIL'],
            'Photagious Account Cancelled',
            $body,
            $mail_headers,
            "-f{$from_email}"
           );
  
  $url = '/?action=account.cancel_confirm&confirmationId=' . $confirmationId;
?>