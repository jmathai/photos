<?php
  $groupId = intval($_GET['group_id']);
?>

<div id="navigation" class="bold">
  <div class="groupNavLink <?php if($action == 'group.home'){ echo 'groupNavLinkOn'; } ?>"><a href="/?action=group.home&group_id=<?php echo $groupId; ?>" class="plain">Home</a></div>
  <div class="groupNavLink <?php if($action == 'group.photos'){ echo 'groupNavLinkOn'; } ?>"><a href="/?action=group.photos&group_id=<?php echo $groupId; ?>" class="plain">Photos</a></div>
  <div class="groupNavLink <?php if($action == 'group.slideshows'){ echo 'groupNavLinkOn'; } ?>"><a href="/?action=group.slideshows&group_id=<?php echo $groupId; ?>" class="plain">Slideshows</a></div>
  <!-- <div><a href="">Videos</a></div> -->
  <div class="groupNavLink <?php if($action == 'group.members'){ echo 'groupNavLinkOn'; } ?>"><a href="/?action=group.members&group_id=<?php echo $groupId; ?>" class="plain">Members</a></div>
  <div class="groupNavLink <?php if($action == 'group.board_main'){ echo 'groupNavLinkOn'; } ?>"><a href="/?action=group.board_main&group_id=<?php echo $groupId; ?>" class="plain">Community</a></div>
</div>