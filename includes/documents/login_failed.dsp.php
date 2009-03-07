<?php
  $activation_key = isset($_GET['activation_key']) ? $_GET['activation_key'] : '';
?>
<table border="0" cellpadding="0" cellspacing="0" align="center" width="545">
  <tr>
    <td align="center">
      <table border="0" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td align="left">
            <div align="center"><img src="images/login_failed.gif" width="147" height="46" border="0" vspace="5" alt="your login failed" /></div>
            <div class="bold">We were unable to log you in.</div><br />
            <br />
            <?php 
              $no_set_template = true;
              include_once PATH_DOCROOT . '/login.frm.php';
            ?>
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