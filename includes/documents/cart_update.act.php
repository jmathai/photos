<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
  
  if (!empty($_POST['ids']))
  {
  	$task = !empty($_GET['task']) ? $_GET['task'] : 'update';
  	if ($task == 'remove')
  	{
  		$ecom->removeCartItems($_POST['ids']);
  	}
  	else 
  	{
  	  $ecom->updateCartItems($_POST['ids'], $_POST['quantities'], $_POST['prices']);
  	}
  	
  	$cart_data = $ecom->getCartItems();
  	
  	$has_promo   = false;
  	$has_account = false;
  	foreach($cart_data as $v)
  	{
  	  if($v['ecg_type'] == 'promo' && $v['ecg_additional'] == '50_MB')
  	  {
  	    $has_promo = $v['id'];
  	  }
  	  else
  	  if(strncmp($v['ecg_type'], 'account', 7) == 0)
  	  {
  	    $has_account = $v['id'];
  	  }
  	}
  	
  	if($has_promo !== false && $has_account === false)
  	{
  	  $ecom->removeCartItems(array($has_promo));
  	}
  }
  
  if(!isset($_GET['redirect']))
  {
    $url = '/?action=cart.view';
  }
  else
  {
    $url = $_GET['redirect'];
  }
?>