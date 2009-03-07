<?php
  /*
  * Requires the following data:
  * $persistent_login     boolean
  * $user_id              int
  * $username             string
  * $email                string
  * $account_type         string
  * $account_perm         string
  */
  
  // reinitialize session
  unset($_FF_SESSION);
  $_FF_SESSION =& new CSession(false, $user_id);
  
  $persistent = $persistent_login === true ? 1 : 0;
  
  $session_id  = $_FF_SESSION->start(false, $persistent_login);
  $_FF_SESSION->register('user_id', $user_id);
  $_FF_SESSION->register('email', $email);
  $_FF_SESSION->register('username', $username);
  $_FF_SESSION->register('session_id', $session_id);
  $_FF_SESSION->register('account_perm', $account_perm);
  $_FF_SESSION->register('is_trial', $is_trial);
  $_FF_SESSION->register('persistent', $persistent);
  
  // adding _USER_ID because login.act.php relies on that
  $_USER_ID = $user_id;
?>