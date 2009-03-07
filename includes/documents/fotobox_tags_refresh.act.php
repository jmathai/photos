<?php
  $t =& CTag::getInstance();
  $t->generateWeights($_USER_ID);
  
  $url = '/?action=fotobox.view_all_tags';
?>