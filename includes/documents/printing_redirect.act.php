<?php
  if(!isset($_GET['opts']))
  {
    $_GET['opts'] = 'live||';
  }
  
  $url = QOOP_LINK . '&bonus=' . $_GET['opts'] . '&user_token=' . $_FF_SESSION->value('sess_hash');
?>