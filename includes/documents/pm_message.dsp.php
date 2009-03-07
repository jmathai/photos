<?php
  $pm = &CPrivateMessage::getInstance();
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
    echo '<div class="f_10 bold">' . $message[0]['PM_SUBJECT'] . '</div>';
    if($type == 'received')
    {
      echo '<div class="f_8">From: ' . $message[0]['U_SENDER_USERNAME'] . '</div>';
    }
    else if($type == 'sent')
    {
      echo '<div class="f_8">To: ' . $message[0]['U_RECEIVER_USERNAME'] . '</div>';
    }
    echo '<div style="padding-top:25px;" class="f_8">' . $message[0]['PMC_CONTENT'] . '</div>';
  echo '</div>';

  echo '<div style="padding-top:30px; padding-left:25px;" class="f_8 bold">';
    
    if($type == 'received')
    {
      echo '<div style="float:left;"><a href="javascript:void(pm_newMessage(\'_newMessage\', ' . $message[0]['PM_SENDER_ID'] . '));">Reply</a></div>';
      echo '<div style="float:left; padding-left:15px;"><a href="javascript:void(confirmDelete(' . $_USER_ID . ', ' . $id . ', \'Received\'));">Delete</a></div>';
      echo '<br /><div style="float:left; padding-top:5px;" id="_newMessage" style="display:block; z-index:75;"></div>';
    }
    else if($type == 'sent')
    {
      echo '<div style="float:left;"><a href="javascript:void(confirmDelete(' . $_USER_ID . ', ' . $id . ', \'Sent\'));">Delete</a></div>';
    }
  echo '</div>';
?>

<script language="javascript">
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