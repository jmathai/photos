<?php
  $us =& CUser::getInstance();
  
  $user_data = $us->find($_USER_ID);  
  
  $total = $user_data['U_SPACETOTAL'];
  $usage  = $user_data['U_SPACEUSED'];
  $usage_percent = intval(($usage / $total) * 100);
  
  /*$total  = $us->space($_USER_ID);
  $usage  = $fb->spaceUsage($_USER_ID);
  $usage_percent = intval(($usage / $total) * 100);*/
?>
<table border="0" cellpadding="0" cellspacing="0" width="126">
  <tr>
    <td width="3"><img src="images/bar_graph_left.gif" width="3" height="16" border="0" /></td>
    <td width="<?php echo $usage_percent; ?>"><img src="images/bar_graph_full.gif" width="<?php echo $usage_percent; ?>" height="16" border="0" /></td>
    <td width="<?php echo (100 - $usage_percent); ?>"><img src="images/bar_graph_empty.gif" width="<?php echo (100 - $usage_percent); ?>" height="16" border="0" /></td>
    <td width="3"><img src="images/bar_graph_right.gif" width="3" height="16" border="0" /></td>
    <td width="20">&nbsp;<?php echo $usage_percent; ?>%</td>
  </tr>
</table>