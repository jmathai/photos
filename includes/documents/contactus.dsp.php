<?php
  $fv =  new CFormValidator;
  
  $fv -> setForm('_contact');
  $fv -> addElement('email_name', 'Your Name', '  - Please enter your name.', 'length');
  $fv -> addElement('email_from', 'Your Email', '  - Please enter your email address.', 'email');
  $fv -> addElement('email_message', 'Message', '  - Please enter your message.', 'length');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_contact');
  $fv -> validate();
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'email_sent':
        $message = '<img src="images/icons/checkmark_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="5" />Your inquiry was successfully sent.  You should receive a response within one business day.';
        break;
      default:
        $message = '';
        break;
    } 
    
    echo    '<div class="confirm">'
          . $message
          . '</div>';
  }
?>


<div class="dataSingleContent">
  <div class="bold">Customer Support</div>
  Please check our <a href="/?action=home.help">FAQs</a> for answers to many common questions.  
  You'll find the information you need on topics ranging from setting up an account to adding pictures to placing orders.  
  You'll also find lots of tutorials throughout the site.
  <br /><br />
  <div class="bold">Write to us</div>
  <div>
    We would love to hear from you.  If you could not find answers to your question in our <a href="/?action=home.help">FAQs</a> then contact us by emailing <script type="text/javascript"> writeEmail('support'); </script>.
  </div>
  <!--
  <form name="_contact" method="post" action="/?action=home.contactus.act" onsubmit="return _val_contact();">
    <div class="formRow">
      <div class="formLabel">Your Name</div>
      <div class="formField"><input type="text" name="email_name" class="formfield" /></div>
    </div>
    
    <div class="formRow">
      <div class="formLabel">Your Email</div>
      <div class="formField"><input type="text" name="email_from" class="formfield" /></div>
    </div>
    
    <div class="formRow">
      <div class="formLabel">Your Message</div>
      <div class="formField"><textarea name="email_message" class="formfield" style="width:300px; height:200px;"></textarea></div>
    </div>
    
    <div class="formRow">
      <div class="formIndent">
        <div><input type="image" src="images/buttons/send_email.gif" width="150" height="23" border="0" vspace="5" /></div>
        <div>
          You may also email us at <script type="text/javascript"> writeEmail('support'); </script>.
        </div>
      </div>
    </div>
  </form>
  -->
</div>