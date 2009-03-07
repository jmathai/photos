<?php
  $registration = $GLOBALS['dbh']->query_first('SELECT bi_key FROM beta_invites WHERE bi_key = ' . $GLOBALS['dbh']->sql_safe($_GET['registrationKey']));
  $registrationKey = $registration['bi_key'];
  if(strlen($registrationKey) == 32)
  {
    setcookie('registrationKey', $registrationKey);
    $url = '/';
  }
  else
  {
    $url = '/';
  }
?>