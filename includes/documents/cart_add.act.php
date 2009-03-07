<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
  $ecom->createCart();
  $cart_data = $ecom->getCartItems();
  
  /*
  * PROMO CODE
  */
  if(!empty($_REQUEST['promo_code']))
  {
    $promo_data = $ecom->getPromo($_REQUEST['promo_code'], NOW);
    if(is_array($promo_data))
    {
      $_REQUEST['ids'][] = $promo_data['C_ID'];
      $_REQUEST['quantities'][] = '1';
    }
  }
  
  $has_account = false;
  foreach($cart_data as $v)
  {
    if(strncmp($v['ecg_type'], 'account', 7) == 0)
    {
      $has_account  = $v['id'];
    }
    else
    if($v['ecg_type'] == 'promo' && $v['ecg_additional'] == '50_MB')
    {
      $has_promo    = $v['id'];
    }
  }
  
  $continue = true;
  
  if(isset($_REQUEST['ids']))
  {
    $ids = !empty($_REQUEST['ids']) ? $_REQUEST['ids'] : array();
    
    if(isset($_REQUEST['quantities']) && isset($_REQUEST['prices']) && isset($_REQUEST['quantities']))
    {
      $quantities = !empty($_REQUEST['quantities']) ? $_REQUEST['quantities'] : array();
      $prices     = !empty($_REQUEST['prices']) ? $_REQUEST['prices'] : array();
      $details    = !empty($_REQUEST['details']) ? $_REQUEST['details'] : array();
    }
    else
    {
      $quantities = array();
      foreach($ids as $k => $v)
      {
        $data = $ecom->getCatalogItem($v);
        if(strncmp($data['ecg_type'], 'account', 7) == 0 && $has_account !== false)
        {
          $ecom->removeCartItems(array($has_account, $has_promo));
        }
        
        $quantities[] = empty($_REQUEST['quantities']) ? 1 : $_REQUEST['quantities'][$k];
        $prices[] = $data['ecg_price'];
        $details[] = $data['ecg_name'];
      }
      
      if(count($quantities) == 0)
      {
        $continue = false;
      }
    }
  }
  else
  if(isset($_REQUEST['id']))
  {
    $item = $ecom->getCatalogItem($_REQUEST['id']);
    if(strncmp($item['ecg_type'], 'account', 7) == 0 && $has_account !== false)
    {
      $ecom->removeCartItems(array($has_account, $has_promo));
    }
    
    $ids = array($item['ecg_id']);
    $quantities = isset($_REQUEST['quantity']) ? array($_REQUEST['quantity']) : array(1);
    $prices = array($item['ecg_price']);
    $details = !empty($_REQUEST['details']) ? array($_REQUEST['details']) : array($item['ecg_description']);
  }
  else
  {
    $continue = false;
  }
  
  if($continue === true)
  {
    $ecom->addCartItems($ids, $quantities, $prices, $details);
  }
  
  //remove the DVD cookie if it exists
  setcookie('temp_dvd', '', NOW - 86400, '/');
  setcookie('temp_dvd_name', '', NOW - 86400, '/');

  if(!isset($_GET['redirect']))
  {
    $url = '/?action=cart.view';
  }
  else
  {
    $url = $_GET['redirect'];
  }
?>
