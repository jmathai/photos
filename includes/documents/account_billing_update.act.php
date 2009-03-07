<?php
  // USER DOES NOT HAVE TO BE LOGGED IN
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
  $usm  =& CUserManage::getInstance();
  
  $_p_safe = array();
  foreach($_POST as $k => $v)
  {
    if(strstr($k, 'er_'))
    {
      $_p_safe[$k] = $v;
    }
  }
  
  $data = $_p_safe;
  $data['er_u_id'] = $_USER_ID;
  $data['er_ccExpYear'] = substr('20' . $data['er_ccExpYear'], -4);
  
  /* If transaction is captured (in xml_result) then update user expiration and next recurring date */
  if(isset($_GET['captured']))
  {
    $expireTime = mktime(0, 0, 0, date('n', NOW), date('j', NOW), date('Y', NOW));
    $recurDetail = $ecom->getRecurringPayment($data['er_id']);
    $expiry = $recurDetail['R_PERIOD'] == 'Monthly' ? ($expireTime + 2592000) : ($expireTime + 31536000);
    $expiry = date('Y-m-d', $expiry);
    $data['er_initialDate'] = $expiry;
    $data['er_status'] = 'Active';
    $data['er_u_id'] = $recurDetail['R_U_ID'];
    $userData = array('u_id' => $recurDetail['R_U_ID'], 'u_dateExpires' => $expiry);
    $usm->update($userData);
  }
  
  $ecom->updateRecurringPayment($data);
  
  $url = '/?action=account.billing_confirm&payment_id=' . $data['er_id'];
?>