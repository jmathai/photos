<?php
  $activation_key = isset($_GET['activation_key']) ? $_GET['activation_key'] : '';
?>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="545">
  <tr>
    <td align="center">
      <table border="0" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td align="left">
            <div align="center"><img src="images/check_email.gif" width="200" height="128" border="0" vspace="5" alt="check your email" /></div>
            <div class="bold">Your account has not yet been activated.</div><br />
            Lost the email?  Click <a href="/?action=home.email_activation.act&activation_key=<?php echo $activation_key; ?>">here</a> to have it resent.
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<?php
  $flush_data = true;
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>