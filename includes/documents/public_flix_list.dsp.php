<?php
  $u  =& CUser::getInstance();
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();
  
  $flix_array = $fl->flixByStatus();
  
  echo '<table border="0" cellpadding="0" cellspacing="0">';
  
  $cnt_flix_array = count($flix_array);
  
  if($cnt_flix_array > 0)
  {
    $cols = 3;
    $i = 3;
    $width = (750 / $cols);
    
    foreach($flix_array as $v)
    {
      $user_data  = $u->find($v['A_U_ID']);
      $foto_id    = $v['A_DATA'][0]['D_UP_ID'];
      $foto_data  = $fb->fotoData($foto_id);
      $sizeArr    = explode('x', $v['A_SIZE']);
      $containerWidth      = $sizeArr[0];
      $containerHeight     = $sizeArr[1];
      
      if($i % $cols == 0)
      {
        echo '<tr>';
      }
      
      $swf_src = '/swf/flix_theme/layout_small/small_' . substr($v['A_TEMPLATE'], 1) . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $v['A_FASTFLIX'] . '&containerWidth=' . $containerWidth . '&containerHeight=' . $containerHeight;
      echo '<td align="center" width="' . $width . '">
              <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" WIDTH="133" HEIGHT="80">
                <PARAM NAME="movie" VALUE="' . $swf_src . '" />
                <param name="menu" value="false" />
                <EMBED menu="false" src="' . $swf_src . '" swliveconnect="true" quality="high" bgcolor="#FFFFFF" WIDTH="133" HEIGHT="80" TYPE="application/x-shockwave-flash" />
                </EMBED>
              </OBJECT>
              <br />
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left">
            Title: ' . $v['A_NAME'] . '<br />
            User: ' . $user_data['U_USERNAME'] . '<br />
            Fotos: ' . $v['A_FOTO_COUNT'] . '<br />
            Views: ' . $v['A_VIEWS'] . '
          </td>
        </tr>
      </table>
            </td>';
      
      if(($i % $cols) == ($cols - 1))
      {
        echo '</tr><tr><td colspan="' . $cols . '"><img src="images/spacer.gif" width="1" height="25" border="0" /></td></tr>';
      }
      
      $i++;
    }
  }
  
  echo '</table>';
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>