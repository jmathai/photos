<?php
  $mode = $logged_in === true ? 'upgrade' : 'login';
  $qoop = false;
  
  if(isset($_GET['redirect']))
  {
    $redirect = $_GET['redirect'];
  }
  elseif(isset($_GET['bonus']))
  {
    $bonus = $_GET['bonus'];
    $photosite_id = 'photagious';
    $redirect = 'http://www.qoop.com/photobooks/check_signon.php?photosite_id=' . $photosite_id . '&bonus=' . $bonus;
    $qoop = true;
  }
  else
  if(strstr($_SERVER['QUERY_STRING'], 'confirm.main') === false && $action != 'home.login_form')
  {
    $redirect = ($_SERVER['PHP_SELF'] == '/index.php' ? '/?' : ($_SERVER['PHP_SELF'].'?')) . $_SERVER['QUERY_STRING'];
  }
  else
  {
    $redirect = '/?action=fotobox.fotobox_main';
  }
?>

<div style="width:350px; padding-top:25px; margin:auto;">
  <?php
    if(isset($_GET['message']))
    {
      if($_GET['message'] == 'login_failed')
      {
        echo '<div class="confirm"><img src="images/icons/close_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="5" />Sorry but we didn\'t recognize your username and password.</div>';
      }
      else 
      {
        echo '<div class="confirm"><img src="images/icons/close_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="5" />Sorry but your account has expired.</div>';
      }
    }
  ?>
  <form action="/?action=member.login_form.act" method="post">
    <div class="bold f_10" style="padding-bottom:10px;">
      <?php
        if($_GET['migrated'] == 'fotoflix')
        {
          echo '<div align="center"><img src="images/fotoflix_to_photagious_equation.gif" width="237" height="62" vspace="10" /></div>
                Use your FotoFlix user name and password';
        }
        else
        {
          echo '    Enter your user name and password to continue';
        }
      ?>
    </div>
    
    <div class="formRow">
      <div class="formLabelLogin">Username</div>
      <div class="formFieldLogin"><input type="text" name="u_username" id="loginUsernameField" value="" style="width:120px;" class="formfield" /></div>
    </div>
    
    <div class="formRow">
      <div class="formLabelLogin">Password</div>
      <div class="formFieldLogin"><input type="password" name="u_password" value="" style="width:120px;" class="formfield" /></div>
    </div>
    
    <div class="formRow">
      <div class="formLabelLogin"></div>
      <div class="formFieldLogin"><input type="checkbox" name="persistent_login" value="1" /> Keep me logged in</div>
    </div>
    
    <div class="formRow">
      <div class="formLabelLogin"></div>
      <div class="formFieldLogin"><input type="image" src="images/buttons/login_beta.gif" width="86" height="26" vspace="5" /></div>
    </div>
    
    Forgot your password? Click <a href="/?action=home.password_reset_form">Here</a>.
    <br />
    Need an Account? Click <a href="/?action=home.registration_form_b">Here</a>
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    <?php
      if($qoop === true)
      {
        echo '<input type="hidden" name="qoop" value="1" />';
      }
    ?>
  </form>
</div>

<script>
  $('loginUsernameField').focus();
</script>