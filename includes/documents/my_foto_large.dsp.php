<?php
  $fb =& CFotobox::getInstance();
  $fotoData = $fb->fotoData($options[0], $user_id);
  
  $dynPhoto = dynamicImageLock($fotoData['P_THUMB_PATH'], $fotoData['P_KEY'], $fotoData['P_ROTATION'], $fotoData['P_WIDTH'], $fotoData['P_HEIGHT'], 800, 600);
  
  echo '<div class="f_10 bold" style="padding-bottom:5px;"><a href="/users/' . $username . '/photo/' . $options[0] . '/" title="back to photos">Back to photos</a></div>
        <div style="width:' . $dynPhoto[1] . 'px; margin:auto;" class="border_dark"><a href="/users/' . $username . '/photo/' . $options[0] . '/" title="back to photos"><img src="' . $dynPhoto[0] . '" ' . $dynPhoto[3] . ' id="photoMain" border="0" /></a></div>';
?>