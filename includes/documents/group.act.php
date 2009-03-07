<?php
  $gm=& CGroupManage::getInstance();
  $fm=& CForumManage::getInstance();
  $g =& CGroup::getInstance();
  $m =& CMail::getInstance();
  $u =& CUser::getInstance();
  $b =& CBoard::getInstance();
  
  /*
  * UPDATE/CREATE GROUP ONLY IF NEEDED
  */
  $message = 'group';
  
  if(isset($_POST['g_name']))
  {
    $_post_data = array(
                    'g_u_id'  => $_USER_ID, 
                    'g_name'  => $_POST['g_name'],
                    'g_description'   => $_POST['g_description'],
                    'g_public'        => $_POST['g_public'],
                    'g_contributors'  => $_POST['g_contributors']
                  );
    
    if($_POST['g_tags'] == true)
    {
      $_post_data['g_tags'] = 'Y';
    }
    else
    {
      $_post_data['g_tags'] = 'N';
    }
    
    if(!isset($_POST['g_listed']))
    {
      $_post_data['g_listed'] = 0;
    }
    
    if(!isset($_POST['g_public']))
    {
      $_post_data['g_public'] = 0;
    }
    
    if(isset($_POST['g_id']))
    {
      $_post_data['g_id'] = $_POST['g_id'];
      $group_id = $gm->update($_post_data);
      $message .= '_updated';
    }
    else
    {
      $_username = $_FF_SESSION->value('username');
      $group_id = $gm->add($_post_data);
      $b->createGroupBoard($group_id, $_post_data['g_u_id'], $_post_data['g_name'], $_post_data['g_description']);
      $gm->join($_USER_ID, $group_id);
      $tp_id  = $fm->addTopic(
                  array('f_g_id' => $group_id, 'f_title' => $_post_data['g_name'] . ' Forum', 
                        'f_description' => 'Talk about ' . $_post_data['g_name'] . ' here.', 'f_lastPoster' => $_username)
                );
      $th_id  = $fm->addThread(
                  array('ft_f_id' => $tp_id, 'ft_g_id' => $group_id, 'ft_title' => 'Thread for ' . $_post_data['g_name'], 'ft_lastPoster' => $_username)
                );
      $fm->addPost(
                  array('fp_ft_id' => $th_id, 'fp_f_id' => $tp_id, 'fp_username' => $_username, 'fp_u_id' => $_USER_ID, 'fp_g_id' => $group_id,
                        'fp_title' => 'Talk about ' . $_post_data['g_name'], 'fp_post' => 'Post away!!')
                );
      
      $message .= '_added';
    }
  }
  else
  {
    $group_id = $_POST['g_id'];
  }
  
  /*
  * SHARE PHOTOS ONLY IF NEEDED
  */
  if(isset($_POST['foto_ids']) || isset($_POST['flix_ids']))
  {
    include_once PATH_DOCROOT . '/group_share_form.act.php';
  }
  
  /*
  * SEND EMAILS ONLY IF NEEDED
  */
  if(isset($_POST['group_recipient_email']))
  {
    $group_data = $g->groupData($group_id, $_USER_ID);
    $foto_data  = $g->fotos($group_id, false, 'P_MOD_YMD', 0, 6);
    $user_data  = $u->find($_USER_ID);
    
    $array_emails= $_POST['group_recipient_email'];
    $array_names = $_POST['group_recipient_name'];
    
    $sender_message  = $m->stripHtml($_POST['group_recipient_email_body']);
    $message_subject = 'FotoFlix Invitation from ' . $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'];
    $from_name    = $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'];
    $from_email   = $user_data['U_EMAIL'];
    
    $mail_headers   = "MIME-Version: 1.0\n"
                    . "Content-type: text/html; charset=iso-8859-1\n"
                    . 'Return-Path: ' . FF_EMAIL_FROM . "\n"
                    . 'From: ' . FF_EMAIL_FROM_FORMATTED;
    
    $email_template = file_get_contents(PATH_DOCROOT . '/invitation.tpl.php');
    
    $count = count($foto_data);
    if($count > 0)
    {
      $group_fotos  = '<table border="0" cellpadding="0" cellspacing="0" width="215">
                        <tr>';
      
      for($i=0; $i<$count; $i++)
      {
        if($i == 3)
        {
          $group_fotos .= '</tr><tr>';
        }
        
        $group_fotos .= '<td>
                            <table border="0" cellpadding="0" cellspacing="0" width="87">
                              <tr>
                                <td colspan="3"><img src="{BASE_URL}/images/fb_frame_top.gif" width="87" height="5" vspace="0" hspace="0" border="0" /></td>
                              </tr>
                              <tr>
                                <td><img src="{BASE_URL}/images/fb_frame_left.gif" width="5" height="75" vspace="0" hspace="0" border="0" /></td>
                                <td><img src="{BASE_URL}' . PATH_FOTO . $foto_data[$i]['P_THUMB_PATH'] . '" hspace="0" vspace="0" border="0" /></td>
                                <td><img src="{BASE_URL}/images/fb_frame_right.gif" width="7" height="75" vspace="0" hspace="0" border="0" /></td>
                              </tr>
                              <tr>
                                <td colspan="3"><img src="{BASE_URL}/images/fb_frame_bottom.gif" width="87" height="7" vspace="0" hspace="0" border="0" /></td>
                              </tr>
                            </table>
                          </td>';
      }
      
      $group_fotos .= '</tr></table>';
    }
    else
    {
      $group_fotos = '';
    }
    
    $group_fotos = str_replace('{BASE_URL}', 'http://' . FF_SERVER_NAME, $group_fotos);
    
    $name_list = '';
    $confirm_invites = false;
    
    foreach($array_emails as $k => $v)
    {
      if($m->validate($array_emails[$k]) === true && !empty($array_emails[$k]))
      {
        $to_email = $array_emails[$k];
        $to_name  = strlen($array_names[$k]) > 0 ? $array_names[$k] : $array_emails[$k];
        $name_exists = $to_email != $to_name ? true : false;
        
        $name_list .= $to_name . '<br />';
        
        $to_email_formatted = $name_exists == true ? $to_name . ' <' . $to_email . '>' : $to_email;
        
        $invite_data = array(
                          'gi_g_id' => $group_id, 
                          'gi_u_id' => $_USER_ID, 
                          'gi_reference' => md5(uniqid(rand(), true)),
                          'gi_name' => $to_name, 
                          'gi_email'=> $to_email
                        );
        
        $gm->invite($invite_data);
        
        $acceptance_link = 'http://' . FF_SERVER_NAME . '/invite?' . $invite_data['gi_reference'];
        
        $message_body = str_replace(
                          array('{GREETING}', '{SENDER_NAME}', '{GROUP_NAME}', '{SENDER_MESSAGE}', '{ACCEPTANCE_LINK}', '{GROUP_FOTOS}', '{BASE_URL}'), 
                          array($to_name, $from_name, $group_data['G_NAME'], $sender_message, $acceptance_link, $group_fotos, 'http://' . FF_SERVER_NAME),
                          $email_template
                        );
        
        $m->send(
                  $to_email_formatted,
                  $message_subject,
                  $message_body,
                  $mail_headers,
                  '-f' . FF_EMAIL_FROM
                 );
        $confirm_invites = true;
      }
    }
    
    if($confirm_invites === true)
    {
      /* SEND CONFIRMATION */
      $mail_headers   = "MIME-Version: 1.0\n"
                      . "Content-type: text/html; charset=iso-8859-1\n"
                      . "Return-Path: " . FF_EMAIL_FROM . "\n"
                      . "From: " . FF_EMAIL_FROM_FORMATTED . "\n";
      
      $confirmation_template = file_get_contents(PATH_DOCROOT . '/invitation_confirmation.tpl.php');
      
      $message_body = str_replace(
                        array('{GREETING}', '{GROUP_NAME}', '{RECIPIENT_LIST}'),
                        array($from_name, $group_data['G_NAME'], $name_list),
                        $confirmation_template
                      );
      
      $m->send(
                $from_email, /* FROM ADDY IS THE TO ADDY FOR CONFIRMATION */
                'Invitation Confirmation',
                $message_body,
                $mail_headers,
                '-f' . FF_EMAIL_FROM
               );
      
      $message .= '_invited';
    }
  }
  
  $url = '/?action=fotogroup.group_home&group_id=' . $group_id . '&message=' . $message;
?>