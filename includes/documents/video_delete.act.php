<?php
  $v =& CVideo::getInstance();
  
  $videoKey = $_GET['videoKey'];
  
  $v->delete($videoKey, $_USER_ID);
  
  $url = '/?action=video.list&message=deleted';
?>