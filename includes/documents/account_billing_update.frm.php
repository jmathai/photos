<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  $payment_id = $_GET['payment_id'];
  
  $tok  =& CToken::getInstance();
  
  $redirectToSignup = false;
  
  if($logged_in === true) // logged in user
  {
    $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
    $recurDetail = $ecom->getRecurringAccount($_USER_ID);
    $uId = $_USER_ID;
  }
  else
  if($_FF_SESSION->value('temp_user_id') > 0) // expired use with user id stored in session
  {
    $ecom =& new CEcom($_FF_SESSION->value('temp_user_id'), $_SESSION_HASH);
    $recurDetail = $ecom->getRecurringAccount($_FF_SESSION->value('temp_user_id'));
    
    if(isset($recurDetail['R_ID'])) // user has a payment record but is expired
    {
      $uId = $_FF_SESSION->value('temp_user_id');
    }
    else // trial user - ask them to purchase
    {
      $redirectToSignup = true;
    }
  }
  
  /*
  THIS MAKES NO SENSE...REQUIRE A LOGIN TO UPDATE PAYMENT INFORMATION
  else
  if($_GET['hash'] == md5('ff_'.$payment_id))
  {
    $recurDetail = $ecom->getRecurringPayment($payment_id);
  }
  */

  if(isset($recurDetail['R_ID']))
  {
    $orderDetail = $ecom->getCatalogItem($recurDetail['R_ECG_ID']);
    $tokenString = $tok->setToken();
    
    if(isset($_GET['type']))
    {
      // if a type is passed in then set that to be the type
      $type = $_GET['type'];
    }
    else
    if($logged_in === false)
    {
      // capture if the user is not logged in (and has a recurring payment)
      // the user must have been redirected from the login.act.php page
      $type = 'capture';
      echo '
          <div class="bold center" style="padding-bottom:15px;">
            <img src="/images/icons/warning_alt_2_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;Your account has expired.  Please update your billing information to re-activate your account.
          </div>
      ';
    }
    else
    {
      // if the user is logged in and has a recurring payment then do not capture
      $type = 'nocapture';
    }
    
    $fv =  new CFormValidator;
    $fv -> setForm('updateBilling');
    $fv -> addElement('er_ccNameFirst', 'First Name', '  - Please enter your first name.', 'length');
    $fv -> addElement('er_ccNameLast', 'Last Name', '  - Please enter your last name.', 'length');
    $fv -> addElement('er_ccStreet', 'Address', '  - Please enter your address.', 'length');
    $fv -> addElement('er_ccCity', 'City', '  - Please enter your city.', 'length');
    $fv -> addElement('er_ccState', 'State', '  - Please select your state.', 'selectboxnull');
    $fv -> addElement('er_ccZip', 'Zip', '  - Please enter your zipcode.', 'length');
    $fv -> addElement('er_ccNum', 'Credit Card Number', '  - Please check make sure you typed your credit card number in correctly.', 'mod10');
    $fv -> addElement('er_ccExpMonth', 'Expiration Month', '  - Please enter the month your credit card expires.', 'length');
    $fv -> addElement('er_ccExpYear', 'Expiration Year', '  - Please enter the year your credit card expires.', 'length');
    $fv -> addElement('er_ccCcv', 'Credit Card Verification Number', '  - Please enter the 3 digit CCV number on the back of your card.', 'length');
    $fv -> setMaxElementsToDisplay(5);
    $fv -> setDebugOutput(false);
    $fv -> setFunctionName('_val_process');
    $fv -> setJavascriptSubmit(false);
    $fv -> validate();
?>
    <script>
      requireSSL(); // force ssl
    </script>
    <div>
      <div class="bold">Update your payment information</div>
      <div style="padding-bottom:10px;">
        You are updating the following subscription:<br />
        Item: <?php echo $orderDetail['ecg_name']; ?><br />
        Amount: $<?php echo $recurDetail['R_AMOUNT']; ?><br />
        Billing Cycle: <?php echo $recurDetail['R_PERIOD']; ?>
      </div>
      <br/>
      <div class="bold">
        <img src="images/icons/credit_card_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" hspace="5" />Enter your new billing information below
      </div>
      <form name="updateBilling" method="post" action="/?action=account.billing_update_form.act">
      <div class="formBlock">
        <div class="formRow">
          <div class="formLabel">First Name:</div>
          <div class="formField"><input type="text" name="er_ccNameFirst" id="er_ccNameFirst" value="<?php echo $recurDetail['R_CC_NAMEFIRST']; ?>" size="10" class="formfield" /></div>
        </div>
        <div class="formRow">
          <div class="formLabel">Last Name:</div>
          <div class="formField"><input type="text" name="er_ccNameLast" id="er_ccNameLast" value="<?php echo $recurDetail['R_CC_NAMELAST']; ?>" size="10" class="formfield" /></div>
        </div>
        <div class="formRow">
          <div class="formLabel">Address:</div>
          <div class="formField"><input type="text" name="er_ccStreet" id="er_ccStreet" value="<?php echo $recurDetail['R_CC_STREET']; ?>" size="20" class="formfield" /></div>
        </div>
        <div class="formRow">
          <div class="formLabel">City:</div>
          <div class="formField"><input type="text" name="er_ccCity" id="er_ccCity" value="<?php echo $recurDetail['R_CC_CITY']; ?>" size="10" class="formfield" /></div>
        </div>
        <div class="formRow">
          <div class="formLabel">State:</div>
          <div class="formField"><select name="er_ccState" class="formfield">
              <option value="null">-- Select State --</option>
              <?php 
                echo optionStates($recurDetail['R_CC_STATE']);
              ?>
            </select></div>
        </div>
        <div class="formRow">
          <div class="formLabel">Zip:</div>
          <div class="formField"><input type="text" name="er_ccZip" id="er_ccZip" value="<?php echo $recurDetail['R_CC_ZIP']; ?>" size="6" class="formfield" /></div>
        </div>
        <div class="formRow">
          <div class="formLabel">Card Number:</div>
          <div class="formField"><input type="text" name="er_ccNum" id="er_ccNum" size="24" class="formfield" /></div>
        </div>
        <div class="formRow">
          <div class="formLabel">Card Expiration:</div>
          <div class="formField"><input type="text" name="er_ccExpMonth" id="er_ccExpMonth" size="2" class="formfield" /> / <input type="text" name="er_ccExpYear" size="4" class="formfield" />&nbsp;(mm/yyyy)</div>
        </div>
        <div class="formRow">
          <div class="formLabel">Card CVV:</div>
          <div class="formField"><input type="text" name="er_ccCcv" id="er_ccCcv" size="4" class="formfield" />&nbsp;(<a href="javascript:_open('/popup/cvv/', 220, 225);">What's this?</a>)</div>
        </div>
      </div>
      <input type="hidden" name="er_id" value="<?php echo $recurDetail['R_ID']; ?>" />
      <input type="hidden" name="er_status" value="Active" />
      <?php
        if($type == 'capture')
        {
          echo '
                <div class="formRow">
                  <div class="formLabel">&nbsp;</div>
                  <div class="formField">
                    <a href="javascript:if(_val_process()){ frm = document.forms[\'updateBilling\']; paymentVerify(frm.er_ccNum.value, frm.er_ccExpMonth.value, frm.er_ccExpYear.value, frm.er_ccCcv.value, frm.er_ccNameFirst.value, frm.er_ccNameLast.value, frm.er_ccStreet.value, frm.er_ccCity.value, frm.er_ccState.value, frm.er_ccZip.value, \'' . $recurDetail['R_AMOUNT'] . '\', \'' . $recurDetail['R_ID'] . '\', \'' . $uId . '\', \'' . $type . '\', \'' . $tokenString . '\'); }"><img src="images/buttons/update.gif" width="87" height="27" border="0" id="updateButton" /></a>
                  </div>
                </div>                
              ';
        }
        else
        {
          echo '
                <div class="formRow">
                  <div class="formLabel">&nbsp;</div>
                  <div class="formField">
                    <a href="javascript:if(_val_process()){ document.forms[\'updateBilling\'].submit(); }"><img src="images/buttons/update.gif" width="87" height="27" border="0" id="updateButton" /></a>
                  </div>
                </div>
                ';
        }
        
        echo '<input type="hidden" name="ecom_amount" value="' . $recurDetail['R_AMOUNT'] . '" />';
      ?>
      
      <div class="formRow">
        <div class="formLabel">&nbsp;</div>
        <div class="formField">
          <div><img src="images/creditcards_accepted.gif" width="134" height="19" border="0" vspace="10" /></div>
        </div>
      </div>
      
      <div class="formRow">
        <div class="formLabel">&nbsp;</div>
        <div class="formField">
          <div style="padding:10px;">
            <!-- GeoTrust QuickSSL [tm] Smart Icon tag. Do not edit. -->
            <script src="//smarticon.geotrust.com/si.js"></script>
            <!-- end GeoTrust Smart Icon tag -->
            <!-- authorize.net seal -->
            <script type="text/javascript" language="javascript">
              var ANS_customer_id="de6ff85c-1da2-49ff-9d52-d75960456998";
            </script>
            &nbsp;&nbsp;
            <script type="text/javascript" language="javascript" src="//VERIFY.AUTHORIZE.NET/anetseal/seal.js" ></script>
            <!-- end authorize.net seal -->
          </div>
        </div>
      </div>
      
      <?php
        if($logged_in === true)
        {
          echo '
                <div class="formRow">
                  <div class="formLabel">&nbsp;</div>
                  <div class="formField">
                    <div><a href="/?action=account.cancel_form" class="plain bold"><img src="images/icons/stop_alt_2_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;Looking to cancel your account?</a></div>
                  </div>
                </div>    
                ';
        }
      ?>
      
      </form>
      <div id="blank"></div>
    </div>
<?php
  }
  else
  {
    if($redirectToSignup === true)
    {
      echo '<div class="bold f_10 center"><img src="images/icons/smiley_24x24.png" class="png" width="24" height="24" align="absmiddle" />&nbsp;We hope you have enjoyed using Photagious during your free trial.</div>
            <br/>
            <div>
              If you would like to purchase an account you can complete your registration by clicking <a href="https://' . FF_SERVER_NAME . '/?action=home.registration_form_b2">here</a>.
              <br/><br/>
              By signing up you can continue to use Photagious to share your photos and slideshow with your friends and family.  If you have any questions feel free to contact us by sending an email to <script> writeEmail("support"); </script>.
            </div>';
    }
    else
    {
      echo '<div class="bold"><img src="images/icons/warning_alt_2_16x16.png" class="png" width="16" height="16" align="absmiddle" />&nbsp;Sorry, we could not find your payment record.</div>
            <br/>
            <div>Please make sure that you are <a href="/?action=home.login_form&redirect=' . urlencode('/?action=account.billing_update_form') . '">logged in</a>.  If you have questions contact us at <script> writeEmail("support"); </script>.</div>';
    }
  }
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>