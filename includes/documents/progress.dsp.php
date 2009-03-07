<?php
  $iTotal = $_GET['iTotal'];
  $iRead  = $_GET['iRead'];
  $iStatus= $_GET['iStatus'];
  $sessionid = $_GET['sessionid'];
  
  $urlParams = 'iTotal=' . $iTotal . '&iRead=' . $iRead . '&iStatus=' . $iStatus . '&sessionid=' . $sessionid . '&parentMode=' . $action;
?>
<script language="javascript">
  window.status = 'Transferring File...';
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
  <tr>
    <td align="center" valign="middle">
      <!--
      <iframe src="/cgi-bin/progress_flash.cgi?<?php echo $urlParams; ?>" width="350" height="50" id="_iframe"></iframe>
      <script language="javascript">
        function _refresh()
        {
          document.getElementById('_iframe').src = document.getElementById('_iframe').src;
          setTimeout('_refresh()', 2500);
        }
        
        _refresh();
      </script>
      -->
      <div style="width:352px; height:234px; background-color:#D0D1D6; border:1px solid #666666; color:#444444;">
        <div align="center">
          <?php
            if($action == 'mp3_progress')
            {
              echo '
                <div style="padding-top:10px;">
                  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="250" height="100" id="upload_animation" align="middle">
                  <param name="allowScriptAccess" value="sameDomain" />
                  <param name="movie" value="/swf/upload_music.swf" />
                  <param name="quality" value="high" />
                  <param name="bgcolor" value="#D0D1D6" />
                  <embed src="/swf/upload_music.swf" quality="high" bgcolor="#D0D1D6" width="250" height="100" name="upload_animation" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                  </object>
                </div>
              ';
            }
            else
            if($action == 'foto_progress')
            {
              echo '
                <img src="images/upload_header.gif" width="313" height="41" border="0" vspace="10"><br /><br />
                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="250" height="60" id="upload_animation" align="middle">
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="movie" value="/swf/upload_animation_small.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#ffffff" />
                <embed src="/swf/upload_animation_small.swf" quality="high" bgcolor="#ffffff" width="250" height="60" name="upload_animation" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                </object>
              ';
            }
          ?>
          <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="300" height="50" id="upload_animation" align="middle">
          <param name="allowScriptAccess" value="sameDomain" />
          <param name="movie" value="/swf/progress.swf?<?php echo $urlParams; ?>" />
          <param name="quality" value="high" />
          <param name="bgcolor" value="#D0D1D6" />
          <embed src="/swf/progress.swf?<?php echo $urlParams; ?>" quality="high" bgcolor="#D0D1D6" width="300" height="50" name="upload_animation" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
          </object>
          <!--<iframe src="/cgi-bin/progress.cgi?iTotal=<?php echo $iTotal; ?>&iRead=<?php echo $iRead; ?>&iStatus=<?php echo $iStatus; ?>&sessionid=<?php echo $sessionid; ?>" width="100%" height="50" frameborder="0" marginwidth="0" marginheight="0" style="background-color:#D0D1D6;"></iframe>-->
          <!--<a href="javascript:opener.close(); self.close();"><img src="images/buttons/cancel.gif" width="87" height="25" vspace="10" border="0" /></a>-->
        </div>
      </div>
    </td>
  </tr>
</table>