<?php
  $fv =&  new CFormValidator;
  
  $fv =  new CFormValidator;
  $fv -> setForm('_password');
  $fv -> addElement('u_password_current', 'Current Password', '  - Please enter your current password.', 'length');
  $fv -> addElement('u_password', 'New Password', '  - Please enter a new password.', 'length');
  $fv -> addElement('u_password_confirm', 'Confirm New Password', '  - Please confirm your new password.', 'length');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_password');
  $fv -> validate();  
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'password_updated':
        $message_text = 'Your password was successfully updated.';
        break;
      case 'passwords_do_not_match':
        $message_text = 'Your new password and new password confirmation did not match.';
        break;
      case 'current_password_wrong':
        $message_text = 'Your current password was incorrect.';
        break;
      default:
        $message_text = '';
    }
    
    echo    '<div class="confirm">' . $message_text . '</div>';
  }
?>

<div class="bold" style="margin-bottom:20px;"><img src="images/icons/key_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="3" /> Change Your Password</div>
  <form name="_password" action="/?action=account.password_form.act" method="post" onSubmit="return _val_password();">
  
  <div class="formRow">
    <div class="formLabel">Current Password</div>
    <div class="formField"><input type="password" name="u_password_current" value="" class="formfield" style="width:110px" /></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">New Password</div>
    <div class="formField"><input type="password" name="u_password" value="" class="formfield" style="width:110px" /></div>
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formLabel">Confirm New Password</div>
    <div class="formField"><input type="password" name="u_password_confirm" value="" class="formfield" style="width:110px" /></div
  </div>
  <div style="padding-top:5px;"></div>
  <div class="formRow">
    <div class="formIndent"><a href="javascript:if(_val_password()){ document.forms['_password'].submit(); }"><img src="images/buttons/update.gif" border="0" /></a></div>
  </div>
</form>