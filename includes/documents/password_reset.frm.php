<?php
  $align = $tpl->mode == 'single' ? 'center' : 'left';
  
  $fv =  new CFormValidator;
  $fv -> setForm('_password');
  $fv -> addElement('u_email', 'Email', '  - Please enter the email address you registered with.', 'length');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_password');
  $fv -> validate();
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'password_reset':
        $message = 'Your password has been reset and sent to the email address you specified.';
        $image = 'images/icons/checkmark_24x24.png';
        break;
      case 'email_not_found':
        $email = isset($_GET['email']) ? $_GET['email'] : 'none';
        $message = 'Sorry, we were unable to find the email address you entered (<i>' . $email . '</i>) in our database.';
        $image = 'images/icons/warning_24x24.png';
        break;
      case 'email_not_valid':
        $email = isset($_GET['email']) ? $_GET['email'] : 'none';
        $message = 'Sorry, the email address you entered (<i>' . $email . '</i>) does not appear to have valid syntax.';
        $image = 'images/icons/warning_24x24.png';
        break;
      case 'secret_question':
        $email = isset($_GET['email']) ? $_GET['email'] : 'none';
        $message = 'Sorry, but what you entered for "Mother\'s Maiden Name" did not match our records .';
        $image = 'images/icons/warning_24x24.png';
        break;
      default:
        $message = '';
        break;
      
    }
    echo '<div class="confirm"><img src="' . $image . '" class="png" width="24" height="24" border="0" align="absmiddle" hspace="5" />' . $message . '</div>';
  }
?>


<div class="dataSingleContent">
  <form name="_password" method="post" action="/?action=home.password_reset_form.act" onSubmit="return _val_password();">
    <div class="bold f_13" style="margin:25px 0px 10px 0px;">Reset your password</div>
    
    <div class="formRow">
      <div class="formLabel">Email</div>
      <div class="formField"><input type="text" name="u_email" value="<?php echo (isset($_GET['email']) ? $_GET['email'] : ''); ?>" class="formfield" style="width:120px" /></div>
    </div>
    
    <div class="formRow">
      <div class="formLabel">Mother's Maiden Name</div>
      <div class="formField"><input type="text" name="u_secret" value="" class="formfield" style="width:120px" /></div>
    </div>
    
    <div class="formRow">
      <div class="formIndent"><input type="image" src="images/buttons/reset_password.gif" border="0" vspace="5" /></div>
    </div>
  </form>
</div>