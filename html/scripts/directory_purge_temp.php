<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  
  $d = dir(PATH_TMPROOT);
  
  $time_limit = NOW - 3600;
  
  while (($file = $d->read()) !== false)
  {
    if(is_file($file_path = PATH_TMPROOT . '/' . $file))
    {
      if(filectime($file_path) < $time_limit)
      {
        unlink($file_path);
      }
    }
  }
  
  $d->close();
?>