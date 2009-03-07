<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CGroupManage.php';
  
  $gm =& CGroupManage::getInstance();
  
  $gm->deleteCommit(NOW, true);
?>