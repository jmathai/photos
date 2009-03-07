<?php
  $g = &CGroup::getInstance();
  $u = &CUser::getInstance();
  
  $group_id = $_GET['group_id'];
  $key = $_GET['key'];
  
  $info = $g->inviteData($key);
  
  if(empty($info))
  {
    echo 'Error';
    die();
  }
  else if($group_id != $info['I_G_ID'] || $_USER_ID != $info['I_U_ID'])
  {
    echo 'Incorrect Invite';
    die();
  }
  else 
  {
    $info = $g->groupData($group_id);
    $userInfo = $u->find($info['G_U_ID']);
  }
?>

<div style="float:left; width:685px; margin-top:10px;">

  <div class="f_10 bold">Group Invitation</div>
  <div style="margin-top:10px;" class="gradient_lt_grey">
    <div style="float:left; padding-top:5px; padding-left:15px;" class="f_black bold">
      <div style="float:left; width:200px;">Group Name</div>
      <div style="float:left; width:150px;">Moderator</div>
      <div>Accept/Decline</div>
    </div>
  </div>
  <div style="padding-left:15px; padding-top:10px; height:40px; background-color:#EFEFEF; border-bottom:2px solid white;">
    <div style="float:left; width:200px; padding-top:7px;"><?php echo $info['G_NAME']; ?></div>
    <div style="float:left; width:150px; padding-top:7px;"><?php echo $userInfo['U_USERNAME']; ?></div>
    <div>
      <div style="float:left; padding-right:15px;"><A href="/?action=group.member_accept.act&group_id=<?php echo $group_id; ?>&key=<?php echo $key; ?>&type=accept"><img src="/images/buttons/group_accept.gif" border="0" /></a></div>
      <div><a href="/?action=group.member_accept.act&group_id=<?php echo $group_id; ?>&key=<?php echo $key; ?>&type=decline"><img src="/images/buttons/group_decline.gif" border="0" /></a></div>
    </div>
  </div>
  
</div>

<?php
  include_once PATH_DOCROOT . '/group_sponsors.dsp.php';
?>