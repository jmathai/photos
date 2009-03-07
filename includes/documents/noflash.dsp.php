<?php
  if(!isset($noflash_included))
  {
    echo '<div style="width:100%;" align="center">
            <div><a href="http://www.macromedia.com/go/getflashplayer" target="_blank"><img src="images/exclamation.gif" width="41" height="40" border="0" hspace="5" alt="Flash Player not detected - click here to download" /></a></div>
            <div>Sorry, but you do not have the latest version of <a href="http://www.macromedia.com/go/getflashplayer" target="_blank">Flash Player</a> installed.</div>
          </div>';

    $noflash_included = true;
  }
?>