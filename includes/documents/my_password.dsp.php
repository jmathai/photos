<form id="passwordForm" method="post" action="/?action=my.password.act">
  <?php
    if($options[0] == 'failed')
    {
      echo '<div class="center f_12 bold f_red" style="padding-bottom:35px;"><img src="images/icons/warning_32x32.png" class="png" width="32" height="32" border="0" align="absmiddle" hspace="5" />Incorrect password</div>';
    }
    
    if(strstr($_SERVER['REQUEST_URI'], 'password') === false)
    {
      echo '<input type="hidden" name="redirect" value="' . htmlentities($_SERVER['REQUEST_URI']) . '" />';
    }
    else
    {
      echo '<input type="hidden" name="redirect" value="/users/' . $username . '/" />';
    }
    
    echo '<input type="hidden" name="user_id" value=' . $user_id . '" />';
  ?>
  <div class="center">
    <div class="f_12 bold"><img src="images/icons/lock_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="5" />To view this page you must enter a password</div>
    <div style="margin-top:15px;">
      <input type="text" name="p_password" class="formfield" style="width:100px;" />&nbsp;&nbsp;<a href="javascript:void(0);" onclick="$('passwordForm').submit();" title="continue..."><img src="images/icons/right_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" /></a>
    </div>
  </div>
</form>