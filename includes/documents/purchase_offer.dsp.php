<?php
  $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/?action=fotobox.fotobox_main';
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
 <tr>
   <td align="center">
    <table cellpadding="0" cellspacing="0" border="0" width="537">
      <tr height="25">
        <td bgcolor="#DOD1D6" align="left" colspan="3" class="bold" background="images/trial_account_header.jpg">&nbsp;&nbsp;Free Account</td>
      </tr>
      <tr>
        <td width="1" bgcolor="#D0D1D6"></td>
        <td width="260"></td>
        <td width="267"><img src="images/join_now_offer.jpg" width="276" height="38" border="0" /></td>
      </tr>
    </table>
   </td>
 </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
 <tr>
   <td align="center">
      <table cellpadding="0" cellspacing="0" border="0" width="537">
        <tr>
          <td width="1" bgcolor="#D0D1D6"></td>
          <td width="300">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td align="center" width="300">
                    <a href="<?php echo $redirect; ?>"><img src="images/buttons/continue.gif" width="133 " height="25" border="0" /></a>
                    <br />
                    <div style="height:35px;"></div>
                </td>
              </tr>
            </table>
          </td>
          <td align="center" background="images/offer.gif">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td class="f_8 f_white" align="left">
                  <div class="bold" style="font-size:11pt; color:#FDDF43;">Need more space?<br />Upgrade today!</div>
                  <br />
                  Premium members enjoy:<br />
                  &nbsp;&nbsp;&middot;&nbsp;Up to 1000MB of space to start<br />
                  &nbsp;&nbsp;&middot;&nbsp;Unlimited space upgrades<br />
                  &nbsp;&nbsp;&middot;&nbsp;No ads while browsing<br />
                  &nbsp;&nbsp;&middot;&nbsp;Special promotions<br />
                  &nbsp;&nbsp;&middot;&nbsp;Early access to new features<br />
                  &nbsp;&nbsp;&middot;&nbsp;And much much more!<br /><br />
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td bgcolor="#D0D1D6" align="center" colspan="2">&nbsp;</td>
          <td bgcolor="#D0D1D6" align="center"><a href="/?action=account.upgrade_form"><img src="images/buttons/upgrade.gif" border="0" /></a></td>
        </tr>
      </table>   
   </td>
 </tr>
</table>
<br /><br />
<div align="center">
  <?php include_once PATH_DOCROOT . '/ads_horizontal.dsp.php'; ?>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
