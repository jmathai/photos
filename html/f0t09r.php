<?php
  include_once './init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_CLASS . '/CSession.php';
  
  if(isset($_GET['username']))
  {
    $userData = $GLOBALS['dbh']->query_first('SELECT * FROM users WHERE u_username = ' . $GLOBALS['dbh']->sql_safe($_GET['username']));
    
    $user_id = $userData['u_id'];
    $email = $userData['u_email'];
    $username = $userData['u_username'];
    $account_perm = $userData['u_accountType'];
    $is_trial = $userData['u_isTrial'];
    
    include PATH_DOCROOT . '/login_manual.act.php';
    header('Location: /');
  }
?>