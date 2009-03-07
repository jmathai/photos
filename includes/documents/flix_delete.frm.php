<?php
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();

  $flix_ids = isset($_GET['flix_ids']) ? preg_replace('/^,*|,*$/', '', $_GET['flix_ids']) : '';
  $array_flix_ids = explode(',', $flix_ids);

  $flix_array = $fl->search(array('FLIX_IDS' => $array_flix_ids, 'USER_ID' => $_USER_ID));
?>
Are you sure you want to delete these slideshows?<br /><br />

<form name="_flixdelete" action="/?action=flix.flix_delete.act" method="post">
  <input type="image" src="images/buttons/btn_delete_slideshow.png" class="png" border="0" />&nbsp;&nbsp;&nbsp;<a href="javascript:history.back();"><img src="images/buttons/cancel.gif" width="87" height="25" border="0" /></a>
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
      $fotoURL = $v['US_PHOTO']['thumbnailPath_str'];
      //$swf_src = '/swf/flix_theme/layout_small/small_' . substr($v['A_TEMPLATE'], 1) . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $v['A_FASTFLIX'] . '&containerWidth=' . $containerWidth . '&containerHeight=' . $containerHeight;

      if($i % $cols == 0)
      {
        echo '<tr>';
      }

      echo '<td align="center" width="' . (557 / $cols) . '">
              <div class="flix_border"><img src="' . PATH_FOTO . $fotoURL . '" border="0" /></div>
              <div style="margin-left:-15px;">' . str_mid($v['US_NAME'], 25) . '</div>
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