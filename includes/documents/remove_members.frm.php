<?php
  $g    =& CGroup::getInstance();
  
  $ids  = isset($_GET['ids']) ? $_GET['ids'] : '';
  
  $members_array = $g->members($_GET['group_id'], $ids);
?>

Are you sure you want to remove these members and their shared items?<br /><br />
<form name="_flixdelete" action="/?action=fotogroup.remove_members.act&group_id=<?php echo $_GET['group_id']; ?>" method="post">
<table border="0">
  <tr>
    <td align="left">Optional message to send:</td>
  </tr>
  <tr>
    <td align="left"><textarea name="message" rows="3" cols="30" class="formfield"></textarea></td
  </tr>
</table>
  <input type="image" src="images/buttons/remove_members.gif" border="0" />&nbsp;&nbsp;&nbsp;<a href="javascript:history.back();"><img src="images/buttons/cancel.gif" width="87" height="25" border="0" /></a>
  <input type="hidden" name="ids" value="<?php echo $ids; ?>" />
</form>

<table border="0" cellpadding="0" cellspacing="0" width="545">
  <tr>
    <td background="images/pixel_dk_grey.gif">&nbsp;</td>
    <td background="images/pixel_dk_grey.gif"><img src="images/group_header_small.gif" widht="22" heihgt="29" border="0" /></td>
    <td align="left" background="images/pixel_dk_grey.gif" class="f_8 f_black">Member Name</td>
    <td align="center" background="images/pixel_dk_grey.gif" class="f_8 f_black">Member Since</td>
    <td align="center" background="images/pixel_dk_grey.gif" class="f_8 f_black">Fotos Shared</td>
  </tr>
  <tr><td colspan="5"><img src="images/pixel_dk_blue.gif" height="1" width="545" /></td></tr>
  <?php
    $i = 0;
    foreach($members_array as $v)
    {
      $gp_us_info = $g->fotosByMember($_GET['group_id'], $v['U_ID'], 'summary');
      $bg = $i % 2 == 0 ? 'images/pixel_lt_grey.gif' : 'images/spacer.gif';
      echo '<tr>
              <td align="right" valign="middle" background="' . $bg . '"><img src="images/icons/label_member.gif" width="13" height="24" border="0" vspace="2" /></td>
              <td align="center" valign="middle" background="' . $bg . '">&nbsp;</td>
              <td align="left" valign="middle" background="' . $bg . '" class="f_8 f_black">' . $v['U_USERNAME'] . '</td>
              <td align="center" valign="middle" background="' . $bg . '" class="f_8 f_black">' . date('m/d/Y', $v['U_JOINED']) . '</td>
              <td align="center" valign="middle" background="' . $bg . '" class="f_8 f_black">' . $gp_us_info['NUM_PHOTOS'] . '</td>
            </tr>
            <tr><td colspan="5"><img src="images/pixel_dk_blue.gif" height="1" width="545" /></td></tr>';
      
      $i++;           
    }
  ?>
</table> 

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>