<?php
  $m =& CMail::getInstance();
  $g =& CGroup::getInstance();
  $gm=& CGroupManage::getInstance();
  
  $ids = isset($_POST['ids']) ? $_POST['ids'] : '';
  
  $group_data = $g->groupData($_GET['group_id'], $_USER_ID);
  $members_array= $g->members($_GET['group_id'], $ids);
  $template = '{USERNAME},' . "\n\n" . 'You have been removed from the following FotoGroup: ' . $group_data['G_NAME'] . '.  If you want to re-join this group then you will need to be invited back.  You may contact a group moderator if you feel this was a mistake.  {MESSAGE}';
  
  if(!empty($_POST['message']))
  {
    $template = str_replace('{MESSAGE}', "\n\nMessage from FotoGroup Moderator:\n--------------------\n{$_POST['message']}\n--------------------", $template);
  }
  else
  {
    $template = str_replace('{MESSAGE}', '', $template);
  }
  
  foreach($members_array as $v)
  {
    $gm->leave($v['U_ID'], $_GET['group_id']);
    
    $to = $v['U_NAMEFIRST'] . ' ' . $v['U_NAMELAST'] . ' <' . $v['U_EMAIL'] . '>';
    $message = str_replace('{USERNAME}', $v['U_USERNAME'], $template);
    
    $from           = FF_EMAIL_FROM_FORMATTED;
    $from_email     = FF_EMAIL_FROM;
    $mail_headers   = "MIME-Version: 1.0\n"
                    . "Content-type: text/plain; charset=iso-8859-1\n"
                    . "Return-Path: {$from_email}\n"
                    . "From: {$from}\n";
    
    $m->send(
              $to,
              'FotoFlix - Removal From Group',
              $message,
              $mail_headers,
              "-f{$from_email}"
             );
  }
  
  $url = '/?action=fotogroup.members&group_id=' . $_GET['group_id'] . '&message=members_removed';
?>