<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  ob_start();
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CEcom.php';
  include_once PATH_CLASS . '/CUser.php';
  include_once PATH_CLASS . '/CMail.php';
  
  $us =& CUser::getInstance();
  $m  =& CMail::getInstance();
  
  $emailFailed = array();
  $today = date('Y-m-d', NOW);
  
  $sqlM = "SELECT * FROM ecom_recur WHERE er_period = 'Monthly' AND DAYOFMONTH(er_initialDate) = '" . date('d', NOW) . "' AND er_initialDate <= '{$today}' AND er_status = 'Active'";
  $sqlY = "SELECT * FROM ecom_recur WHERE er_period = 'Yearly' AND MONTH(er_initialDate) = '" . date('m', NOW) . "' AND DAYOFMONTH(er_initialDate) = '" . date('d', NOW) . "' AND er_initialDate <= '{$today}' AND er_status = 'Active'";
  
  $dataM = $GLOBALS['dbh']->query_all($sqlM);
  $dataY = $GLOBALS['dbh']->query_all($sqlY);
  
  $dataMerge = array_merge($dataM, $dataY);
  
  $month = date('m', NOW);
  $year  = date('Y', NOW);
  
  foreach($dataMerge as $v)
  {
    $user_data = $us->find($v['er_u_id']);
    $v['er_ccExpYear'] = substr('20' . $v['er_ccExpYear'], -4);

    $ecom =& new CEcom($v['er_u_id'], '');
    $ecom->createCart();
    $item_detail = $ecom->getCatalogItem($v['er_ecg_id']);
    $detail = $item_detail['ecg_name'];
    $order_id = $ecom->getCartID();
    
    $ecom->addCartItems(array($v['er_ecg_id']), array('1'), array($v['er_amount']), array($detail));
    
    $decrypted_cc = decrypt($v['er_ccNum']);
    
    $payment_info = array(
      'order_num' => $order_id,
      'invoice_num' => $order_id,
      'amount'    => $v['er_amount'],
      'tax'       => '0',
      'shipping'  => '0',
      
      'cc_num'    => $decrypted_cc,
      'cc_exp'    => substr('0' . $v['er_ccExpMonth'] . $v['er_ccExpYear'], -6),
      'cc_code'   => $v['er_ccCcv'],
      'cc_type'   => '',
      'recurring' => 'NO',
      
      'first_name'=> $v['er_ccNameFirst'],
      'last_name' => $v['er_ccNameLast'],
      'company'   => $v['er_ccCompany'],
      'address'   => $v['er_ccStreet'],
      'city'      => $v['er_ccCity'],
      'state'     => $v['er_ccState'],
      'zip'       => $v['er_ccZip'],
      'country'   => 'US',
      'phone'     => '',
      'fax'       => '',
      'email'     => $user_data['U_EMAIL'],
      
      'customer_id'     => $v['er_u_id'],
      'customer_ip'     => '',
      'description'     => ''
    );
    
    $response = $ecom->checkout($payment_info);
    if ($response == ECOM_APPROVED)
    {
      $ecom->finalize();
      $expireTime = mktime(0, 0, 0, date('n', NOW), date('j', NOW), date('Y', NOW));
      $expiry = $v['er_period'] == 'Monthly' ? ($expireTime + 2592000) : ($expireTime + 31536000);
      $expiry = date('Y-m-d', $expiry);
      $sqlUpd = "UPDATE users SET u_dateExpires = '{$expiry}' WHERE u_id = '{$v['er_u_id']}'";
      $GLOBALS['dbh']->execute($sqlUpd);
      $status = 'Success';
      echo '+ Updated user ' . $v['er_u_id'] . ' to expire on ' . $expiry . "\n";
    }
    else
    if(($v['er_ccExpYear'] < $year) || ($v['er_ccExpYear'] == $year && $v['er_ccExpMonth'] < $month))
    {
      $status = 'Expired';
      $sqlDisable = "UPDATE ecom_recur SET er_status = 'Disabled' WHERE er_id = '{$v['er_id']}'";
      $GLOBALS['dbh']->execute($sqlDisable);
      
      $sqlDisableAcct = "UPDATE users SET u_status = 'Expired' WHERE u_id = '{$v['er_u_id']}'";
      $GLOBALS['dbh']->execute($sqlDisableAcct);
        
      echo '- Card expired for user ' . $user_data['U_USERNAME'] . '(' . $user_data['U_ID'] . ")\n";
      // send email to user that we could not renew their membership
      $emailFailed[] = array('USERID' => $user_data['U_ID'], 'EMAIL' => $user_data['U_EMAIL'], 'NAME' => $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'], 'PAYMENT_ID' => $v['er_id'], 'SERVER_NAME' => FF_SERVER_NAME, 'REASON' => 'Card has expired');
    }
    else
    {
      $status = 'Failure';
      $sqlDisableRecur = "UPDATE ecom_recur SET er_status = 'Disabled' WHERE er_id = '{$v['er_id']}'";
      $GLOBALS['dbh']->execute($sqlDisableRecur);
      
      $sqlDisableAcct = "UPDATE users SET u_status = 'Disabled' WHERE u_id = '{$v['er_u_id']}'";
      $GLOBALS['dbh']->execute($sqlDisableAcct);
      
      echo '- Failed for user ' . $user_data['U_USERNAME'] . '(' . $user_data['U_ID'] . ') code(' . $response . ")\n";
      // send email to user that we could not renew their membership
      $emailFailed[] = array('USERID' => $user_data['U_ID'], 'EMAIL' => $user_data['U_EMAIL'], 'NAME' => $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST'], 'PAYMENT_ID' => $v['er_id'], 'SERVER_NAME' => FF_SERVER_NAME, 'REASON' => 'Card was declined');
    }
    
    $ecom->addRecurringResult($v['er_id'], $status);
    unset($ecom);
  }
  
  $from     = FF_EMAIL_FROM_FORMATTED;
  $from_email = FF_EMAIL_FROM;
  $mail_headers   = "MIME-Version: 1.0\n"
                  . "Content-type: text/plain; charset=iso-8859-1\n"
                  . "Return-Path: {$from}\n"
                  . "From: {$from}\n";
  $failedTpl = file_get_contents(PATH_DOCROOT . '/account_billing_recur_error.tpl.php');
  
  $reportEmail = '';
  foreach($emailFailed as $v)
  {
    echo "* Payment update email sent to {$v['NAME']}...";
    $body = str_replace(array('{NAME}','{PAYMENT_ID}','{SERVER_NAME}', '{HASH}'),
                        array($v['NAME'],$v['PAYMENT_ID'],$v['SERVER_NAME'], md5('ff_'.$v['PAYMENT_ID'])),
                        $failedTpl);
    // email user
    $m->send(
              $v['EMAIL'],
              'Update your Photagious billing information',
              $body,
              $mail_headers,
              "-f{$from_email}"
             );
             
    // email support
    $reportEmail .= "Name:\n{$v['NAME']}\nEmail:\n{$v['EMAIL']}\nURL:\nhttps://".FF_SERVER_NAME."/cp/?action=users.single_result&u_id={$v['USERID']}\nReason:\n{$v['REASON']}\n------------------------------\n";
    echo "ok\n";
  }
  
  if(count($dataMerge) > 0)
  {
    $m->send(
              'support@fotoflix.com',
              'Recurring Billing Report for ' . date(FF_FORMAT_DATE_LONG, NOW),
              'Recurring Billing Report for ' . date(FF_FORMAT_DATE_LONG, NOW) . "\n\n" . ob_get_contents() . "\n\nFollow up with these users:\n" . $reportEmail
             );
  }
  
  echo  "\nUpdated for " . date('Y-m-d', NOW) . "\n"
      . "^^^^^^^^^^^^^^^^^^^^^^^^\n\n";
  ob_end_flush();
?>