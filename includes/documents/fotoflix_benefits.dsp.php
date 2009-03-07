<?php
  $which_content = isset($_GET['content']) ? $_GET['content'] : '';
?>

<table width="733" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" align="center">
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="700" height="600" id="overview_container" align="middle">
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="movie" value="/swf/overview_container.swf?whichContent=<?php echo $which_content; ?>" />
        <param name="menu" value="false"/>
        <param name="quality" value="high" />
        <param name="bgcolor" value="#ffffff" />
        <embed src="/swf/overview_container.swf?whichContent=<?php echo $which_content; ?>" quality="high" bgcolor="#ffffff" width="700" height="600" name="overview_container" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
      </object>
    </td>
  </tr>
</table>
<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>