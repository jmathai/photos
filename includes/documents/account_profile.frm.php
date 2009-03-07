<?php
  $u  =& CUser::getInstance();
  $fv =&  new CFormValidator;
  
  $user_data = $u->find($_USER_ID);
  $required = '<span class="f_dark_accent"><sup>*</sup></span>';
  
  $fv -> setForm('_profile');
  $fv -> addElement('u_email', 'Email', '  - Please enter your email address.', 'email');
  $fv -> addElement('u_nameFirst', 'First Name', '  - Please enter your first name.', 'length');
  $fv -> addElement('u_nameLast', 'Last Name', '  - Please enter your last name.', 'length');
  $fv -> addElement('u_address', 'Address', '  - Please enter your address.', 'length');
  $fv -> addElement('u_city', 'City', '  - Please enter your city.', 'length');
  $fv -> addElement('u_zip', 'Zip', '  - Please enter your 5 digit zip code.', 'numeric');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_profile');
  $fv -> validate();
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'updated':
        $message_text = 'Your account was successfully updated.';
        break;
      default:
        $message_text = '';
    }
    
    echo    '<div class="confirm">' . $message_text . '</div>';
  }
?>

<div class="bold" style="margin-bottom:20px;"><img src="images/icons/vcard_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="3" /> Update Your Account Information</div>

<form name="_profile" action="/?action=account.profile_form.act" method="post" onSubmit="return _val_profile();">
  <div class="formRow">
    <div class="formLabel">Username</div>
    <div class="formField"><?php echo $user_data['U_USERNAME']; ?></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">Email Address</div>
    <div class="formField"><input type="text" name="u_email" value="<?php echo $user_data['U_EMAIL']; ?>" class="formfield" style="width:125px;" /></div>
  </div>
  <div style="padding-top:5px;"></div>  
  <div class="formRow">
    <div class="formLabel">First Name</div>
    <div class="formField"><input type="text" name="u_nameFirst" value="<?php echo $user_data['U_NAMEFIRST']; ?>" class="formfield" style="width:125px;" /></div>
  </div>
    <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">Last Name</div>
    <div class="formField"><input type="text" name="u_nameLast" value="<?php echo $user_data['U_NAMELAST']; ?>" class="formfield" style="width:125px;" /></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">Address</div>
    <div class="formField"><input type="text" name="u_address" value="<?php echo $user_data['U_ADDRESS']; ?>" class="formfield" style="width:125px;" /></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">City</div>
    <div class="formField"><input type="text" name="u_city" value="<?php echo $user_data['U_CITY']; ?>" class="formfield" style="width:125px;" /></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">State</div>
    <div class="formField">
      <select name="u_state" class="formfield">
        <?php
          $sel_state = $user_data['U_STATE'];
          echo optionStates($sel_state);
        ?>
      </select>
    </div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">Zip Code</div>
    <div class="formField"><input type="text" name="u_zip" value="<?php echo $user_data['U_ZIP']; ?>" class="formfield" style="width:125px;" /></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">Country</div>
    <div class="formField">
      <select name="u_country" class="formfield">
        <?php
          $sel_country = $user_data['U_COUNTRY'];
          echo optionCountries($sel_country);
        ?>
      </select>
    </div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">Mother's Maiden Name</div>
    <div class="formField"><input type="text" name="u_secret" value="<?php echo $user_data['U_SECRET']; ?>" class="formfield" style="width:125px;" /></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formIndent"><a href="javascript:if(_val_profile()){ document.forms['_profile'].submit(); }"><img src="images/buttons/update.gif" border="0" /></a></div>
  </div>
  
  <?php
    echo '<input type="hidden" name="u_id" value="' . $_USER_ID . '" />';
  ?>
</form>