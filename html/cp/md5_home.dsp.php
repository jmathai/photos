<?php
  $result = '';
  if(count($_POST) > 0)
  { 
    $result = md5($_POST['md5_request']);
  }
?>

<form name="md5_form" action="/cp/?action=md5.home" method="POST">
  <div style="padding-top:25px;">Find MD5 of: <input type="text" name="md5_request" maxlength="500" size="100" value="<?php echo $_POST['md5_request']; ?>" /> &nbsp; <input name="md5_submit" type="submit" value="Submit" /></div>
  <div style="padding-top:10px;">MD5: <?php echo $result; ?></div>
</form>