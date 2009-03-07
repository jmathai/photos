<?php
  $us =& CUser::getInstance();
  
  $pAccount = $us->my($_POST['parentAccount']);
  
  if($pAccount !== false)
  {
    $userAccount = $us->subAccount($pAccount['U_ID'], $_POST['subUsername'], $_POST['subPassword']);
    
    if($userAccount !== false)
    {
      $user_id  = $pAccount['U_ID']; // parent user id
      $email    = $userAccount['SA_EMAIL']; // user email
      $username = $pAccount['U_USERNAME'] . '/' . $userAccount['SA_USERNAME']; // combination of parent / user
      $account_perm = $arUser['U_ACCOUNTTYPE']; // parent accountType
      $is_trial = ''; // no longer used
      
      include_once PATH_DOCROOT . '/login_manual.act.php';
      
      $_FF_SESSION->register('permissions', '1'); // set permissions
      $_FF_SESSION->register('sub_account_id', $userAccount['SA_ID']); // set sub account id
      
      $url = '/';
    }
    else
    {
      // wrong username/password
    }
  }
  else
  {
    // parent account DNE
  }
  
  if(!isset($url))
  {
    die();
  }
?>