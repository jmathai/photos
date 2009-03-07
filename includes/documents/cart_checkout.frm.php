<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  $u =& CUser::getInstance();
  $fv =  new CFormValidator;
  
  if($action == 'home.registration_form_d')
  {
    if(!is_numeric($_USER_ID) && !isset($_COOKIE[FF_SESSION_TMP_KEY]))
    {
      $redirect = '/?action=home.registration_form_b&checksum=' . md5(NOW);
      $tpl->kill(
                '<div align="center">'
              . '<div class="bold">You need to fill out your registation information before checking out.</div>'
              . '<br />Click <a href="' . $redirect . '">here</a> to finish your registration or you will be automatically redirected.'
              . '<META HTTP-EQUIV=Refresh CONTENT="5; URL=' . $redirect .'">'
              . '</div>');
      die();
    }
    $submit_btn_src = 'purchase_membership.gif';
    $submit_act_src = 'home.registration_form_d.act';
    $registration = true;
  }
  else
  {
    $submit_btn_src = 'checkout.gif';
    $submit_act_src = 'cart.checkout.act';
    $registration = false;
  }
  
  $user_info = $u->find($_USER_ID);
  if($user_info === false)
  {
    $user_info = $u->inactive($_USER_ID);
  }
  
  $ec =& new CEcom($_USER_ID, $_SESSION_HASH);
  
  // use user_info if needed
  $items = $ec->getCartItems();
  
  $required = '<span class="f_dark_accent"><sup>*</sup></span>';
?>

<script language="javascript">
  _tmp = new Image();
  _tmp.src = 'images/buttons/please_wait.gif';
  
  function _swapToWait(_action)
  {
    if(_action == true)
    {
      document.getElementById('_checkout_button').style.display   = 'none';
      document.getElementById('_processing_button').style.display = 'block';
    }
    else
    {
      document.getElementById('_checkout_button').style.display   = 'block';
      document.getElementById('_processing_button').style.display = 'none';
    }
  }
</script>
<iframe src="/blank.html" name="_auth" id="_auth" width="100%" height="25" marginheight="0" marginwidth="0" frameborder="0"></iframe>
<div class="confirm" id="_message" style="width:500px; display:none; margin: auto;"></div>
<table border="0" cellpadding="0" cellspacing="0" width="710">
  <form action="/?action=<?php echo $submit_act_src; ?>" name="_checkout" target="_auth" method="post">
    <tr height="29">
      <td colspan="3" align="left" class="f_9 bold">Review Your Order | (<a href="/?action=cart.view">Adjust your order</a>)</td>
    </tr>
    <tr height="20">
      <td align="left" width="450" background="images/pixel_dk_grey.gif" class="f_8 f_black bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item</td>
      <td align="center" width="150" background="images/pixel_dk_grey.gif" class="f_8 f_black bold">Quantity</td>
      <td align="center" width="110" background="images/pixel_dk_grey.gif" class="f_8 f_black bold">Price</td>
    </tr>
    <?php
      $total = 0;
      $shipping = false;
      $recur = false;
      foreach($items as $k => $v)
      {
        if($v['quantity'] > 0)
        {
          $bg = $k % 2 == 0 ? 'images/spacer.gif' : 'images/pixel_lt_grey.gif';
          
          if($v['ecg_type'] == 'promo')
          {
            switch($v['ecg_additional'])
            {
              case '50_MB';
                echo '<input type="hidden" name="space_promo" value="' . md5(date('y', NOW)) . '" />';
                break;
              case '100_MB':
                echo '<input type="hidden" name="space_promo_100" value="' . md5(date('y', NOW)) . '" />';
                break;
            }
          }
          else
          if(strncmp($v['ecg_type'], 'account', 7) == 0)
          {
            echo '<input type="hidden" name="additional" value="' . $v['ecg_additional'] . '" />';
          }
          
          if($v['ecg_shipping'] == 1)
          {
            $shipping = true;
          }
          
          if($v['ecg_recurring'] == 'Y')
          {
            $recur = $v['catalog_id'];
          }
          
          echo '<tr height="29">
                  <td align="left" background=' . $bg . '>
                    <span class="f_8 f_black bold">&nbsp;&nbsp;' . $v['name'] . '</span><br />
                    &nbsp;&nbsp;' . $v['description'] . '
                  </td>
                  <td align="center" background=' . $bg . ' class="f_8 f_black bold">' . $v['quantity'] . '</td>
                  <td align="center" background=' . $bg . ' class="f_8 f_black bold">$' . $v['totalprice'] . '</td>
                </tr>';
          $total += $v['totalprice'];
        }
      }
      $total = number_format($total, 2);
      echo '<tr height="1">
              <td colspan="3"><div class="line_dark"></div></td>
            </tr>
            <tr>
              <td colspan="2" align="right" class="bold">Total:&nbsp;</td>
              <td align="center" class="f_8 f_red bold">$' . $total . '</td>
            </tr>';
      
      $fv -> setForm('_checkout');
      if($shipping === true)
      {
        $fv -> addElement('s_shipping', 'Shipping Method', '  - Please select your shipping method.', 'selectboxnull');
        $fv -> addElement('s_nameFull', 'Shipping Name', '  - Please enter your shipping name.', 'length');
        $fv -> addElement('s_address', 'Shipping Street', '  - Please enter your shipping street.', 'length');
        $fv -> addElement('s_city', 'Shipping City', '  - Please enter your shipping city.', 'length');
        $fv -> addElement('s_zip', 'Shipping Zip Code', '  - Please enter your shipping zip code.', 'length');
        $fv -> addElement('s_state', 'Shipping State', '  - Please select your shipping state.', 'selectboxnull');
      }
      $fv -> addElement('cc_nameFirst', 'First Name', '  - Please enter your first name as it appears on the credit card.', 'length');
      $fv -> addElement('cc_nameLast', 'Last Name', '  - Please enter your last name as it appears on the credit card.', 'length');
      $fv -> addElement('b_address', 'Billing Street', '  - Please enter your billing street.', 'length');
      $fv -> addElement('b_city', 'Billing City', '  - Please enter your billing city.', 'length');
      $fv -> addElement('b_zip', 'Billing Zip Code', '  - Please enter your billing zip code.', 'length');
      $fv -> addElement('b_state', 'Billing State', '  - Please select your billing state.', 'selectboxnull');
      $fv -> addElement('cc_number', 'Credit Card Number', '  - Please enter your credit card number.', 'length');
      $fv -> addElement('cc_cvv', 'Credit Card CCV', '  - Please enter the CCV number on the back of your credit card.', 'length');
      $fv -> addElement('accept_terms_of_sale', 'Accept Terms of Sale', '  - Please check the "Accept Terms of Sale" checkbox.', 'checkboxmin1');
      $fv -> setMaxElementsToDisplay(5);
      $fv -> setDebugOutput(false);
      $fv -> setFunctionName('_val_checkout');
      $fv -> setEval('document.getElementById(\'_message\').innerHTML = \'<div>Please wait...</div><img src="images/loading_bar.gif" width="100" height="12" border="0" style="padding-top:3px;" />\'; document.getElementById(\'_message\').style.display = \'block\';');
      $fv -> validate();
    ?>
</table>
<br /><br />
<table cellpadding="0" cellspacing="0" border="0"  width="710">
    <tr >
      <td background="images/billing_headerBG.gif" colspan="2"><img src="images/order_review.gif" /></td>
      <?php
        if($shipping === true)
        {
          echo '<td background="images/billing_headerBG.gif" colspan="2"><img src="images/shipping.gif" /></td>';
        }
      ?>
      <td background="images/billing_headerBG.gif"><img src="images/credit_card_info.gif" /></td>
    </tr>
    <tr>
      <td valign="top">
      <!--Order Summary table-->
        <table cellpadding="3" cellspacing="0" border="0">
          <tr>
            <td colspan="2">&nbsp;&nbsp;Amount of your order:</td>
          </tr>
          <tr>
            <td align="right">Order Total:&nbsp;</td>
            <td class="f_8 f_dark bold">$<?php echo $total ;?></td>
          </tr>
          <?php
            if($shipping === true)
            {
          ?>
              <tr>
                <td align="right">Shipping:&nbsp;</td>
                <td class="f_8 f_dark bold">$<span id="_total_shipping_text">-.--</span></td>
              </tr>
              <tr>
                <td align="right">Final Total:&nbsp;</td>
                <td class="f_8 f_red bold">$<span id="_total_price_text"><?php echo $total ;?></span></td>
              </tr>
          <?php
            }
          ?>
        </table>
      </td>
      <td width="2" class="bg_lite"><img src="images/spacer.gif" width="2" height="1" /></td>
      <!--Shipping Address-->
      <?php
        if($shipping === true)
        {
          echo '<script language="javascript">
                  var _total_price = "' . $total . '";
                  
                  function _adjustShipping(_value)
                  {
                    if(!isNaN(_value))
                    {
                      _element0 = document.getElementById("_total_shipping_text");
                      _val = parseFloat(_value);
                      _element0.innerHTML = _val.toFixed(2);
                      
                      _element1 = document.getElementById("_total_price_text");
                      _val = parseFloat(_total_price) + parseFloat(_value);
                      _element1.innerHTML = _val.toFixed(2);
                    }
                    else
                    {
                      _element0 = document.getElementById("_total_shipping_text");
                      _element0.innerHTML = "-.--";
                      
                      _element1 = document.getElementById("_total_price_text");
                      _val = parseFloat(_total_price);
                      _element1.innerHTML = _val.toFixed(2);
                    }
                  }
          
                  function _sameAsShipping(_element)
                  {
                    _form = document.forms["_checkout"];
                    _fields = new Array("address","city","zip","state");
                    for(i=0; i<_fields.length; i++)
                    {
                      if(_fields[i] != "state" || _element.checked == true)
                      {
                        _form.elements["b_" + _fields[i]].value = (_element.checked == true ? _form.elements["s_" + _fields[i]].value : "");
                      }
                      else
                      {
                        _form.elements["b_" + _fields[i]].options[0].selected = true;
                      }
                    }
                  }
                </script>';
      ?>
          <td valign="top">
            <table cellpadding="3" cellspacing="0" border="0">
              <tr>
                <td align="right">&nbsp;&nbsp;Shipping:</td>
                <td>
                  <select name="s_shipping" class="formfield" onChange="_adjustShipping(this.value);">
                    <option value="null">-- Select Shipping --</option>
                    <option value="6.95">USPS Priority (3 - 5 Days)</option>
                    <option value="18.95">USPS 2nd Day</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td align="right">Name:&nbsp;</td>
                <td><input type="text" name="s_nameFull" value="<?php echo $user_info['U_NAMEFIRST'] . ' ' . $user_info['U_NAMELAST']; ?>" class="formfield" style="width:125px;" /></td>
              </tr>
              <tr>
                <td align="right">Street:&nbsp;</td>
                <td><input type="text" name="s_address" value="<?php echo $user_info['U_ADDRESS']; ?>" class="formfield" style="width:125px;" /></td>
              </tr>
              <tr>
                <td align="right">City:&nbsp;</td>
                <td><input type="text" name="s_city" value="<?php echo $user_info['U_CITY']; ?>" class="formfield" style="width:85px;" /></td>
              </tr>
              <tr>
                <td align="right">Zip Code:&nbsp;</td>
                <td><input type="text" name="s_zip" value="<?php echo $user_info['U_ZIP']; ?>" class="formfield" style="width:40px;" /></td>
              </tr>
              <tr>
                <td align="right">State:&nbsp;</td>
                <td>
                  <select name="s_state" class="formfield">
                    <option value="null">-- Select State --</option>
                    <?php 
                      $sel_state = $user_info['U_STATE'];
                      echo optionStates($sel_state);
                    ?>
                  </select>
                </td>
              </tr>
            </table>
          </td>
          <td width="2" class="bg_lite"><img src="images/spacer.gif" width="2" height="1" /></td>
      <?php
        }
      ?>
      <!--Credit Card Information-->
      <td valign="top">
        <table cellpadding="3" cellspacing="0" border="0">
          <?php
            if($shipping === true)
            {
          ?>
              <tr>
                <td colspan="2" align="center"><input type="checkbox" name="same_as_shipping" onClick="_sameAsShipping(this);"  />&nbsp;Same as shipping</td>
              </tr>
          <?php
            }
          ?>
          <tr>
            <td align="right">First Name:&nbsp;&nbsp;</td>
            <td><input type="text" name="cc_nameFirst" value="" class="formfield" style="width:125px;" /></td>
          </tr>
          <tr>
            <td align="right">Last Name:&nbsp;&nbsp;</td>
            <td><input type="text" name="cc_nameLast" value="" class="formfield" style="width:125px;" /></td>
          </tr>
          <tr>
            <td align="right">Street:&nbsp;&nbsp;</td>
            <td><input type="text" name="b_address" value="" class="formfield" style="width:125px;" /></td>
          </tr>
          <tr>
            <td align="right">City:&nbsp;&nbsp;</td>
            <td><input type="text" name="b_city" value="" class="formfield" style="width:85px;" /></td>
          </tr>
          <tr>
            <td align="right">State:&nbsp;&nbsp;</td>
            <td>
              <select name="b_state" class="formfield">
                <option value="null">-- Select State --</option>
                <?php 
                  echo optionStates();
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <td align="right">Zip Code:&nbsp;&nbsp;</td>
            <td><input type="text" name="b_zip" value="" class="formfield" style="width:40px;" /></td>
          </tr>
          <tr>
            <td align="right">Credit Card #:&nbsp;&nbsp;</td>
            <td><input type="text" name="cc_number" value="" class="formfield" style="width:125px;" /></td>
          </tr>
          <!--
          <tr>
            <td align="right">Credit Card Type:&nbsp;&nbsp;</td>
            <td><input type="radio" name="cc_type" CHECKED value="VS" /> Visa&nbsp;&nbsp;&nbsp;<input type="radio" name="cc_type" value="MS" />Mastercard</td>
          </tr>
          -->
          <tr>
            <td align="right">CCV #:&nbsp;&nbsp;</td>
            <td align="left"><input type="text" name="cc_cvv" value="" class="formfield" style="width:25px" />&nbsp;&nbsp;<a href="javascript:_open('/popup/cvv/', 220, 225);">What's this?</a>
          </tr>
          <tr>
            <td align="right">Expiration:&nbsp;&nbsp;</td>
            <td>
              <select name="cc_exp_month" class="formfield">
                  <?php
                    for($i=1; $i<=12; $i++)
                    {
                      echo '<option value="' . $i . '">' . substr(('0' . $i), -2) . '</option>';
                    }
                  ?>
              </select>
                 / 
              <select name="cc_exp_year" class="formfield">
                  <?php
                    $_start = date('Y', NOW);
                    $_end   = $_start + 10;
                    for($i=$_start; $i<=$_end; $i++)
                    {
                      echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                  ?>
              </select>
            </td>
          </tr>
          <tr>
            <td align="right" valign="top"><input type="checkbox" name="accept_terms_of_sale" value="i_accept" />&nbsp;</td>
            <td>I accept the terms of sale<br />(<a href="javascript:_openDefault('/popup/terms_sale/', '545', '350');">view terms of sale</a>)</td>
          </tr>
          <?php
            if($recur !== false)
            {
              echo '<input type="hidden" name="recur_payment" value="' . $recur . '" />';
                    /*<!--<tr>
                      <td align="right" valign="top"><input type="checkbox" name="recur_payment" value="yes" checked="true" />&nbsp;</td>
                      <td nowrap="true">Automatically renew my membership<br />(<a href="javascript:_openDefault(\'/popup/auto_renew/\', \'350\', \'175\');">what\'s this?</a>)</td>
                    </tr>-->*/
            }
          ?>
          <tr>
            <td>&nbsp;</td>
            <td <?php echo ($shipping === false ? 'width="194"' : 'width="150"'); ?>>
              <div id="_checkout_button"><a href="javascript:if(_val_checkout()){ _swapToWait(true); document.forms['_checkout'].submit(); }" /><img src="images/buttons/<?php echo $submit_btn_src; ?>" name="_sub_button" vspace="5" border="0" /></a></div>
              <div id="_processing_button" style="display:none"><img src="images/buttons/please_wait.gif" width="150" height="23" border="0" /></div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td colspan="5" align="center">
        <div style="padding-top:10px;"><img src="images/creditcards_accepted.gif" width="198" height="29" border="0" alt="FotoFlix accepts all major credit cards" title="FotoFlix accepts all major credit cards" /></div>
        <div style="padding-top:10px;">Need help placing your order?  Click <a href="/?action=<?php echo ($logged_in === true ? 'member' : 'home'); ?>.help&search=order+problems">here</a>.</div>
      </td>
    </tr>
    <?php
      if(isset($_GET['forward_to_group']) && isset($_GET['group_id']))
      {
        echo '<input type="hidden" name="forward_to_group" value="' . $_GET['group_id'] . '" />';
      }
    ?>
  </form>
</table>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>