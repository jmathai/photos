<?php
  $dir = dirname($_SERVER['SCRIPT_FILENAME']);
  
  $list= `ls {$dir}/*.err`;
  $listArr = explode("\n", $list);
  
  foreach($listArr as $v)
  {
    if($v != '' && is_file($v))
    {
      $contents = file_get_contents($v);
      mail('support@fotoflix.com', 'URGENT: Script Failed - ' . basename($v, '.err'), $contents);
      unlink($v);
    }
  }
?>