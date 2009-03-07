<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';

  $directories = array('original', 'thumbnail', 'mp3', 'custom', 'videos', 'base');

  $_next_month_time = strtotime('+1 month');

  $time = mktime(0, 0, 0, date('m', $_next_month_time), 1, date('Y', $_next_month_time));

  foreach($directories as $v)
  {
    if($v == 'videos')
    {
      $path = PATH_VIDEOROOT . '/' . date('Ym', $time);
    }
    else
    {
      $path = PATH_FOTOROOT . '/' . $v . '/' . date('Ym', $time);
    }
    echo "Created directory...{$path}\n";
    mkdir($path, 0755);
    chmod($path, 0755);
    chown($path, 'apache');
    chgrp($path, 'apache');
  }
  echo "------" . date('m-d-Y', NOW) . "------\n\n";
?>
