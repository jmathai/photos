<?php
  $key = isset($_GET['key']) ? $_GET['key'] : '';
?>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="545">
  <tr>
    <td align="center">
      <table border="0" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td align="left">
            <div align="center"><img src="images/expired.gif" width="200" height="57" border="0" vspace="5" alt="this account expired" /></div>
            <div class="bold">Your account has expired.</div><br />
            Click <a href="/?action=home.purchase_trial.act&key=<?php echo $key; ?>">here</a> to purchase a membership for as low as $2.39.
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