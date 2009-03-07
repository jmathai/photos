<?php
  $t =& CTag::getInstance();
  $t->generateWeights($_USER_ID);
  
  $url = '/?action=flix.view_all_tags';
?>