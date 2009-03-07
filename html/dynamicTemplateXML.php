<?php
  include_once './init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CFlix.php';
  
  if(!isset($_GET['user_id']))
  {
    die('No user id specified');
  }
  
  $fl =& CFlix::getInstance();
  
  $arr = $fl->templates(false, $_GET['user_id']);
  
  header('Content-type: text/xml');
  
  echo  '<?xml version="1.0"?>'
      . '<ffData>';
  
  foreach($arr as $v)
  {
    $sizeArr  = explode('x', $v['T_SIZE']);
    $width    = $sizeArr[0];
    $height   = $sizeArr[1];
    
    echo '<ffItem 
              t_id="' . $v['T_ID'] . '"
              t_name="' . $v['T_NAME'] . '" 
              t_width="' . $width . '"
              t_height="' . $height . '"
              t_screenshot="' . $v['T_SCREENSHOT'] . '" 
              t_swf="' . $v['T_SWF'] . '" 
              t_categories="' . $v['T_CATEGORIES'] . '" 
              t_type="' . $v['T_TYPE'] . '" 
            />';
  }

  echo '</ffData>';
  
  include_once PATH_DOCROOT . '/garbage_collector.act.php';
?>
