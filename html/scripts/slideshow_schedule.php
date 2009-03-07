<?php
  include_once dirname(__FILE__) . '/../init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/functions.php';
  
  include_once PATH_CLASS . '/CFlix.php';
  include_once PATH_CLASS . '/CFlixManage.php';
  
  $fl =& CFlix::getInstance();
  $flm=& CFlixManage::getInstance();
  
  $date = date('Y-m-d 00:00:00', NOW);
  
  $time = strtotime($date);
  
  $schedulesStart = $fl->getSchedules($date, 'start');
  $schedulesEnd   = $fl->getSchedules($date, 'end');
  
  foreach($schedulesStart as $v)
  {
    $flixData = $fl->search(array('FLIX_ID' => $v['USS_US_ID']));
    $privacy  = $flixData['US_PRIVACY'] | PERM_SLIDESHOW_PUBLIC;
    $flm->updateSlideshow(array('USER_ID' => $v['USS_U_ID'], 'US_ID' => $v['USS_US_ID'], 'PRIVACY' => $privacy));
    echo 'set to show ' . $v['USS_US_ID'] . '<br/>';
  }
  
  foreach($schedulesEnd as $v)
  {
    $flixData = $fl->search(array('FLIX_ID' => $v['USS_US_ID']));
    $privacy  = PERM_SLIDESHOW_PRIVATE;
    $flm->updateSlideshow(array('USER_ID' => $v['USS_U_ID'], 'US_ID' => $v['USS_US_ID'], 'PRIVACY' => $privacy));
    echo 'set to stop ' . $v['USS_US_ID'] . '<br/>';
  }
?>