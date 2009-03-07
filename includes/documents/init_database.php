<?php
  include_once PATH_CLASS . '/CDatabase.php';
  
  $GLOBALS['dbh'] = init_db(DB_DBMS . '://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . '/' . DB_NAME);
?>
