<?php
  ini_set('max_execution_time', 0);
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CFotobox.php';
  include_once PATH_CLASS . '/CFotoboxManage.php';
  
  $fb =& CFotobox::getInstance();
  $fbm=& CFotoboxManage::getInstance();
  
  // select fotos which have not been modified in the last 10 days
  //$fotos_array = $fb->fotosByStatus('Deleted', strtotime('-10 days', NOW));
  $fotos_array = $fb->fotosByStatus('Deleted');
  
  echo "--- Deleting fotos for " . date('m-d-Y', NOW) . " ---\n";
  foreach($fotos_array as $v)
  {
    if(file_exists($filename = PATH_FOTOROOT . $v['P_THUMB_PATH']))
    {
      $fbm->remove($v['P_U_ID'], $v['P_ID']);
    }
  }
?>
