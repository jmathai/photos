<?php
  $pm = &CPrivateMessage::getInstance();
  $u = &CUser::getInstance();
  
  $type = $_GET['type'];
  $id = $_GET['id'];
  
  if($type == 'received')
  {
    $message = $pm->getReceivedMessage($_USER_ID, $id);
    $pm->markReceivedAsRead($_USER_ID, $id);
  }
  else if($type == 'sent')
  {
    $message = $pm->getSentMessage($_USER_ID, $id);
    $pm->markSentAsRead($_USER_ID, $id);
  }
?>

<?php
  echo '<div style="text-align:left; padding-top:25px; padding-left:25px;">';
    if($type == 'received')
    {
      echo '<div style="padding-bottom:20px;"><a href="/?action=messaging.home">Back to inbox</a></div>';
      $avatar = $u->pref($message[0]['PM_SENDER_ID'], 'AVATAR');
      if($avatar != '')
      {
        $avatarSrc = PATH_FOTO . $avatar;
      }
      else
      {
        $avatarSrc = 'images/avatar.jpg';
      }
      echo '<div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" border="0" width="40" height="40" /></div>';
      echo '<div class="f_8">From: ' . $message[0]['U_SENDER_USERNAME'] . '</div>';
    }
    else if($type == 'sent')
    {
      echo '<div style="padding-bottom:20px;"><a href="/?action=messaging.home&tab=sent">Back to sent</a></div>';
      $avatar = $u->pref($message[0]['PM_RECEIVER_ID'], 'AVATAR');
      if($avatar != '')
      {
        $avatarSrc = PATH_FOTO . $avatar;
      }
      else
      {
        $avatarSrc = 'images/avatar.jpg';
      }
      echo '<div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" border="0" width="40" height="40" /></div>';
      echo '<div class="f_8">To: ' . $message[0]['U_RECEIVER_USERNAME'] . '</div>';
    }
    echo '<div class="f_7 italic">' . date('M d, Y g:i a', $message[0]['PM_DATECREATED']) . '</div>';
    echo '<br clear="all" />';
    echo '<div style="padding-top:20px;" class="f_10 bold">Subject: ' . $message[0]['PM_SUBJECT'] . '</div>';
    echo '<div style="padding-top:25px;" class="f_8">' . $message[0]['PMC_CONTENT'] . '</div>';
  echo '</div>';

  echo '<div style="padding-top:30px; padding-left:25px;" class="f_8 bold">';
    
    if($type == 'received')
    {
      echo '<div style="padding-top:5px;"><img src="images/icons/pencil_16x16.png" class="png" border="0" width="16" height="16" onclick="showReply(' . $message[0]['PM_ID'] . ');" style="cursor:pointer;" /> <span class="f_7 bold" onclick="showReply(' . $message[0]['PM_ID'] . ');" style="cursor:pointer;">reply</span> &nbsp; <img src="images/icons/trash_empty_16x16.png" class="png" border="0" width="16" height="16" onclick="showDelete(' . $message[0]['PM_ID'] . ');" style="cursor:pointer;" /> <span class="f_7 bold" onclick="showDelete(' . $message[0]['PM_ID'] . ');" style="cursor:pointer;">delete</span></div>';
      echo '<br clear="all" />';
      
      echo '<div id="message_reply_' . $message[0]['PM_ID'] . '" style=" display:none; padding-top:20px; padding-left:5px;">';
      echo '<form id="message_reply_form_' . $message[0]['PM_ID'] . '" onsubmit="pm_send(\'message_reply_' . $message[0]['PM_ID'] . '\', ' . $message[0]['PM_SENDER_ID'] . ', $(\'subject_' . $message[0]['PM_ID'] . '\').value, $(\'message_text_' . $message[0]['PM_ID'] . '\').value, 10, 15); return false;">';
      echo '<div style="float:left; padding-right:3px;">Subject:</div>';
      echo '<div style="float:left; padding-right:10px;"><input id="subject_' . $message[0]['PM_ID'] . '" type="text" value="Re: ' . $message[0]['PM_SUBJECT'] . '" class="formfield" /></div>';
      echo '<div style="float:left; padding-right:3px;">Message:</div>';
      echo '<div style="float:left; padding-right:10px;"><textarea id="message_text_' . $message[0]['PM_ID'] . '" rows="3" cols="40" class="formfield"></textarea></div>';
      echo '<div><input type="submit" value="Reply" /></div>';
      echo '</form>';
      echo '</div>';
      
      echo '<div id="message_delete_' . $message[0]['PM_ID'] . '" style="display:none; padding-top:10px;">';
      echo '<form id="message_delete_form_' . $message[0]['PM_ID'] . '" action="">';
      echo '<div>Delete this message?</div>';
      echo '<input type="hidden" name="action" value="messaging.message.act" />';
      echo '<input type="hidden" name="PM_ID" value="' . $message[0]['PM_ID'] . '" />';
      echo '<input type="hidden" name="type" value="received" />';
      echo '<div style="float:left; padding-right:5px; padding-left:10px; padding-top:5px;"><input type="submit" value="Yes" /></div>';
      echo '<div style="padding-top:5px;"><input type="submit" value="No" onclick="$(\'message_delete_' . $message[0]['PM_ID'] . '\').style.display = \'none\'; return false;" /></div>';
      echo '</form>';
      echo '</div>';
      echo '<br clear="all" />';
    }
    else if($type == 'sent')
    {
      echo '<div style="padding-top:5px;"><img src="images/icons/trash_empty_16x16.png" class="png" border="0" width="16" height="16" onclick="showDelete(' . $message[0]['PM_ID'] . ');" style="cursor:pointer;" /> <span class="f_7 bold" onclick="showDelete(' . $message[0]['PM_ID'] . ');" style="cursor:pointer;">delete</span></div>';
      echo '<br clear="all" />';
      
      echo '<div id="message_delete_' . $message[0]['PM_ID'] . '" style="display:none; padding-top:10px;">';
      echo '<form id="message_delete_form_' . $message[0]['PM_ID'] . '" action="">';
      echo '<div>Delete this message?</div>';
      echo '<input type="hidden" name="action" value="messaging.message.act" />';
      echo '<input type="hidden" name="PM_ID" value="' . $message[0]['PM_ID'] . '" />';
      echo '<input type="hidden" name="type" value="sent" />';
      echo '<div style="float:left; padding-right:5px; padding-left:10px; padding-top:5px;"><input type="submit" value="Yes" /></div>';
      echo '<div style="padding-top:5px;"><input type="submit" value="No" onclick="$(\'message_delete_' . $message[0]['PM_ID'] . '\').style.display = \'none\'; return false;" /></div>';
      echo '</form>';
      echo '</div>';
      echo '<br clear="all" />';
    }
  echo '</div>';
?>

<script language="javascript">
  function showReply(id)
  {
    if($('message_reply_' + id).style.display == 'block')
    {
      $('message_reply_' + id).style.display = 'none';
    }
    else
    {
      $('message_reply_' + id).style.display = 'block';
      $('message_delete_' + id).style.display = 'none';
    }
  }
  
  function showDelete(id)
  {
    if($('message_delete_' + id).style.display == 'block')
    {
      $('message_delete_' + id).style.display = 'none';
    }
    else
    {
      $('message_delete_' + id).style.display = 'block';
      $('message_reply_' + id).style.display = 'none';
    }
  }
  
  function confirmDelete(uid, id, type)
  {
    agree = confirm("Delete this message?");
    
    if (agree)
    {
      document.location.href = './?action=pm.delete.act&u_id=' + uid + '&id=' + id + '&type=' + type;
    }
  }
</script>
<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
