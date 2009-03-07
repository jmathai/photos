<?php
  $fl  =& CFlix::getInstance();
  $flm =& CFlixManage::getInstance();
  $usm =& CUserManage::getInstance();
  
  if(isset($_GET['fastflix']))
  {
    $flix_data  = $fl->search(array('KEY' => $_GET['fastflix']));
  }
  
  if(isset($flix_data['US_ID']))
  {
    if(($flix_data['US_PRIVACY'] & PERM_SLIDESHOW_PUBLIC) != PERM_SLIDESHOW_PUBLIC)
    {
      $flm->incrementOrder($_USER_ID);
      $order  = 1;
    }
    else
    {
      // move to the bottom (to make sure the order is correct)
      // then remove from the list
      $flm->reorder($_USER_ID, $_GET['fastflix'], 'bottom');
      $order = 0;
    }
    
    $main['US_KEY'] = $_GET['fastflix'];
    $main['USER_ID'] = $_USER_ID;
    
    if($order == 0)
    {
      $main['PRIVACY'] = PERM_SLIDESHOW_PRIVATE;
    }
    else
    {
      $main['PRIVACY'] = PERM_SLIDESHOW_PUBLIC;
    }
    
    $main['ORDER'] = $order;
    $flm->updateSlideshow($main);
    
    if(isset($_GET['redirect']))
    {
      $url = $_GET['redirect'];
    }
    else
    {
      $url = '/?action=flix.flix_list';
    }
  }
  else 
  {
    $url = '/?action=error';
  }
?>