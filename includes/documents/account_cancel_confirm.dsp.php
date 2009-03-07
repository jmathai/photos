<?php
  $u  =& CUser::getInstance();
  $userData = $u->find($_USER_ID);
?>

<div style="width:545px;" align="left">
  <div class="bold" style="padding-bottom:10px;">Your account was successfully cancelled</div>
  <div>
    Your confirmation code is <?php echo $_GET['confirmationId']; ?>.  
    We have sent you a email with the confirmation code.  
  </div>
  <br/>
  <div>
    Your account will remain active through <?php echo date(FF_FORMAT_DATE_LONG, $userData['U_DATEEXPIRES']); ?>.  
    After that date your account will no longer have access to any services on Photagious.  
    Your account cancellation will include the removal of any photos, slideshows, and other items created at Photagious.
  </div>
  <br/>
  <div>
    If you have any questions please let us know by emailing us at <script> writeEmail('support'); </script>.
  </div>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>