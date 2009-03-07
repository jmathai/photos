<?php
  $messages = $u->getMessages($_USER_ID);
?>

<div class="f_10 bold"><img src="/images/icons/mail_alt_2_16x16.png" class="png" width="16" height="16" vspace="5" border="0" align="absmiddle" /> Messages</div>

<table cellpadding="3" cellspacing="1" border="0">
  <tr bgcolor="#eeeeee">
    <th width="400">Subject</th>
    <th width="150">From</th>
    <th width="150">Date</th>
    <th bgcolor="#ffffff">&nbsp;</th>
  </tr>
  <!--<tr height="5">
    <td colspan="3"></td>
  </tr>-->
  <?php
    if(count($messages) > 0)
    {
      foreach($messages as $k => $v)
      {
        $bgcolor = $readColor = $k % 2 == 0 ? '#dddddd' : '#ffffff';
        $sClass   = '';
        
        if($v['UI_STATUS'] == 'Unread')
        {
          $sClass = 'bold';
          $bgcolor = '#f8e8a0';
        }
        
        echo '
          <tr bgcolor="' . $bgcolor . '" rel="' . $readColor . '" id="messageRow_' . $v['UI_ID'] . '" ' . $effect . '>
            <td style="padding-left:5px;" valign="top">
              <div id="message_' . $v['UI_ID'] . '" class="' . $sClass . '"><a href="javascript:void(0);" onclick="getMessage(' . $v['UI_ID'] . ');">' . $v['UI_SUBJECT'] . '</a></div>
            </td>
            <td align="center" valign="top">' . $v['UI_SENDER'] . '</td>
            <td align="center" valign="top">' . date('l, F j', $v['UI_DATECREATED']) . '</td>
            <td valign="top">
              <a href="javascript:void(0);" onclick="getMessage(' . $v['UI_ID'] . ');" title="reply to this message"><img src="/images/icons/reply_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" /></a>
              <a href="javascript:void(0);" onclick="deleteMessage(' . $v['UI_ID'] . ');" title="delete this message"><img src="/images/icons/delete_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" /></a>
            </td>
          </tr>
        ';
      }
    }
    else
    {
      echo '<tr><td colspan="4" class="bold italic">You have no messages</td></tr>';
    }
  ?>
</table>