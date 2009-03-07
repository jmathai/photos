<?php
  include '../../init_constants.php';
  include PATH_INCLUDE . '/functions.php';
  include PATH_HOMEROOT . '/init.php';
  include_once PATH_DOCROOT . '/init_database.php';
  
  $dynamics = $GLOBALS['dbh']->query_all('SELECT * FROM user_fotos_dynamic');
  
  foreach($dynamics as $v)
  {
    unlink(PATH_FOTOROOT . $v['ufd_source']);
    echo "Deleted {$v['ufd_source']} <br />";
  }
  
  $GLOBALS['dbh']->query_all('DELETE FROM user_fotos_dynamic');
?>