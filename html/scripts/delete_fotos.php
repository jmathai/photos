<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CFotobox.php';
  
  $fb =& CFotobox::getInstance();
  
  // select fotos which have not been modified in the last 10 days
  $fotos_array = $fb->fotosByStatus('Deleted', strtotime('-10 days', NOW));
  
  echo "--- Deleting fotos for " . date('m-d-Y', NOW) . " ---\n";
  foreach($fotos_array as $v)
  {
    if(file_exists($filename = PATH_FOTOROOT . $v['P_THUMB_PATH']))
    {
      unlink($filename);
    }
    
    if(file_exists($filename = PATH_FOTOROOT . $v['P_FLIX_PATH']))
    {
      unlink($filename);
    }
    
    if(file_exists($filename = PATH_FOTOROOT . $v['P_WEB_PATH']))
    {
      unlink($filename);
    }
    
    if(file_exists($filename = PATH_FOTOROOT . $v['P_ORIG_PATH']))
    {
      unlink($filename);
      echo "  - Deleted " . $v['P_ORIG_PATH'] . "\n";
    }
  }
?>
