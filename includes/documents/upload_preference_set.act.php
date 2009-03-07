<?php
  $_array = array();
  
  $_FF_SESSION->register('fotobox_page', 1);
  
  setcookie('ff_uploader_preference', intval($_GET['uploader_preference']), NOW + 2592000, '/');
  
  if(isset($_GET['redirect']))
  {
    $url = $_GET['redirect'];
  }
  else 
  {
    $url = '/?action=fotobox.fotobox_myfotos&message=fotos_uploaded';
  }
?>