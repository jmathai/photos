<?php
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();
  
  $mp3_id = $args[1];
  
  $mp3_data = $fb->mp3($mp3_id, $_USER_ID);
  
  $mp3_deps = $fl->mp3Dependencies($mp3_data['M_PATH']);
  $cnt_deps = count($mp3_deps);
?>

<form name="_mp3_delete" method="post" action="/?action=fotobox.mp3_delete.act" style="display:inline">
  <input type="hidden" name="um_id" value="<?php echo $mp3_data['M_ID']; ?>" />
</form>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align="center">
      <div class="bold">Are you sure you want to delete this mp3? (<?php echo $mp3_data['M_NAME']; ?>)</div>
      <br />
      <a href="javascript:document.forms['_mp3_delete'].submit();"><img src="images/buttons/delete_mp3s.gif" border="0" /></a>
      &nbsp;
      <a href="javascript:history.back();"><img src="images/buttons/cancel.gif" border="0" /></a>
      <br /><br />
      <?php
        if($cnt_deps > 0)
        {
          echo 'This mp3 is currently active in the following ' . $cnt_deps . ' FotoFlix:<br /><br />
                <div style="width:325px; height:125px; overflow:auto;" align="left">
                <br />';
          
          foreach($mp3_deps as $k => $v)
          {
            $foto_id   = $v['A_DATA'][0]['D_UP_ID'];
            $foto_data = $fb->fotoData($foto_id);
            echo '&nbsp;&nbsp;<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" ID="a_' . $v['A_ID'] . '" WIDTH="133" HEIGHT="80">
              <PARAM NAME="movie" VALUE="/swf/flix_theme/layout_small/small_' . $v['A_TEMPLATE'] . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $v['A_FASTFLIX'] . '" />
              <param name="menu" value="false" />
              <EMBED name="a_' . $v['A_ID'] . '" menu="false" src="/swf/flix_theme/layout_small/small_' . $v['A_TEMPLATE'] . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $v['A_FASTFLIX'] . '" swliveconnect="true" quality="high" bgcolor="#FFFFFF" WIDTH="133" HEIGHT="80" TYPE="application/x-shockwave-flash" />
              </EMBED>
            </OBJECT>&nbsp;&nbsp;';
            
            if($k % 2 == 1)
            {
              echo '<br /><br />';
            }
          }
          
          echo '</div>';
        }
      ?>
    </td>
  </tr>
</table>