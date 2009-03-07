<?php
  $fl =& CFlix::getInstance();
  
  $id = $_GET['id'];
  $commentId = $_GET['commentId'];
  
  $slideshow = $fl->search(array('FLIX_ID' => $id));
  
  $url = '/slideshow?' . $slideshow['US_KEY'] . '#comment' . $commentId;
?>