<?php
  $m =& CMail::getInstance();
  $u =& CUser::getInstance();
  $g =& CGroup::getInstance();
  
  $ids = isset($_POST['ids']) ? $_POST['ids'] : '';
  
  $group_data = $g->groupData($_GET['group_id'], $_USER_ID);
  $user_data  = $u->find($_USER_ID);
  
  $from_email = $user_data['U_EMAIL'];
  $from_name  = $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'];
  
  $members_array = $g->members($_GET['group_id'], $ids);
  
  $mail_headers   = "MIME-Version: 1.0\n"
                  . "Content-type: text/html; charset=iso-8859-1\n"
                  . "Return-Path: <{$from_email}>\n"
                  . "From: \"{$from_name}\" <{$from_email}>\n";
  
  $subject = $m->stripHtml($_POST['subject']);
  $message = $m->stripHtml($_POST['message']);
  
  foreach($members_array as $v)
  {
    $to_email_formatted = '"' . $v['U_NAMEFIRST'] . ' ' . $v['U_NAMELAST'] . '" <' . $v['U_EMAIL'] . '>';
    
    $m->send(
              $to_email_formatted,
              $subject,
              $message,
              $mail_headers,
              "-f{$from_email}"
             );
  }
  
  $url = '/?action=fotogroup.members&group_id=' . $_GET['group_id'] . '&message=message_sent';
?>