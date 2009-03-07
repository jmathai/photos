<?php
  $fb =& CFotobox::getInstance();
  
  $page  = intval($_GET['page']);
  $limit = 56;
  $start = $page * $limit;

  
  $fotos = $fb->fotosByTags(false, false, 3, false, $start, $limit);
  $totalFotos = $GLOBALS['dbh']->found_rows();
  $totalPages = floor($totalFotos / $limit);
  $showPages  = 30;
  
  $pg = new CPaging($page, $showPages, $totalPages);
  
  echo '<div style="padding-top:5px; padding-bottom:5px;"><iframe name="showSelected" src="show_public_fotos.php" style="width:660px; height:80px;"></iframe></div>
        <div style="padding-bottom:10px;">';
  if(($page - $showPages) > 0)
  {
    echo $pg->getFirstPage('1') . '&nbsp;&middot;&middot;&middot;';
  }
  
  echo $pg->getPages();  
  
  if($page < ($totalPages - $showPages))
  {
    echo '&middot;&middot;&middot;&nbsp;' . $pg->getLastPage($totalPages);
  }
  echo '</div>';
  
  
  $i = 1;
  echo '<form name="fotos">';
  foreach($fotos as $v)
  {
    echo '<div style="float:left; padding-right:5px; padding-bottom:15px; text-align:center;"><a href="show_public_fotos.php?doId=' . $v['P_ID'] . '" target="showSelected"><img src="' . PATH_FOTO . $v['P_THUMB_PATH'] . '" /></a><br/>' . $v['P_WIDTH'] . 'x' . $v['P_HEIGHT'] . '<br/><a href="' . PATH_FOTO . $v['P_WEB_PATH'] . '" target="_blank" style="font-size:10px;">larger</a>&nbsp;&nbsp;<a href="javascript:confirmSubmit('. $v['P_ID'] . ',' . $v['P_U_ID'] . ');" style="font-size:10px;">delete</a>&nbsp;<input type="checkbox" name="deletes" value="'. $v['P_ID'] . ',' . $v['P_U_ID'] . '" /></div>';
  }
  echo '
        <br clear="all"/>
        <div style="padding-top:10px;"><input type="button" value="Delete all checked" onclick="batchDelete();" /></form>';
  
  echo '<br clear="all" />';
?>

<script language="Javascript">
  function confirmSubmit( id, userid )
  {
    var agree = confirm("Delete this photo?");
    
    if (agree)
    {
      document.location.href = 'delete_foto.php?p_id=' + id + '&u_id=' + userid + '&page=' + <?php echo $page; ?>;
    }
  }
  
  function batchDelete()
  {
    var element = document.forms['fotos'].elements['deletes'];
    var append = '';
    for(i=0; i<element.length; i++)
    {
      if(element[i].checked)
      {
        append += '&ids[]=' + element[i].value;
      }
    }
    
    location.href = 'delete_foto.php?page=' + <?php echo $page; ?> + append;
  }
</script>