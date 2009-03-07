<?php
  include_once './init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CFlix.php';
  
  $fl =& CFlix::getInstance();
  
  if(isset($_GET['user_id']))
  {
    $arr = $fl->categories(intval($_GET['user_id']));
  }
  else
  {
    $arr = $fl->categories();
  }
  
  $arr = array_merge(array('Show All'), array_slice($arr, 0));
  
  header('Content-type: text/xml');
  
  echo '<?xml version="1.0"?>';
  echo '<ffData>';
  
  foreach($arr as $v)
  {
    echo '<ffItem  c_name="' . $v . '" />' . "\n";
  }

  echo '</ffData>';
  
  include_once PATH_DOCROOT . '/garbage_collector.act.php';
?>