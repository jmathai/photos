<table border="0" cellpadding="2" cellspacing="3" width="100%">
  <tr>
    <td align="left">
      We are working hard to bring FotoFlix to the masses. 
      However, there are some minimum site requirements which are outlined below.
      <br />
      <br />
      
      <script langugae="javascript">
        var _js_enabled = false;
      </script>
      <div class="bold">Testing your computer for required software...</div>
      <table border="0" cellpadding="0">
        <tr height="28">
          <td width="5" style="background-color:red;" id="_flash_check">&nbsp;</td>
          <td align="left" valign="middle">&nbsp;Macromedia Flash Player:&nbsp;</td>
          <td align="left" valign="middle">
            <object type="application/x-shockwave-flash" data="/swf/flash_enabled.swf" width="100" height="28">
              <!--<param name="movie" value="/swf/flash_enabled.swf" />-->
              <param name="menu" value="false" />
              <param name="quality" value="high" />
              <param name="bgcolor" value="#ffffff" />
              <a href="http://www.macromedia.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" target="_blank"><img src="images/overview/get_flash_player.gif" width="88" height="31" border="0"></a>
            </object>
          </td>
        </tr>
        <tr>
          <td colspan="3"><div class="line_dark"></div></td>
        </tr>
        <tr height="28">
          <td width="5" style="background-color:red;" id="_js_check">&nbsp;</td>
          <td align="left" valign="middle">&nbsp;Javascript enabled:&nbsp;</td>
          <td align="left" valign="middle">
            <script language="javascript"> document.write('Javascript enabled'); _js_enabled = true; </script>
            <noscript>Javascript not enabled!</noscript>
          </td>
        </tr>
      </table>
      <script lanauge="javascript">
        function _flash_is_here()
        {
          document.getElementById('_flash_check').style.backgroundColor = 'green';
        }
        
        if(_js_enabled == true){ document.getElementById('_js_check').style.backgroundColor = 'green'; }
      </script>
      <br />
      <div class="line_dark"></div>
      <br />
      <div class="bold">Software:</div>
      <ol>
        <li>Using MS Windows: Internet Explorer 5.0 / Firefox 1.0 / Mozilla 1.4</li>
        <li>Using Macintosh: Firefox 1.0 / Safari 1.2</li>
        <li>Using Linux: Firefox 1.0 / Mozilla 1.4 / Konqueror 3.2</li>
        <li>Javascript enabled</li>
        <li><a href="http://www.macromedia.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Macromedia Flash Player 7</a></li>
      </ol>
      <div class="bold">Hardware:</div>
      <ol>
        <li>Pentium III / AMD Athlon or equivalent processor</li>
        <li>64MB RAM</li>
        <li>Video card with 16MB RAM</li>
      </ol>
      <div class="bold">In addition to site requirements we also recommend that you have the following:</div>
      <ol>
        <li>Broadband Internet connection (i.e. DSL or Cable Modem)</li>
        <li>Latest version of <a href="http://www.microsoft.com/windows/ie/default.mspx"> Internet
            Explorer</a> or <a href="http://www.mozilla.org/products/firefox/">Mozilla FireFox</a></li>
        <li>Screen resolution of at least 800x600</li>
        <li>Ability to hear audio</li>
      </ol>
    </td>
  </tr>
</table>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>