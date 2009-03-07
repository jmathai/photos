<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $mail =& CMail::getInstance();
  $user =& CUser::getInstance();
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);

  $user_data  = $user->find($_USER_ID);
  $order_id   = $ecom->getCartID();
  
  $redirect_success = isset($_POST['redirect_success']) ? $_POST['redirect_success'] : '/?action=cart.checkout.confirmation';
  $redirect_failure = isset($_POST['redirect_failure']) ? $_POST['redirect_failure'] : '/?action=cart.view?e=declined';
  
  if (!empty($_POST['i_ids']))
  {
    $ids 				= !empty($_POST['i_ids']) ? $_POST['i_ids'] : array();
    $quantities = !empty($_POST['i_quantities']) ? $_POST['i_quantities'] : array();
    $prices			= !empty($_POST['i_prices']) ? $_POST['i_prices'] : array();
    $details		= !empty($_POST['i_details']) ? $_POST['i_details'] : array();
  
    $ecom->addCartItems($ids, $quantities, $prices, $details);
  }
  
  $cart_data = $ecom->getCartItems();
  
  if(isset($_POST['s_nameFull']))
  {
    $rpos = strrpos($_POST['s_nameFull'], ' ');
    $ship_f_name  = substr($_POST['s_nameFull'], $rpos);
    $ship_l_name  = substr($_POST['s_nameFull'], 0, $rpos);
    $ship_address = $_POST['s_address'];
    $ship_city    = $_POST['s_city'];
    $ship_state   = $_POST['s_state'];
    $ship_zip     = $_POST['s_zip'];
    $shipping     = $_POST['s_shipping'];
    $do_shipping  = true;
  }
  else
  {
    $ship_f_name  = '';
    $ship_l_name  = '';
    $ship_address = '';
    $ship_city    = '';
    $ship_state   = '';
    $ship_zip     = '';
    $shipping     = '';
    $shipping     = 0;
    $do_shipping  = false;
  }
  
  $cart_total = $ecom->getTotalPrice() + $shipping;
  
  $payment_info = array(
    'order_num' => $order_id,
    'invoice_num' => $order_id,
    'amount'    => $cart_total,
    'tax'       => '0',
    'shipping'  => $shipping,
    
    'cc_num'    => $_POST['cc_number'],
    'cc_exp'    => $_POST['cc_exp_month'] . $_POST['cc_exp_year'],
    'cc_code'   => $_POST['cc_cvv'],
    'cc_type'   => $_POST['cc_type'],
    'recurring' => 'NO',
    
    'first_name'=> $_POST['cc_nameFirst'],
    'last_name' => $_POST['cc_nameLast'],
    'company'   => '',
    'address'   => $_POST['b_address'],
    'city'      => $_POST['b_city'],
    'state'     => $_POST['b_state'],
    'zip'       => $_POST['b_zip'],
    'country'   => 'US',
    'phone'     => '',
    'fax'       => '',
    'email'     => $user_data['U_EMAIL'],
    
    'ship_first_name' => $ship_f_name,
    'ship_last_name'  => $ship_l_name,
    'ship_company'    => '',
    'ship_address'    => $ship_address,
    'ship_city'       => $ship_city,
    'ship_state'      => $ship_state,
    'ship_zip'        => $ship_zip,
    'ship_country'    => 'US',
    
    'customer_id'     => $_USER_ID,
    'customer_ip'     => $_SERVER['REMOTE_ADDR'],
    'description'     => ''
  );
  
  $status = $ecom->checkout($payment_info);
  if ($status == ECOM_APPROVED)
  {
    if($_USER_ID == 0)
    {
      $ecom->migrate($order_id, $_COOKIE['FF_TMP_USER_ID']);
    }
    
    /* DO REGISTRATION */
    include_once PATH_DOCROOT . '/registration_d.act.php';
    /* DO RECURRING BILLING */
    if(isset($_POST['recur_payment']))
    {
      $payment_recur = $payment_info;
      $payment_recur['period'] = $period; // set in registration_d.act.php
      switch($period)
      {
        case 'Monthly':
          $initTime = mktime(0, 0, 0, date('m', NOW)+1, min(27, date('d', NOW)), date('Y', NOW));
          break;
        case 'Yearly':
          $initTime = mktime(0, 0, 0, date('m', NOW), min(27, date('d', NOW)), date('Y', NOW) + 1);
          break;
      }
      $payment_recur['initial_date'] = date('Y-m-d', $initTime);
      $payment_recur['catalog_id']   = $_POST['recur_payment'];
      $ecom->addRecurringPayment($payment_recur);
    }
    
    
    $ecom->finalize();
    $ecom->cc($payment_info);
    if($do_shipping === true)
    {
      $shipping_data  = array(
                          'eos_eo_id' => $order_id,
                          'eos_name'  => $ship_f_name . ' ' . $ship_l_name,
                          'eos_address' => $ship_address,
                          'eos_city'  => $ship_city,
                          'eos_state' => $ship_state,
                          'eos_zip'   => $ship_zip
                        );
      $ecom->addShipping($shipping_data);
    }
    
    // PUT EMAIL CONFIRMATION HERE
    if(!isset($no_confirmation_email))
    {
      $to   = $user_data['U_EMAIL'];
      $from = FF_EMAIL_FROM_FORMATTED;
      $subject    = 'Your FotoFlix.com Order: #' . $order_id;
      $headers    = "MIME-Version: 1.0\n"
                  . "Content-type: text/plain; charset=iso-8859-1\n"
                  . "Return-Path: {$from}\n"
                  . "From: {$from}\n";
      
      if(isset($_POST['s_address']))
      {
        $message  = $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'] . ','
                  . 'Thank you for using FotoFlix.com.  We have successfully received your order and are currently proccessing it.  We will send you an email with tracking information once the item has been shipped.' . "\n\n"
                  . 'If you have any questions please contact us at support@fotoflix.com or visit the Help section on our website.' . "\n\n"
                  . 'Your order details are below:' . "\n\n";
      }
      else
      {
        $message  = $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'] . ','
                  . 'Thank you for using FotoFlix.com.  Your account should be immediately credited.' . "\n\n"
                  . 'If you have any questions please contact us at support@fotoflix.com or visit the Help section on our website.' . "\n\n"
                  . 'Your order details are below:' . "\n\n";
      }
      
      $total_price = 0;
      foreach($cart_data as $v)
      {
        $message  .=$v['name'] . "\n"
                  . 'Quantity            ' . $v['quantity'] . "\n"
                  . 'Price                $' . number_format($v['totalprice'], 2) . "\n"
                  . '-----------------------------' . "\n";
        $total_price += $v['totalprice'];
      }
      
      if(isset($_POST['s_shipping']))
      {
        $message  .='Shipping             $' . $_POST['s_shipping'] . "\n"
                  . '=============================' . "\n" 
                  . 'Total Price         $' . number_format(($total_price + $_POST['s_shipping']), 2) . "\n\n"
                  . 'Thanks,' . "\n" . 'The FotoFlix Team';
      }
      else
      {
        $message  . '=============================' . "\n" 
                  . 'Total Price         $' . number_format($total_price, 2) . "\n\n"
                  . 'Thanks,' . "\n" . 'The FotoFlix Team';
      }
      
    }
    
    $mail->send($to, $subject, $message, $headers, "-f{$from}");
    
    $url = 'https://' . FF_SERVER_NAME . '/?action=account.order_details&order_id=' . $order_id . '&message=confirm';
    
    if(isset($_POST['forward_to_group']))
    {
      $url .= '&forward_to_group=' . $_POST['forward_to_group'];
    }
    $code_execute = '<script language="javascript">
                        top.location.href = "' . $url . '";
                     </script>';
  }
  else
  if($status == ECOM_DECLINED)
  {
    $url = $redirect_failure;
    $code_execute = '<script language="javascript">
                        top.document.getElementById("_message").innerHTML  = "<span class=\"f_9 f_red bold\">We were unable to complete the transaction.  Please try a different credit card or make sure the following information is correct:</span>"
                                                                        + "<ol>"
                                                                        + "<li>Billing Address</li>"
                                                                        + "<li>Name On Card</li>"
                                                                        + "<li>Credit Card Number</li>"
                                                                        + "<li>CVV #</li>"
                                                                        + "<li>Expiration Date</li>"
                                                                        + "<li>Ensure your cart isn\'t empty by refreshing this page</li>"
                                                                        + "</ol>"
                                                                        + "If you continue to have problems please <a href=\"/contactus/\">contact us</a>.";
                        top.document.getElementById("_message").style.display = "block";
                        top._swapToWait(false);
                        top.location.href = top.location.href + "#top";
                     </script>';
  }
  else
  if($status == ECOM_ERROR)
  {
    $url = $redirect_failure;
    $code_execute = '<script language="javascript">
                        top.document.getElementById("_message").innerHTML  = "<span class=\"f_9 f_red bold\">There was a problem with the credit card information you entered.  Make sure the following information is correct:</span>"
                                                                        + "<ol>"
                                                                        + "<li>Billing Address</li>"
                                                                        + "<li>Name On Card</li>"
                                                                        + "<li>Credit Card Number</li>"
                                                                        + "<li>CVV #</li>"
                                                                        + "<li>Expiration Date</li>"
                                                                        + "<li>Ensure your cart isn\'t empty by refreshing this page</li>"
                                                                        + "</ol>"
                                                                        + "If you continue to have problems please <a href=\"/contactus/\">contact us</a>.";
                        top.document.getElementById("_message").style.display = "block";
                        top._swapToWait(false);
                        _location = top.location.href;
                        top.location.href = _location.replace(/#top/g, "") + "#top";
                     </script>';
  }
?>