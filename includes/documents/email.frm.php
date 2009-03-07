<?php
  $g =& CGroup::getInstance();
  
  $ids = isset($_GET['ids']) ? $_GET['ids'] : '';
  
  $group_data = $g->groupData($_GET['group_id'], $_USER_ID);
  $members_array= $g->members($_GET['group_id'], $ids);
?>

<table border="0" cellpadding="0" cellspacing="0" width="545">
  <form method="post" action="/?action=fotogroup.email_form.act&group_id=<?php echo $_GET['group_id']; ?>">
    <tr>
      <td align="left">
        Subject:<br />
        <input type="text" name="subject" value="" size="30" class="formfield" /><br /><br />
        Message:<br />
        <textarea name="message" style="width:400px; height:200px;" class="formfield"></textarea><br /><br />
        <input type="image" src="images/buttons/send_email.gif" border="0" />
      </td>
    </tr>
    <input type="hidden" name="ids" value="<?php echo $ids; ?>" />
  </form>
</table>

<br /><br />
<table border="0" cellpadding="0" cellspacing="0" width="545">
  <tr>
    <td align="left" class="bold">Your email will be sent to the following members:</td>
  </tr>
</table>
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