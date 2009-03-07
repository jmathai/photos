<?php
	$agent = trim($_SERVER['HTTP_USER_AGENT']);
	if ((stristr($agent, 'wind') || stristr($agent, 'winnt')) && (preg_match('|MSIE ([0-9.]+)|', $agent) || preg_match('|Internet Explorer/([0-9.]+)|', $agent)))
	{
		$_popupBlockedCss = 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'images/transbg.png\',sizingMethod=\'scale\');';
	}
	else
	{
		$_popupBlockedCss = 'background-image:url(images/transbg.png);';
	}
?>
<div id="popupBlocked" align="center" style="display:none; width:350px; height: 200px; position:absolute; top:50%; left:50%; margin-left:-175px; margin-top:-175px; z-index:20;">
  <div id="popupBlockedContent" style="width:350px; height:200px; border:1px dashed #666666; color:#000000; z-index:22; <?php echo $_popupBlockedCss; ?>">
    <table border="0" cellpadding="0" cellspacing="0" width="300" height="100%" align="center">
      <tr>
        <td width="51"><img src="images/exclamation.gif" width="41" height="40" border="0" hspace="5" /></td>
        <td align="left">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="150">
            <tr height="9">
              <td width="8"><img src="images/white_curve_top_left.gif" width="8" height="9" /></td>
              <td bgcolor="#ffffff"><img src="images/spacer.gif" width="1" height="1" /></td>
              <td width="8"><img src="images/white_curve_top_right.gif" width="8" height="9" /></td>
            </tr>
            <tr>
              <td width="8" bgcolor="#ffffff"><img src="images/spacer.gif" width="1" height="1" /></td>
              <td bgcolor="#ffffff">
                <div class="bold">Unable to open popup</div>
                <br />
                Sorry, FotoFlix was unable to open a popup window.  
                Your browser may not support popups or have them disabled.
                <br /><br />
                Please enable popups for this website.
              </td>
              <td width="8" bgcolor="#ffffff"><img src="images/spacer.gif" width="1" height="1" /></td>
            </tr>
            <tr height="9">
              <td width="8"><img src="images/white_curve_bottom_left.gif" width="8" height="9" /></td>
              <td bgcolor="#ffffff"><img src="images/spacer.gif" width="1" height="1" /></td>
              <td width="8"><img src="images/white_curve_bottom_right.gif" width="8" height="9" /></td>
            </tr>
            
          </table>
        </td>
      </tr>
    </table>
  </div>
</div>