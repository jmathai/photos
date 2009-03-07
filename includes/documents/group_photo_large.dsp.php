<?php
  $fb =& CFotobox::getInstance();
  
  $id = $_GET['id'];
  $group_id = $_GET['group_id'];
  $page = $_GET['page'];
  $offset = $_GET['offset'];
  $tags = $_GET['tags'];
  
  $fotoData = $fb->fotoData($id);
  
  $dynPhoto = dynamicImageLock($fotoData['P_THUMB_PATH'], $fotoData['P_KEY'], $fotoData['P_ROTATION'], $fotoData['P_WIDTH'], $fotoData['P_HEIGHT'], 800, 600);
  
  if(isset($tags) && $tags !== '')
  {
    echo '<div class="f_10 bold" style="padding-bottom:5px;"><a href="/?action=group.photo&group_id=' . $group_id . '&id=' . $id . '&page' . $page . '&offset=' . $offset . '&tags=' . $tags . '" title="back to photos">Back to photos</a></div>
          <div style="width:' . $dynPhoto[1] . 'px;"><a href="/?action=group.photo&group_id=' . $group_id . '&id=' . $id . '&page' . $page . '&offset=' . $offset . '&tags=' . $tags . '" title="back to photos"><img src="' . $dynPhoto[0] . '" ' . $dynPhoto[3] . ' id="photoMain" border="0" /></a></div>';
  }
  else 
  {
    echo '<div class="f_10 bold" style="padding-bottom:5px;"><a href="/?action=group.photo&group_id=' . $group_id . '&id=' . $id . '&page' . $page . '&offset=' . $offset . '" title="back to photos">Back to photos</a></div>
          <div style="width:' . $dynPhoto[1] . 'px;"><a href="/?action=group.photo&group_id=' . $group_id . '&id=' . $id . '&page' . $page . '&offset=' . $offset . '" title="back to photos"><img src="' . $dynPhoto[0] . '" ' . $dynPhoto[3] . ' id="photoMain" border="0" /></a></div>';
  }
?>