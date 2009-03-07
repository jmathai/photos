<?php
  $_FF_SESSION->destroy();
  
  if(isset($_GET['redirect']))
  {
    $url = $_GET['redirect'];
  }
  else
  {
    $url = '/';
  }
?>