<?php
  $fb =& CFotobox::getInstance();
  $fl =& CFlix::getInstance();

  $fotoId = intval($_GET['foto_id']);
  $fotoData = $fb->fotoData($fotoId, $_USER_ID);
  //$flixData = $fl->flixByFotoIds(array($fotoId), false);

  $fotoName = basename($fotoData['P_THUMB_PATH']);
    $fotoName = substr($fotoName, strpos($fotoName, '_')+1);
    
  $smallMedium = dynamicImageLock($fotoData['P_THUMB_PATH'], $fotoData['P_KEY'], $fotoData['P_ROTATION'], $fotoData['P_WIDTH'], $fotoData['P_HEIGHT'], 500, 375);
  $medium = dynamicImageLock($fotoData['P_THUMB_PATH'], $fotoData['P_KEY'], $fotoData['P_ROTATION'], $fotoData['P_WIDTH'], $fotoData['P_HEIGHT'], 600, 450);
  $large  = dynamicImageLock($fotoData['P_THUMB_PATH'], $fotoData['P_KEY'], $fotoData['P_ROTATION'], $fotoData['P_WIDTH'], $fotoData['P_HEIGHT'], 800, 600);
?>

<div style="width:740px;" align="left">

  <div class="bold">All available sizes for <?php echo $fotoName; ?></div>

  <div style="padding-top:10px;">
    <div class="bullet"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>#photoSquareThumbnail">Square Thumbnail (75x75)</a></div>
    <div class="bullet"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>#photoSmall-Medium">Small-Medium (500x375)</a></div>
    <div class="bullet"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>#photoMedium">Medium (600x450)</a></div>
    <div class="bullet"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>#photoLarge">Large (800x600)</a></div>
    <div class="bullet"><a href="/download?key=<?php echo $fotoData['P_KEY']; ?>" title="download the original">Download the original version (<?php echo $fotoData['P_WIDTH'] . 'x' . $fotoData['P_HEIGHT'] . ' :: ' . ($fotoData['P_SIZE'] > KB ? number_format(($fotoData['P_SIZE'] / KB), 1).'MB' : $fotoData['P_SIZE'].'KB'); ?>)</a></div>
  </div>

  <div style="padding-top:10px;">
    <div>Square Thumbnail (75x75<a name="photoSquareThumbnail"></a>)</div>
    <div style="padding-left:10px;">
      <div><img src="<?php echo PATH_FOTO . $fotoData['P_THUMB_PATH']; ?>" width="75" height="75" border="0" class="border_dark" /></div>
      <div class="bullet">
        <div>Link to this photo</div>
        <div><input type="text" class="formfield" style="width:550px;" value="<?php echo 'http://' . FF_SERVER_NAME . PATH_FOTO . $fotoData['P_THUMB_PATH']; ?>" /></div>
       </div>
       <div class="bullet">
        <div style="padding-top:3px;">Embed this photo on a blog or web page</div>
        <div><textarea class="formfield" style="width:550px; height:100px;"><?php echo htmlentities('<a href="http://' . FF_SERVER_NAME . '/handler/photo/' . $fotoData['P_KEY'] . '/"><img src="http://' . FF_SERVER_NAME . PATH_FOTO . $fotoData['P_THUMB_PATH'] . '" width="75" height="75" border="0" /></a>'); ?></textarea></div>
      </div>
    </div>
  </div>

  <div style="padding-top:10px;">
    <div>Small-Medium (500x375)<a name="photoSmall-Medium"></a></div>
    <div style="padding-left:10px;">
      <div><img src="<?php echo $smallMedium[0]; ?>" <?php echo $smallMedium[3]; ?> border="0" class="border_dark" /></div>
      <div class="bullet">
        <div>Link to this photo</div>
        <div><input type="text" class="formfield" style="width:550px;" value="<?php echo $smallMedium[0]; ?>" /></div>
       </div>
       <div class="bullet">
        <div style="padding-top:3px;">Embed this photo on a blog or web page</div>
        <div><textarea class="formfield" style="width:550px; height:100px;"><?php echo htmlentities('<a href="http://' . FF_SERVER_NAME . '/handler/photo/' . $fotoData['P_KEY'] . '/"><img src="' . $smallMedium[0] . '" ' . $smallMedium[3] . ' border="0" /></a>'); ?></textarea></div>
      </div>
    </div>
  </div>

  <div style="padding-top:10px;">
    <div>Medium (600x450)<a name="photoMedium"></a></div>
    <div style="padding-left:10px;">
      <div><img src="<?php echo $medium[0]; ?>" <?php echo $medium[3]; ?> border="0" class="border_dark" /></div>
      <div class="bullet">
        <div>Link to this photo</div>
        <div><input type="text" class="formfield" style="width:550px;" value="<?php echo $medium[0]; ?>" /></div>
       </div>
       <div class="bullet">
        <div style="padding-top:3px;">Embed this photo on a blog or web page</div>
        <div><textarea class="formfield" style="width:550px; height:100px;"><?php echo htmlentities('<a href="http://' . FF_SERVER_NAME . '/handler/photo/' . $fotoData['P_KEY'] . '/"><img src="' . $medium[0] . '" ' . $medium[3] . ' border="0" /></a>'); ?></textarea></div>
      </div>
    </div>
  </div>
  
  <div style="padding-top:10px;">
    <div>Large (800x600)<a name="photoLarge"></a></div>
    <div style="padding-left:10px;">
      <div><img src="<?php echo $large[0]; ?>" <?php echo $large[3]; ?> border="0" class="border_dark" /></div>
      <div class="bullet">
        <div>Link to this photo</div>
        <div><input type="text" class="formfield" style="width:550px;" value="<?php echo $large[0]; ?>" /></div>
       </div>
       <div class="bullet">
        <div style="padding-top:3px;">Embed this photo on a blog or web page</div>
        <div><textarea class="formfield" style="width:550px; height:100px;"><?php echo htmlentities('<a href="http://' . FF_SERVER_NAME . '/handler/photo/' . $fotoData['P_KEY'] . '/"><img src="' . $large[0] . '" ' . $large[3] . ' border="0" /></a>'); ?></textarea></div>
      </div>
    </div>
  </div>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>