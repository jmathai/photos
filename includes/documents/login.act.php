<?php
  $u =& CUser::getInstance();
  $g =& CGroup::getInstance();
  $gm=& CGroupManage::getInstance();

  $arUser = $u->find($_POST['u_username'], $_POST['u_password']);
  
  if($arUser !== false)
  {
    if($arUser['U_STATUS'] == 'Active')
    {
      $persistent_login = isset($_POST['persistent_login']) ? true : false;
      $user_id          = $arUser['U_ID'];
      $username         = $arUser['U_USERNAME'];
      $account_perm     = $arUser['U_ACCOUNTTYPE'];
      $email            = $arUser['U_EMAIL'];
      $is_trial         = $arUser['U_ISTRIAL'];
      
      include_once PATH_DOCROOT . '/login_manual.act.php';

      $forward_to_group = false;
      if(isset($_POST['invite_key']))
      {
        $invite_data = $g->inviteData($_POST['invite_key']);
        if(strncasecmp($_FF_SESSION->value('email'), $invite_data['I_EMAIL'], strlen($invite_data['I_EMAIL'])) == 0)
        {
          $gm->join($_USER_ID, $invite_data['I_G_ID'], $invite_data['I_REFERENCE']);
          $forward_to_group = $invite_data['I_G_ID'];
        }
      }

      if(isset($_POST['redirect']))
      {
        if($arUser['U_ACCOUNTTYPE'] == FF_ACCT_TRIAL)
        {
          $url = '/?action=home.purchase_offer&expiry=' . $arUser['U_DATEEXPIRES'] . '&redirect=' . urlencode($_POST['redirect']);
        }
        else
        {
          if(isset($_POST['qoop']))
          {
            $_POST['redirect'] .= '&user_token=' . $_FF_SESSION->value('sess_hash');// $arUser['U_KEY'];
          }
          $url = $_POST['redirect'];
        }
      }
      else
      if($arUser['U_ACCOUNTTYPE'] == FF_ACCT_TRIAL)
      {
        $forward_url = $forward_to_group === false ? '/?action=fotobox.fotobox_main' : '/?action=fotogroup.group_home&group_id=' . $forward_to_group;
        $url = '/?action=home.purchase_offer&expiry=' . $arUser['U_DATEEXPIRES'] . '&redirect=' . urlencode($forward_url);
      }
      else
      {
        $url = '/?action=fotobox.fotobox_main';
      }
    }
    else
    {
      $url = '/?action=home.login_form&message=login_failed';
    }
  }
  else
  {
    $userData = $u->inactive($_POST['u_username'], $_POST['u_password']);
    if($userData !== false) // expired
    {
      $_FF_SESSION->register('temp_user_id', $userData['U_ID']);
      if(strstr($_POST['redirect'], 'home.registration_form_b2'))
      {
        $url = 'https://' . FF_SERVER_NAME . $_POST['redirect'];
        $doNotAppend = 1;
      }
      else
      {
        $url = 'https://' . FF_SERVER_NAME . '/?action=account.billing_update_form';
      }
    }
    else 
    {
      $url = '/?action=home.login_form&message=login_failed';
    }
    
    if(isset($_POST['redirect']) && empty($doNotAppend))
    {
      $url .= '&redirect=' . urlencode($_POST['redirect']);
    }
  }
?>
