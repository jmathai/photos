<?php
  $g = &CGroup::getInstance();
  
  $g_id = $_GET['group_id'];
 
  // paging info
  $limit = 12;
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  
  $fotos = $g->fotoContributions($_USER_ID, $g_id, ($currentPage-1)*$limit, $limit);
  $totalRows = $GLOBALS['dbh']->found_rows();
  $pagesToDisplay = 15;
  $totalPages = ceil($totalRows/$limit);
  
  $pg  =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
?>

<div style="padding-top:10px; padding-left:15px;">
  <div style="text-align:left;" class="f_8 bold">My Foto Contributions</div>
</div>

<?php  
  echo '<div style="padding-left:15px;" id="_fotos">';
  
  foreach($fotos as $k => $v)
  {
    echo '<div style="float:left; padding-top:25px; padding-right:40px; text-align:left;" id="_foto_' . $v['P_KEY'] . '">';
      echo '<div><img src="images/fb_frame_top.gif" width="87" height="5" vspace="0" hspace="0" border="0" /></div>';
      echo '<div style="float:left;"><img src="images/fb_frame_left.gif" width="5" height="75" vspace="0" hspace="0" border="0" /></div>';
      echo '<div style="float:left;"><a href="/?action=fotogroup.image_show&group_id=' . $group_id . '&image_id=' . $v['P_ID'] . '"><img src="' . PATH_FOTO . $v['P_THUMB_PATH'] . '?' . time() . '" ' . ' width="' . FF_THUMB_WIDTH . '" height="' . FF_THUMB_HEIGHT . '" hspace="0" vspace="0" border="0" /></a></div>';
      echo '<div><img src="images/fb_frame_right.gif" width="7" height="75" vspace="0" hspace="0" border="0" /></div>';
      echo '<div><img src="images/fb_frame_bottom.gif" width="87" height="7" vspace="0" hspace="0" border="0" /></div>';
      echo '<div style="padding-left:10px;"><a href="/?action=fotogroup.foto_contributions_unshare.act&ids=' . $v['P_ID'] . '&group_id=' . $group_id . '" class="f_7">Unshare foto</a></div>';
    echo '</div>';
  }
  
  echo '</div>';
  echo '<br clear="left" />';
  
  if($totalPages > 1 )
  {
    echo '<div style="padding-top:10px; padding-right:100px; text-align:right;" class="f_7">Pages:';
  
    if(($currentPage - $pagesToDisplay) > 0)
    {
      echo $pg->getFirstPage('1') . '&nbsp;&middot;&middot;&middot;';
    }
    
    echo $pg->getPages();  
    
    if($currentPage < ($totalPages - $pagesToDisplay))
    {
      echo '&middot;&middot;&middot;&nbsp;' . $pg->getLastPage($totalPages);
    }
    echo '</div>';
  }
?>

<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>