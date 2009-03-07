<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  $payment_id = $_GET['payment_id'];
  
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
  $recurDetail = $ecom->getRecurringPayment($payment_id, $_USER_ID);
  $orderDetail = $ecom->getCatalogItem($recurDetail['R_ECG_ID']);
  
  $td = mcrypt_module_open('tripledes', '', 'ecb', '');
  $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
  mcrypt_generic_init($td, ECOM_CC_ENCRYPT_KEY, $iv);
  $ccNum = substr(preg_replace('/\D/', '', mdecrypt_generic($td, base64_decode($recurDetail['R_CC_NUM']))), -4);
  mcrypt_generic_deinit($td);
  mcrypt_module_close($td);
?>

<div style="width:545px;" align="left">
  <div class="bold">Your billing information was updated.</div>
  
  <div style="padding-top:10px;">
    <div class="bold">Subscription:</div>
    Item: <?php echo $orderDetail['ecg_name']; ?><br />
    Amount: $<?php echo $recurDetail['R_AMOUNT']; ?><br />
    Billing Cycle: <?php echo $recurDetail['R_PERIOD']; ?>
  </div>
  
  <div style="padding-top:10px;">
    <div class="bold">Billing information:</div>
    Name: <?php echo $recurDetail['R_CC_NAMEFIRST'] . ' ' . $recurDetail['R_CC_NAMELAST']; ?><br />
    Street: <?php echo $recurDetail['R_CC_STREET']; ?><br />
    City: <?php echo $recurDetail['R_CC_CITY']; ?><br />
    State: <?php echo $recurDetail['R_CC_STATE']; ?><br />
    Zip: <?php echo $recurDetail['R_CC_ZIP']; ?><br />
    Credit Card Number: ************<?php echo $ccNum; ?>
  </div>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>