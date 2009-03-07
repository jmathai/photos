<?php
  $ci = &CCitizenImage::getInstance();
  
  // see if they have a ci account already set up
  // if they don't then send them to the register and member agreement page
  // otherwise, send them to the upload images page
  $citizenRs = $ci->search(array('U_ID' => $_USER_ID));
  
  if(empty($citizenRs))
  {
    $url = '/?action=ci.register';
  }
  else 
  {
    $url = '/?action=ci.upload';
  }
  
?>