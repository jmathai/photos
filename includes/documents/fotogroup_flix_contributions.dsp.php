<?php
  $g = &CGroup::getInstance();
  $fb = &CFotobox::getInstance();
  
  $g_id = $_GET['group_id'];
 
  // paging info
  $limit = 12;
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  
  $flix = $g->flixContributions($_USER_ID, $g_id, ($currentPage-1)*$limit, $limit);
  $totalRows = $GLOBALS['dbh']->found_rows();
  $pagesToDisplay = 15;
  $totalPages = ceil($totalRows/$limit);
  
  $pg  =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
?>

<div style="padding-top:10px; padding-left:15px;">
  <div style="text-align:left;" class="f_8 bold">My Flix Contributions</div>
</div>

<?php  
  echo '<div style="padding-left:15px;" id="_flix">';
  
  foreach($flix as $v)
  {
    $sizeArr    = explode('x', $v['A_SIZE']);
    $containerWidth = $sizeArr[0];
    $containerHeight = $sizeArr[1];
    $foto_id = $v['A_DATA'][0]['D_UP_ID'];
    $foto_data = $fb->fotoData($foto_id);
    $swf_src = '/swf/flix_theme/layout_small/small_' . substr($v['A_TEMPLATE'], 1) . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $v['A_FASTFLIX'] . '&containerWidth=' . $containerWidth . '&containerHeight=' . $containerHeight;
    $swf_id  = 'ff_' . $v['A_ID'];
                    
    echo '<div style="float:left; padding-top:25px; padding-right:40px; text-align:left;" id="_flix_' . $v['A_FASTFLIX'] . '">';
      echo '<div class="flix_border"><a href="/fastflix_popup?fastflix=' . $v['A_FASTFLIX'] . '" onclick="_open(this.href, ' . $containerWidth . ', ' . $containerHeight . '); return false;"><img src="' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '" border="0" /></a></div>';
      echo '<div style="width:110px; clear:both;" align="left">' . str_mid($v['A_NAME'], 15) . '</div>';
      echo '<div style="padding-left:10px;"><a href="/?action=fotogroup.flix_contributions_unshare.act&ids=' . $v['A_ID'] . '&group_id=' . $group_id . '" class="f_7">Unshare flix</a></div>';
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