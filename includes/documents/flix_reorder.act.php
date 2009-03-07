<?php
  $flm =& CFlixManage::getInstance();
  
  $flm->reorder($_USER_ID, $_GET['fastflix'], $_GET['move']);
  
  $url = isset($_GET['redirect']) ? $_GET['redirect'] : '/';
?>