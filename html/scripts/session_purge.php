<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  
  $time_expired = date('Y-m-d 00:00:00', NOW - FF_SESSION_LENGTH);
  
  $rs = $dbh->query("SELECT us_id FROM  user_session WHERE us_timeAccessed < '{$time_expired}'");
  
  echo "Purging sessions for " . date('Y-m-d', NOW - FF_SESSION_LENGTH) . "...";
  while($data = $dbh->fetch_assoc($rs))
  {
    $dbh->execute('DELETE FROM user_session WHERE us_id = ' . $data['us_id']);
    $dbh->execute('DELETE FROM user_session_data WHERE us_id = '  . $data['us_id']);
  }
  echo "OK\n";
?>