<?php
  $u = &CUser::getInstance();
  $g = &CGroup::getInstance();
  
  $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 0;
  $resultsPage = 'search.members_results';
  
  if($group_id != 0)
  {
    $user_group = $g->groups($_USER_ID, $group_id);
    if(count($user_group) == 0)
    {
      $tpl->kill('You are not a member of this group. <a href="/?action=fotogroup.search_members">Search</a> for all users.');
    }
    
    $resultsPage = 'fotogroup.search_members_results&group_id=' . $group_id;
  }
?>

<div class="f_12 bold">User Search</div>
<form id="userSearchForm" method="post" action="./?action=<?php echo $resultsPage; ?>">
  <div style="padding-top:10px; padding-left:10px;">
    <div style="float:left;">
      <div class="bold">Username</div>
      <div><input name="u_username" type="text" value="<?php echo $_POST['u_username']; ?>" class="formfield" style="width:90px;" /></div>
    </div>
    <div style="padding-left:25px; float:left;">
      <div class="bold">Email Address</div>
      <div><input name="u_email" type="text" value="<?php echo $_POST['u_email']; ?>" class="formfield" style="width:90px;" /></div>
    </div>
    <div style="padding-left:25px; float:left;">
      <div class="bold">First Name</div>
      <div>
        <div><input name="u_firstName" type="text" value="<?php echo $_POST['u_firstName']; ?>" class="formfield" style="width:90px;" /></div>
      </div>
    </div>
    <div style="padding-left:25px; float:left;">
      <div class="bold">Last Name</div>
      <div>
        <div><input name="u_lastName" type="text" value="<?php echo $_POST['u_lastName']; ?>" class="formfield" style="width:90px;" /></div>
      </div>
    </div>
    <div style="padding-left:25px; float:left;">
      <div style="padding-top:12px;"><input type="button" value="Search" class="formfield bold" onclick="document.getElementById('userSearchForm').submit();" /></div>
    </div>
    <br />
  </div>
  <div style="padding-top:5px;"></div>
</form>
  
<?php  
  if($_GET['action'] == 'search.members' || $_GET['action'] == 'fotogroup.search_members')
  {
    $tpl->main($tpl->get());
    $tpl->clean();
  }
?>