<?php
  $fb =& CFotobox::getInstance();

  $key = $_GET['key'];

  $fotoData = $fb->fotoData($key);
  
  if($fotoData)
  {
    $fotoUrl = dynamicImageLock($fotoData['P_THUMB_PATH'], $fotoData['P_KEY'], $fotoData['P_ROTATION'], $fotoData['P_WIDTH'], $fotoData['P_HEIGHT'], 800, 600);
    echo '<div align="center">
            <img src="' . $fotoUrl[0] . '" ' . $fotoUrl[3] . ' class="border_medium" border="0" />
          </div>';
            /*<div style="padding-top:5px;">
              <div>' . $fotoData['P_NAME'] . '</div>
              <div style="padding-top:5px;">' . $fotoData['P_DESC'] . '</div>
            </div>';*/
  }
  else
  {
    echo '<div align="center">Sorry, there was a problem locating the photo you requested.</div>';
  }
?>