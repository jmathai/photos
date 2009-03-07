<?php
  $g =& CGroup::getInstance();
  
  $group_id = $_GET['group_id'];
  $group_data = $g->groupData($group_id, $_USER_ID);
  
  $is_owner = $g->isOwner($group_id, $_USER_ID, $group_data);
  
  if($is_owner === false)
  {
    echo 'Sorry, but you must be the owner of a group to delete it.';
  }
  else
  {
?>
    <table border="0" cellspacing="0" cellpadding="0" width="545">
      <tr>
        <td align="left">
          <div class="bold">Delete Group: <?php echo $group_data['G_NAME']; ?></div>
          <br />
          If you continue, an email will be sent to all members of the FotoGroup notifying them that the FotoGroup is going to be deleted.  
          Members will have between 24 and 48 hours to use the FotoGroup and save FotoGroup fotos to their FotoBox.  
          Once the FotoGroup is deleted it can no longer be accessed.
          <br /><br />
          Proceed only if you are sure you want to delete this FotoGroup!
          <br /><br />
        </td>
      </tr>
      <tr>
        <td align="center">
          <a href="/?action=fotogroup.group_delete.act&group_id=<?php echo $group_id; ?>"><img src="images/buttons/delete_group.gif" with="128" height="23" border="0" /></a>
        </td> 
      </tr>
    </table>
<?php
  }
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>