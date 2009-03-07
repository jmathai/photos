<?php
  
  include_once './init_constants.php';
  include_once PATH_CLASS . '/CCitizenImage.php';
  
  $obj =& CCitizenImageLocation::getInstance();
  
  $obj->createMemberMethods();
  
  $obj =& CCitizenImage::getInstance();
  
  $obj->setUsername($_POST['ci_username']);
  $obj->setPassword($_POST['ci_password']);
  $obj->setFirstname($_POST['ci_name_first']);
  $obj->setLastname($_POST['ci_name_last']);
  $obj->setEmailaddress($_POST['ci_email']);
  $obj->setPaymenttype($_POST['ci_payment_type']);
  $obj->setStreetaddress1($_POST['ci_street_1']);
  $obj->setStreetaddress2($_POST['ci_street_2']);
  $obj->setCity($_POST['ci_city']);
  $obj->setState($_POST['ci_state']);
  $obj->setZip($_POST['ci_zip']);
  $obj->setCountry($_POST['ci_country']);
  $obj->setAcceptagreement('true');
  
  $obj->save();
  
  $url = '/?action=ci.home_page';
  
  /*
  $qs = '';
  foreach($_POST as $k => $v)
  {
    if(strpos($k, 'password') === false && strpos($k, 'action') === false)
    {
      $qs .= '&' . $k . '=' . $v;
    }
  }

  $url = '/?action=ci.register' . $qs;
  */
?>