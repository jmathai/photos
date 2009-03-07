<?php
  $u =& CUser::getInstance();
  $fv =  new CFormValidator;
  
  $checkPerm = $u->find($_USER_ID);
  if($checkPerm['U_PARENTID'] > 0)
  {
    echo '<script> location.href = "/"; </script>';
  }
  
  $accounts = $u->childAccounts($_USER_ID);

  $fv -> setForm('_registration');
  $fv -> addElement('u_username', 'Username', '  - Username must be between 4 and 16 alpha-numeric characters.', 'regexp/^[a-zA-Z0-9_]{4`,16}$/');
  $fv -> addElement('u_password', 'Password', '  - Please enter a password.', 'length');
  $fv -> addElement('u_email', 'Email', '  - Please enter your email address.', 'email');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_registration');
  $fv -> validate();
?>
<div class="dataSingleContent">
  <form name="_registration" id="_registration" method="post" action="/?action=manage.account_create.act" onsubmit="return _val_registration();">
    <div class="formBlock">
      <div class="formRow">
        <div class="formLabel">Username</div>
        <div class="formField">
          <input type="text" name="u_username" id="u_username" value="<?php echo $_get_udf['u_username']; ?>" class="formfield" style="width:110px" onblur="checkUsername(this);" /><?php echo $required; ?>&nbsp;(4 to 16 characters)
        </div>
        <div class="formIndent italic" id="_usernameSuggest"></div>
      </div>
      
      <div class="formRow">
        <div class="formLabel">Password</div>
        <div class="formField"><input type="password" name="u_password" value="" class="formfield" style="width:110px" /><?php echo $required; ?></div>
      </div>
      
      <div class="formRow">
        <div class="formLabel">Email</div>
        <div class="formField"><input type="text" name="u_email" id="u_email" value="<?php if(isset($_get_udf['u_email'])){ echo $_get_udf['u_email']; } ?>" <?php if($_get_udf['fotoflixTrialUser'] != '101'){ echo 'onblur="checkEmail(this);"'; } ?> class="formfield" style="width:135px" /><?php echo $required; ?></div>
        <div class="formIndent italic bold f_red" id="_emailSuggest"></div>
      </div>
    </div>
    
    <div class="formRow">
      <div class="formIndent">
        <div id="registrationMessage"></div>
        <div id="registrationButton" style="padding-left:5px;">
          <input type="image" src="images/buttons/create_account.gif" id="registrationSubmit" width="168" height="34" border="0" vspace="2" border="0" />
        </div>
      </div>
    </div>
    
    <input type="hidden" name="u_parentId" value="<?php echo $_USER_ID; ?>" />
  </form>


  <?php
    $cntAccounts = count($accounts);
    if($cntAccounts > 0)
    {
      echo '<div class="bold">' . $cntAccounts . ' total accounts</div>
            <br/>
            <table border="0" cellpadding="2" cellspacing="1" bgcolor="#ffffff">
              <tr>
                <th width="150">Username</th>
                <th width="250">Email</th>
                <th>&nbsp;</th>
              </tr>';
      
      foreach($accounts as $v)
      {
        echo '<tr bgcolor="#efefef">
                <td>' . $v['U_USERNAME'] . '</td>
                <td>' . $v['U_EMAIL'] . '</td>
                <td><a href="/?action=secure.login.act&id=' . $v['U_ID'] . '">login</a></td>
              </tr>';
      }
      
      echo '</table>';
    }
  ?>

</div>