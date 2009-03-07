<?php
  $us =& CUser::getInstance();
  
  $userData = $us->findIncomplete($_GET['key']);
  
  if($userData !== false)
  {
?>
    <div style="width:400px; margin:auto;">
      <div class="bold f_11">Thanks for giving us feedback.</div>
      <br/>
      We would appreciate any additional feedback that you could provide regarding our service.  
      Please enter any additional feedback in the form below.
      <br/><br/>
      <form action="/?action=account.incomplete_user_response.act&key=<?php echo $_GET['key']; ?>" method="post">
        <textarea style="width:375px; height:150px;" name="uir_customResponse" class="formfield"></textarea>
        <br/><br/>
        <input type="submit" value="Leave feedback" class="formbutton" />
      </form>
    </div>
<?php
  }
  else
  {
    echo '<div style="margin-top:25px;" class="center bold f_10">We could not find your user record.</div>';
  }
?>