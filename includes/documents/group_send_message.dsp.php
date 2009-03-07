<?php
  $g = &CGroup::getInstance();
  $group_id = $_GET['group_id'];
  
  if($g->isModerator($_USER_ID, $group_id) == true)
  {
  
    $message = isset($_GET['message']) ? $_GET['message'] : false;
    
    if($message == 'success')
    {
      echo '<div style="padding-top:5px; padding-bottom:10px;" class="f_8 bold">Your message has been sent</div>';
    }
  ?>
  
  <div>Send message to all group members</div>
  
  <form id="_sendGroupMessageForm" action="?action=group.send_message.act&group_id=<?php echo $group_id; ?>" method="POST">
    <div>Subject</div>
    <div><input type="text" id="_subject" name="_subject" /></div>
    
    <div>Message</div>
    <div><textarea id="_message" name="_message" cols="40" rows="10"></textarea></div>
    
    <div><img src="images/buttons/send.jpg" border="0" onclick="javascript:validateSendGroupMessage($('_subject').value, $('_message').value);" style="cursor:pointer;" /></div>
  </form>

<?php
  }
  else 
  {
    echo 'You are no a group administrator';
  }
?>

<script language="javascript">
function validateSendGroupMessage(subject, message)
{
  if(subject == '')
  {
    alert('Subject cannot be blank');
    $('_subject').focus();
  }
  else if(message == '')
  {
    alert('Message cannot be blank');
    $('_message').focus();
  }
  else
  {
    $('_sendGroupMessageForm').submit();
  }
}
</script>