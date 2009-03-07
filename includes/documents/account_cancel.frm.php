<?php
  $u =& CUser::getInstance();
  $userData = $u->find($_USER_ID);
?>

<div>
  <div class="bold" style="padding-bottom:10px;"><img src="/images/icons/stop_alt_2_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="absmiddle" />Confirm your request to cancel membership</div>
  
  <div>
    Are you sure you want to cancel your membership?  
    <?php
     echo '<div style="padding-bottom:10px; padding-top:10px;">We strive to do everything that we can to keep all of our members (including you) happy.  If there have been any problems you have had using Photagious then <a href="/contactus/">contact us</a>.</div>
              <div style="padding-bottom:10px;">If you cancel your membership it will remain active until ' . date(FF_FORMAT_DATE_LONG, $userData['U_DATEEXPIRES']) . '.  After that date you will no longer have access to any material you uploaded to or created on Photagious.</div>';
    ?>
  </div>
  
  <div>
    <div style="width:193px; margin:auto;" align="center"><a href="/?action=account.cancel_form.act"><img src="images/buttons/cancel_membership.gif" width="193" height="23" border="0" vspace="10" /></a></div>
  </div>
</div>