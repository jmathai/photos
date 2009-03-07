<?php
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();

  $flix_ids = isset($_GET['flix_ids']) ? preg_replace('/^,*|,*$/', '', $_GET['flix_ids']) : '';
  $array_flix_ids = explode(',', $flix_ids);

  $flix_array = $fl->flixByIds($array_flix_ids, $_GET['group_id'], 'group');
?>
Are you sure you want to unshare these FotoFlix?<br /><br />

<form name="_flixdelete" action="/?action=fotogroup.flix_unshare_form.act&group_id=<?php echo $_GET['group_id']; ?>" method="post">
  <input type="image" src="images/buttons/unshare_flix.gif" border="0" />&nbsp;&nbsp;&nbsp;<a href="javascript:history.back();"><img src="images/buttons/cancel.gif" width="87" height="25" border="0" /></a>
  <input type="hidden" name="flix_ids" value="<?php echo $flix_ids; ?>" />
</form>

<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0">
  <?php
    $cols = 3;
    $i = 3;

    foreach($flix_array as $k => $v)
    {
      //$foto_id   = substr($v['A_PHOTOS'], 0, strpos($v['A_PHOTOS'], ','));
      $foto_id   = $v['A_DATA'][0]['D_UP_ID'];
      $foto_data = $fb->fotoData($foto_id);

      $image_info = @getimagesize(PATH_FOTOROOT . $foto_data['P_THUMB_PATH']);

      $sizeArr    = explode('x', $v['A_SIZE']);
      $containerWidth      = $sizeArr[0];
      $containerHeight     = $sizeArr[1];

      if($i % $cols == 0)
      {
        echo '<tr>';
      }

      $swf_src = '/swf/flix_theme/layout_small/small_' . substr($v['A_TEMPLATE'], 1) . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $v['A_FASTFLIX'] . '&containerWidth=' . $containerWidth . '&containerHeight=' . $containerHeight;
      echo '<td align="center" width="' . (557 / $cols) . '">
              <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="" ID="a_' . $v['A_ID'] . '" WIDTH="133" HEIGHT="80">
                <PARAM NAME="movie" VALUE="' . $swf_src . '" />
                <param name="menu" value="false" />
                <EMBED name="a_' . $v['A_ID'] . '" menu="false" src="' . $swf_src . '" swliveconnect="true" quality="high" bgcolor="#FFFFFF" WIDTH="133" HEIGHT="80" TYPE="application/x-shockwave-flash" />
                </EMBED>
              </OBJECT>
              <br />
              ' . str_mid($v['A_NAME'], 25) . '
              <br />
            </td>
      ';

      if(($i % $cols) == ($cols - 1))
      {
        echo '</tr><tr><td colspan="' . $cols . '"><img src="images/spacer.gif" width="1" height="25" border="0" /></td></tr>';
      }

      $i++;
    }

    if( ((--$i) % $cols) !== ($cols - 1) )
    {
      while( ($i % $cols) !== ($cols - 1) )
      {
        echo '<td width="' . (557 / $cols) . '">&nbsp;</td>';
        $i++;
      }

      echo  '</tr>';
    }
  ?>
</table>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>