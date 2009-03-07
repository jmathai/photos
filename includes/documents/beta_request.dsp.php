<?php
$fv = new CFormValidator();
$fv -> setForm('_beta');
$fv -> addElement('be_email', 'Email', '  - Please enter an email address.', 'email');
$fv -> setDebugOutput(false);
$fv -> setFunctionName('_validate');
$fv -> validate();
?>

<div class="center">
  <div style="padding-top:10px;" class="f_12 bold">Photagious is currently accepting beta testers</div>
  <div style="padding-top:25px;">If you would like to be a beta tester for Photagious please enter your email address below</div>
  <div id="_hideBeta" style="padding-top:10px; display:block;">
    <form name="_beta" onsubmit="if(_validate()) { betaEmail($('be_email').value); } return false;">
      <input type="text" name="be_email" id="be_email" size="35" class="formfield" />
      <a href="javascript:void(0);" onclick="if(_validate()) { betaEmail($('be_email').value); }"><img src="images/icons/send_mail_24x24.png" class="png" width="24" height="24" border="0" hspace="5" align="absmiddle" style="margin-top:-5px;" /></a>
    </form>
  </div>
  <div id="thankYou" style="padding-top:20px; display:none;" class="f_10 bold">Thank you for your interest.  If you are selected you will receive an email when testing begins.</div>
</div>

<script type="text/javascript">
  $('be_email').focus();
</script>