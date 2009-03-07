<?php
  if(isset($_GET['parentAccount']))
  {
    $us =& CUser::getInstance();
    $parentAccount = $_GET['parentAccount'];
  }
?>

<form method="post" id="loginSubFrm" action="/?action=member.login_sub_form.act">
  <div style="margin:auto; width:275px;">
    <?php
      if(isset($parentAccount))
      {
        echo '<div style="padding-bottom:4px;">
                <div style="float:left; width:100px;">Parent Account</div>
                <div style="float:left;">' . $parentAccount . '<input type="hidden" name="parentAccount" value="' . htmlspecialchars($parentAccount) . '" /></div>
                <br clear="all" />
              </div>';
      }
      else
      {
        echo '<div style="padding-bottom:4px;">
                <div style="float:left; width:100px;">Parent Account</div>
                <div style="float:left;"><input type="text" name="parentAccount" class="formfield" style="width:120px;" /></div>
                <br clear="all" />
              </div>';
      }
    ?>
    
    <div style="padding-bottom:4px;">
      <div style="float:left; width:100px;">Username</div>
      <div style="float:left;"><input type="text" name="subUsername" class="formfield" style="width:150px;" /></div>
      <br clear="all" />
    </div>
    
    <div style="padding-bottom:4px;">
      <div style="float:left; width:100px;">Password</div>
      <div style="float:left; padding-bottom:4px;"><input type="password" name="subPassword" class="formfield" style="width:150px;" /></div>
      <br clear="all" />
    </div>
    
    <div style="padding-bottom:4px; padding-left:100px;">
      <div style="padding-right:3px; float:left;"><a href="javascript:document.getElementById('loginSubFrm').submit();" style="text-decoration:none;" title="log in to leave a comment"><img src="images/login2.gif" width="16" height="16" border="0" alt="leave comment" /></a></div>
      <div style="padding-top:1px;"><a href="javascript:document.getElementById('loginSubFrm').submit();" style="text-decoration:none;" title="log in to leave a comment">Log in</a></div>
    </div>
  </div>
  

</form>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>