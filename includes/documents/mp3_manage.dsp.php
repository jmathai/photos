<?php
  $fb =& CFotobox::getInstance();
  
  $mp3_data = $fb->mp3s($_USER_ID);
  
  switch($args[1])
  {
    case 'uploaded':
      echo '<div align="center" class="bold">Your MP3 was uploaded.</div>';
      break;
    case 'updated':
      echo '<div align="center" class="bold">Your MP3 was updated.</div>';
      break;
    case 'filesizeexceeded':
      echo '<div align="center" class="bold">You have insufficient space to upload that MP3.</div>';
      break;
  }

?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr height="28">
    <td align="center" class="bg_medium_accent f_8 f_white bold">MP3 Name</td>
    <td align="center" class="bg_medium_accent f_8 f_white bold">MP3 Size</td>
    <td align="center" class="bg_medium_accent f_8 f_white bold">Uploaded On</td>
    <td align="center" class="bg_medium_accent"></td>
  </tr>
  <?php
    foreach($mp3_data as $k => $v)
    {
      $style = $k % 2 == 0 ? 'bg_lite' : 'bg_white';
      echo '<tr height="28">
              <td align="center" valign="middle" class="' . $style . ' f_8 f_black">' . $v['M_NAME'] . '</td>
              <td align="center" valign="middle" class="' . $style . ' f_8 f_black">' . number_format($v['M_SIZE']) . ' KB</td>
              <td align="center" valign="middle" class="' . $style . ' f_8 f_black">' . date('m/d/Y', $v['M_CREATED_AT']) . '</td>
              <td align="center" valign="middle" class="' . $style . ' f_8 f_black">
                <a href="/popup/mp3_update_form/' . $v['M_ID'] . '"><img src="images/icons/edit_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" /></a>
                <a href="/popup/mp3_delete_confirm/' . $v['M_ID'] . '"><img src="images/icons/delete_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /></a>
              </td>
            </tr>
            <tr><td colspan="5"><div class="line_dark"></div></td></tr>';
    }
  ?>
</table>