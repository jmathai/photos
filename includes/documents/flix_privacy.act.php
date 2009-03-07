<?php
  $fl  =& CFlix::getInstance();
  $flm =& CFlixManage::getInstance();
  $usm =& CUserManage::getInstance();
  
  if(isset($_GET['fastflix']))
  {
    $flix_data  = $fl->search(array('KEY' => $_GET['fastflix'], 'USER_ID' => $_USER_ID));
  }
  else 
  if(isset($_GET['flix_id']))
  {
    $flix_data  = $fl->search(array('FLIX_ID' => $_GET['flix_id'], 'USER_ID' => $_USER_ID, 'RETURN_TYPE' => 'SINGLE_FOTO'));
  }
  
  if(isset($flix_data['US_ID']))
  {
    if($_GET['privacy']{0} > 1)
    {
      $flm->incrementOrder($_USER_ID);
      $order  = '1';
    }
    else
    {
      $flm->decrementOrder($_USER_ID, $flix_data['A_FASTFLIX']);
      $order  = '0';
    }
    
    $array_update = array('us_id' => $flix_data['US_ID'], 'us_privacy' => $_GET['privacy']);
    
    $flm->update($array_update);
    $usm->setFotoPage($_USER_ID);
    
    if(isset($_GET['redirect']))
    {
      $url = $_GET['redirect'];
    }
    else
    {
      $url = '/?action=flix.flix_list&message=public_' . $public;
    }
  }
  else 
  {
    $url = '/?action=error';
  }
?>