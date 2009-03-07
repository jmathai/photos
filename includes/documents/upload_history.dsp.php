<?php
  $prefix = substr($action, 0, strpos($action, '.'));

  if(isset($_GET['group_id']))
  {
    $f =& CGroup::getInstance();
    $u =& CUser::getInstance();
    $foto_history = $f->history($_GET['group_id']);
    $fotos_url_part = 'group_fotos';
    $group_url_suffix = '&group_id=' . $_GET['group_id'];
    $viewer = 'groupfotoviewer';
  }
  else
  {
    $f =& CFotobox::getInstance();
    $foto_history = $f->history($_USER_ID);
    $fotos_url_part = 'fotobox_myfotos';
    $group_url_suffix = '';
    $viewer = 'fotoviewer';
  }

  $cnt_history = count($foto_history);

  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $pages_to_display = 16;
    $per_page = 6;
  $total_pages = ceil($cnt_history / $per_page);
  $start = ($page * $per_page) - $per_page;
  $end   = ($start + $per_page) < $cnt_history ? ($start + $per_page) : $cnt_history;

  $pg = new CPaging($page, $pages_to_display, $total_pages);

  if($cnt_history == 0)
  {
    echo '<table border="0" cellpadding="0" cellspacing="0" width="545">
            <tr>
              <td>
                You do not have an upload history.
                You can begin to upload fotos by clicking <a href="/?action=fotobox.upload_form">here</a>.
              </td>
            </tr>
          </table>';
  }
  else
  {
    echo '<table border="0" cellpadding="0" cellspacing="0" width="545">
            <tr>
              <td align="right">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>Page ' . $page . ' of ' . $total_pages . '</td>
                    <td>&nbsp;|&nbsp;</td>
                    <td>' . $pg->getPrevPage('<img src="images/arrow_left.gif" width="6" height="9" border="0" alt="click to view next" title="click to view next" />') . '</td>
                    <td>' . $pg->getPages() . '</td>
                    <td>' . $pg->getNextPage('<img src="images/arrow_right.gif" width="6" height="9" border="0" alt="click to view next" title="click to view next" />') . '</td>
                  </tr>
                </table>
                &nbsp;
            </tr>
          </table>';
  }

  $counter = 0;
  //foreach($foto_history as $k => $v)

  $keys = array_keys($foto_history);

  for($i = $start; $i < $end; $i++)
  {
    $background_image = $counter % 2 == 0 ? 'images/pixel_lt_grey.gif' : 'images/spacer.gif';
    $date = array('YEAR' => substr($keys[$i], 0, 2), 'MONTH' => substr($keys[$i], 2, 2), 'DAY' => substr($keys[$i], 4, 2));
    if($prefix == 'fotogroup')
    {
      $user_id = substr($keys[$i], strpos($keys[$i], '|') + 1);
      $user_data = $u->find($user_id);
      $user_str  = 'by: ' . $user_data['U_USERNAME'];
    }
    else
    {
      $user_str = '&nbsp;';
    }

    echo '<table border="0" cellpadding="0" cellspacing="0" width="545" background="' . $background_image . '">
            <tr>
              <td colspan="4"><img src="images/spacer.gif" width="1" height="5" border="0" /></td>
            </tr>
            <tr>
              <td width="30" align="center"><img src="images/icons/label_upload_history.gif" width="20" height="20" /></td>
              <td width="100" align="left">' . $user_str . '</td>
              <td width="125" align="left">' . $date['MONTH'] . '/' . $date['DAY'] . '/' . $date['YEAR'] . '</td>
              <td width="205" align="left">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><img src="images/icons/label_box.gif" width="14" height="13" border="0" hspace="5" /></td>
                    <td>' . $foto_history[$keys[$i]]['COUNT'] . ' Pictures Uploaded&nbsp;</td>
                  </tr>
                </table>
              </td>
              <td width="75"><a href="/?action=' . $prefix . '.' . $fotos_url_part . $group_url_suffix . '&foto_ids=' . $foto_history[$keys[$i]]['IDS'] . '">show all</a></td>
            </tr>
            <tr>
              <td colspan="5" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="545">';

    $cols = 5;
    $i2    = 5;

    $foto_preview = array();
    $cnt_this_history = count($foto_history[$keys[$i]]);
    $limit_x = $cnt_this_history - 3;

    for($start = 0; $start < $cols && $start < $limit_x; $start ++)
    {
      $foto_preview[] = $foto_history[$keys[$i]][$start];
    }


    $popup_width  = FF_WEB_WIDTH + 10;
    $popup_height = FF_WEB_HEIGHT + 75;

    foreach($foto_preview as $p)
    {
      if($i2 % $cols == 0)
      {
        echo '<tr>';
      }

      $image_data = @getimagesize(PATH_FOTOROOT . $p['P_THUMB_PATH']);

      echo '<td width="' . intval(1 / $cols * 100) . '%">
              <a href="/popup/' . $viewer . '/' . $p['P_KEY'] . '" onClick="_open(this.href, ' . $popup_width . ', ' . $popup_height . '); return false;"><img src="' . PATH_FOTO . $p['P_THUMB_PATH'] . '?' . time() . '" width="40" height="40" hspace="0" vspace="5" border="0" /></a>
            </td>';

      if(($i2 % $cols) == ($cols - 1))
      {
        echo '</tr>
              <tr>
                <td><img src="images/spacer.gif" width="1" height="5" border="0" /></td>
              </tr>';
      }

      $i2++;
    }

    if( ((--$i2) % $cols) != ($cols - 1) )
    {
      while( ($i2 % $cols) != ($cols - 1) )
      {
        echo "<td>&nbsp;</td>\n";
        $i2++;
      }

      echo  '</tr>';
    }

    echo '     </table>
              </td>
            </tr>
          </table>';

    $counter++;
  }

  $tpl->main($tpl->get());
  $tpl->clean();
?>