<?php
  $display_only = true;
  $no_paging = true;
  $no_checkboxes = true;
  $no_title = true;
  $foto_ids = $_GET['ids'];
?>

Are you sure you want to unshare all the foto(s) listed below?

<form method="post" action="/?action=fotogroup.fotos_unshare_form.act&group_id=<?php echo $_GET['group_id']; ?>">
  <input type="hidden" name="ids" value="<?php echo $_GET['ids']; ?>" />
  <input type="image" src="images/buttons/unshare_fotos.gif" border="0" />&nbsp;&nbsp;&nbsp;<a href="javascript:history.back();"><img src="images/buttons/cancel.gif" width="87" height="25" border="0" /></a>
</form>