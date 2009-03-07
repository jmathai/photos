<?php
  $dFrom = $_GET['u_dateCreatedFrom'];
  $dTo   = $_GET['u_dateCreatedTo'];
?>

<div class="header">Search Users</div>
<form id="userSearchForm" method="get" action="./">
  <input type="hidden" name="action" value="users.search_results" />
  <div class="padding_top_5">
    <div style="float:left;">
      <div>Username</div>
      <div><input name="u_username" type="text" value="<?php echo htmlspecialchars($_GET['u_username']); ?>" class="formfield" style="width:100px;" /></div>
    </div>
    <div style="padding-left:30px; float:left;">
      <div>Email Address</div>
      <div><input name="u_email" type="text" value="<?php echo htmlspecialchars($_GET['u_email']); ?>" class="formfield" style="width:100px;" /></div>
    </div>
    <div style="padding-left:30px; float:left;">
      <div>First Name</div>
      <div>
        <div><input name="u_nameFirst" type="text" value="<?php echo htmlspecialchars($_GET['u_nameFirst']); ?>" class="formfield" style="width:100px;" /></div>
      </div>
    </div>
    <div style="padding-left:30px; float:left;">
      <div>Last Name</div>
      <div>
        <div><input name="u_nameLast" type="text" value="<?php echo htmlspecialchars($_GET['u_nameLast']); ?>" class="formfield" style="width:100px;" /></div>
      </div>
    </div>
    <div style="padding-left:30px; float:left;">
      <div>Date From (mm-dd-yyyy)</div>
      <div>
        <input type="text" name="u_dateCreatedFrom" class="formfield" value="<?php echo $dFrom; ?>" />
      </div>
    </div>
    <div style="padding-left:30px; float:left;">
      <div>Date To (mm-dd-yyyy)</div>
      <div>
        <input type="text" name="u_dateCreatedTo" class="formfield" value="<?php echo $dTo; ?>" />
      </div>
    </div>
    <div style="padding-left:15px; float:left;">
      <div style="padding-top:10px;"><input type="button" value="Search" class="formfield bold" onclick="document.getElementById('userSearchForm').submit();" /></div>
    </div>
    <br clear="all" />
  </div>
  <div class="padding_top_5"></div>
</form>