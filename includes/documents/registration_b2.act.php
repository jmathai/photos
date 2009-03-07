<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $u      =& CUser::getInstance();
  $mail   =& CMail::getInstance();
  $ecom   =& new CEcom($_USER_ID, $_SESSION_HASH);
  $um     =& CUserManage::getInstance();
  $idat   =& CIdat::getInstance();
  
  $error  = false;
  $continue = true;
  
  if($_FF_SESSION->value('temp_user_id') > 0) // trial user who is expired
  {
    $tempUserData = $u->inactive($_FF_SESSION->value('temp_user_id'));
  }
  else // trial user who is not expired yet
  {
    $tempUserData = $u->find($_USER_ID);
  }
  
  if($tempUserData === false)
  {
  	$continue = false;
  }
  
  $redirect = $_GET['redirect'];
  
  $account = $ecom->getCatalogItem($_POST['ecom_catalog_id']);
  
  if($account !== false) // attempt to purchase an account
  {
    $price = $account['ecg_priceSpecialStart'] < NOW && NOW < $account['ecg_priceSpecialEnd'] ? $account['ecg_priceSpecial'] : $account['ecg_price'];
    $ecom->addCartItems($account['ecg_id'], 1, $price, $account['desc']);
    
    $order_id = $ecom->getCartID();
    
    $ecom->addCartItems(array($account['ecg_id']), array('1'), array($price), array($account['ecg_description']));
    
    $payment_info = array(
      'order_num' => $order_id,
      'invoice_num' => $order_id,
      'amount'    => $price,
      'tax'       => '0',
      'shipping'  => '0',
      
      'cc_num'    => $_POST['ecom_cc_num'],
      'cc_exp'    => substr('0' . $_POST['ecom_cc_month'] . $_POST['ecom_cc_year'], -6),
      'cc_code'   => $_POST['ecom_cc_cvv'],
      'cc_type'   => '',
      'recurring' => 'NO',
      
      'first_name'=> $_POST['u_nameFirst'],
      'last_name' => $_POST['u_nameLast'],
      'company'   => $_POST['u_businessName'],
      'address'   => $_POST['u_address'],
      'city'      => $_POST['u_city'],
      'state'     => $_POST['u_state'],
      'zip'       => $_POST['u_zip'],
      'country'   => 'US',
      'phone'     => '',
      'fax'       => '',
      'email'     => $tempUserData['U_EMAIL'],
      
      'customer_id'     => $tempUserData['U_ID'],
      'customer_ip'     => '',
      'description'     => ''
    );
    
    $response = $ecom->checkout($payment_info);
    if ($response != ECOM_APPROVED)
    {
      $continue = false;
    }
  }
  
  if($continue === true)
  {
    $_p_safe = array();
    foreach($_POST as $k => $v)
    {
      if(strncmp('u_', $k, 2) == 0)
      {
        $_p_safe[$k] = $v;
      }
    }
    
    $account_type_array = explode('-', $account['ecg_additional']);
    
    $_p_safe['u_spaceTotal']  = FF_DEFAULT_SPACE;
    if(intval($_POST['ecom_catalog_id']) == 1 || intval($_POST['ecom_catalog_id']) == 2)
    {
      $_p_safe['u_accountType'] = PERM_USER_0;
    }
    else 
    {
      $_p_safe['u_accountType'] = PERM_USER_1;
    }
    
    if(intval($_POST['ecom_catalog_id']) == 1 || intval($_POST['ecom_catalog_id']) == 3)
    {
      $period = 'Monthly';
      $_p_safe['u_dateExpires'] = $initial_date = date('Y-m-d', strtotime('+1 Month', NOW));
    }
    else 
    {
      $period = 'Yearly';
      switch($_POST['couponCode'])
      {
        case 'freeyear':
          $_p_safe['u_dateExpires'] = $initial_date = date('Y-m-d', strtotime('+2 Years', NOW));
          break;
        default:
          $_p_safe['u_dateExpires'] = $initial_date = date('Y-m-d', strtotime('+1 Year', NOW));
          break;
      }
    }
    
    $_p_safe['u_isTrial'] = 0;
    $_p_safe['u_status']  = 'Active'; //isset($_POST['email_validation_bypass']) ? 'Active' : 'Pending';
    
    $_p_safe['u_id'] = $user_id = $tempUserData['U_ID'];
    
    $um->update( $_p_safe );
    
    $ecom->migrate($ecom->getCartId(), $user_id);
    $order_data = array('eo_cc_fname' => $_POST['u_nameFirst'], 'eo_cc_lname' => $_POST['u_nameLast'], 'eo_cc_company' => $_POST['u_businessName'],'eo_cc_street' => $_POST['u_address'], 'eo_cc_city' => $_POST['u_city'], 
                        'eo_cc_state' => $_POST['u_state'], 'eo_cc_zip' => $_POST['u_zip'], 'eo_cc_num' => $_POST['ecom_cc_num'], 'eo_cc_month' => $_POST['ecom_cc_month'], 'eo_cc_year' => $_POST['ecom_cc_year'], 
                        'eo_cc_ccv' => $_POST['ecom_cc_cvv']);
    $order_id = $ecom->finalize($order_data);
    $data = array('order_id'  => $order_id,
                  'catalog_id'=> intval($_POST['ecom_catalog_id']),
                  'user_id'   => $user_id,
                  'first_name'=> $_POST['u_nameFirst'],
                  'last_name' => $_POST['u_nameLast'],
                  'company'   => $_POST['u_businessName'],
                  'address'   => $_POST['u_address'],
                  'city'      => $_POST['u_city'],
                  'state'     => $_POST['u_state'],
                  'zip'       => $_POST['u_zip'],
                  'cc_num'    => $_POST['ecom_cc_num'],
                  'cc_month'  => $_POST['ecom_cc_month'],
                  'cc_year'   => $_POST['ecom_cc_year'],
                  'cc_cvv'    => $_POST['ecom_cc_cvv'],
                  'initial_date'  => $initial_date,
                  'period'    => $period,
                  'amount'    => $price);
    $recurringId = $ecom->addRecurringPayment($data);
    $ecom->addRecurringResult($recurringId, 'Success');

  	
    $email = $tempUserData['U_EMAIL'];
    $username = $tempUserData['U_USERNAME'];
    $account_perm = $_p_safe['u_accountType'];
    $is_trial = USER_IS_NOT_TRIAL;
    include_once PATH_DOCROOT . '/login_manual.act.php';
    
    $to = $tempUserData['U_EMAIL'];
    $from = FF_EMAIL_FROM_FORMATTED;
    $subject = 'Welcome to Photagious';
    $message = str_replace(array('{USERNAME}','{BASE_URL}'), array($tempUserData['U_USERNAME'],'http://'.FF_SERVER_NAME), file_get_contents(PATH_DOCROOT . '/registration.tpl.php'));
    $headers = "MIME-Version: 1.0\n"
             . "Content-type: text/html; charset=iso-8859-1\n"
             . 'Return-Path: ' . $from . "\n"
             . 'From: ' . $from;
                      
    $mail->send($to, $subject, $message, $headers, $from);
    
    // ___ is the tracking code
    // ____ is the amount
    $url = '/?newAccount=2&___=' . urlencode(base64_encode($user_id . '-' . $order_id)) . '&____=' . urlencode(base64_encode($price));
               
    /*
    if(!empty($_POST['promotion'])) 
    { 
      $promotion = $GLOBALS['dbh']->sql_safe($_POST['promotion']); 
      
      $to = FF_EMAIL_FROM_FORMATTED;
      $from = FF_EMAIL_FROM_FORMATTED;
      $subject = 'Promotional Code Entered';
    
      switch($_POST['promotion']) 
      { 
        // if there's a second promotion we'll put this in a class 
      }
    }
    */
    
    if($error === true)
    {
      $parts  = parse_url($_SERVER['HTTP_REFERER']);
      $url = $parts['path'] . '?action=home.registration_form_b&message=account_info';
    }
  }
  else
  {
    $parts  = parse_url($_SERVER['HTTP_REFERER']);
    $url = $parts['path'] . '?action=home.registration_form_b&message=account_info';
  }
?>