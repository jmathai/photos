<?php
  if(isset($GLOBALS['dbh']))
  {
    $GLOBALS['dbh']->close();
  }
?>