<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  ob_start();
  include_once $path = str_replace('scripts', '', dirname(__FILE__)) . 'init_constants.php';
  
  set_time_limit(900); // 15 minutes - surely it will run out of memory by then?
  
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CTag.php';
  
  $t =& CTag::getInstance();
  
  $segments = range('a', 'z');
  $hour = date('G', NOW);
  
  $purge = date('H', NOW) == '05' && date('i', NOW) > '29' ? true : false; // purge tags at 05:00
  
  if($hour < 23) // midnight through 10pm
  {
    $letter = $segments[$hour];
    $users = $GLOBALS['dbh']->query_all("SELECT u_id, u_username, u_spaceUsed FROM users WHERE u_username LIKE '{$letter}%' AND u_dateExpires > NOW()");
  }
  else // at 11pm run for users starting with x, y or z
  {
    $users = $GLOBALS['dbh']->query_all("SELECT u_id, u_username, u_spaceUsed FROM users WHERE (u_username LIKE 'z%' OR u_username LIKE 'y%' OR u_username LIKE 'x%') AND u_dateExpires > NOW()");
  }
  
  echo "**** Generating tags on " . date(FF_FORMAT_DATE_LONG, NOW) . "\n";
  
  foreach($users as $v)
  {
    $GLOBALS['dbh']->execute('DELETE FROM user_tags WHERE ut_u_id = ' . intval($v['u_id']));
    $t->generateWeights($v['u_id']);
    echo "  Updated tags::user {$v['u_username']}\n";
  }
?>
