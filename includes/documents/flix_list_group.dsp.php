<?php
  $prefix = substr($action, 0, strpos($action, '.'));
  $group_id = intval($_GET['group_id']);
  $fl =& CFlix::getInstance();

  $f =& CGroup::getInstance();

  if(!isset($_GET['tags']))
  {
    $flix_array = $fl->flix($group_id, false, 'group');
  }
  else
  {
    $tags = (array)explode(',', $_GET['tags']);
    $flix_array = $fl->flixByTags($tags, $group_id, false, 'group', $sort, false, false);
  }

  $fb =& CFotobox::getInstance();

  if(isset($_GET['message']))
  {
    $message_show = false;
    switch($_GET['message'])
    {
      case 'created':
        $message_display = 'Congratulations!  Your flix was created.';
        $message_show = true;
        break;
      case 'updated':
        $message_display = 'Your flix was updated.';
        $message_show = true;
        break;
    }

    if($message_show === true)
    {
      echo '<div class="confirm">' . $message_display . '</div>';
    }
  }

  $show_actions = true;
?>

<br />
<table border="0" cellpadding="0" cellspacing="0">
  <?php
    $cnt_flix_array = count($flix_array);
    if($cnt_flix_array > 0)
    {
      $cols = 3;
      $i = 3;
      $width = (557 / $cols);

      foreach($flix_array as $k => $v)
      {
        //$foto_id   = substr($v['A_PHOTOS'], 0, strpos($v['A_PHOTOS'], ','));
        $foto_id    = $v['A_DATA'][0]['D_UP_ID'];
        $foto_data  = $fb->fotoData($foto_id);

        $sizeArr    = explode('x', $v['A_SIZE']);
        $containerWidth      = $sizeArr[0];
        $containerHeight     = $sizeArr[1];

        $image_info = @getimagesize(PATH_FOTOROOT . $foto_data['P_THUMB_PATH']);

        if($i % $cols == 0)
        {
          echo '<tr>';
        }
        echo '<td align="left" width="' . $width . '">
                <div style="padding-left:10px;">
                  <div class="flix_border"><a href="/fastflix_popup?fastflix=' . $v['A_FASTFLIX'] . '" onclick="_open(this.href, ' . $containerWidth . ', ' . $containerHeight . '); return false;"><img src="' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '" border="0" /></a></div>
                  <div class="bold">' . str_mid($v['A_NAME'], 25) . '</div>';
        if($v['A_U_ID'] == $_USER_ID)
        {
          echo '<div><img src="images/icons/label_unshare.gif" width="22" height="22" border="0" align="absmiddle" />&nbsp;<a href="/?action=fotogroup.flix_unshare_form&group_id=' . $group_id . '&flix_ids=' . $v['A_ID'] . '">Unshare Flix</a></div>';
        }
        /*else
        {
          echo '<div><img src="images/icons/label_transfer.gif" width="35" height="22" border="0" align="absmiddle" />&nbsp;<a href="/?action=fotobox.save_to_fotobox_form&group_id=' . $group_id . '&flix_ids=' . $v['A_ID'] . '">Save fotos</a></div>';
        }*/
        echo '  </div>
              </td>';

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
    }
    else
    {
      echo '<tr><td align="left">There are currently no flix shared with this group.</td></tr>';
      $show_actions = false;
    }
  ?>
</table>
<br />
<?php
  include_once PATH_DOCROOT . '/ads_horizontal.dsp.php';

  $tpl->main($tpl->get());
  $tpl->clean();
?>
