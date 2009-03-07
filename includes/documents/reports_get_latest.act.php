<?php
  $r =& CReport::getInstance();
  
  $key = $_SERVER['QUERY_STRING'];
  
  if(strlen($key) != 32)
  {
    $reportData = $r->getCurrentReport($key);
    $url = '/report?' . $reportData['RA_KEY'];
  }
?>