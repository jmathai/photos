<?php
  $report = CReport::getInstance();
  $type = $report->Types();
  $typeArray = array();
  $frequency = array('Weekly' => 0, 'Monthly' => 1);
  
  $i = 1;
  foreach( $type as $k => $v )
  {
    $typeArray[$i] = $v['RT_NAME'];
    $i++;
  }
  
  $t = $_GET['type'];
  $f = $_GET['freq'];
  
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  $limit = 10;
  $offset = ($currentPage-1)*$limit;
  $archive = $report->Reports($_USER_ID, $t, $f, $limit, $offset);
  
  // paging info
  $totalRows = $GLOBALS['dbh']->found_rows();
  $pagesToDisplay = 6;
  $totalPages = ceil($totalRows/$limit);

  $paging =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
  
  $pages = $paging->getPages();
  $firstPage = $paging->getFirstPage('First');
  $lastPage = $paging->getLastPage('Last');
  $nextPage = $paging->getNextPage('Next');
  $prevPage = $paging->getPrevPage('Previous');
?>

<div style="padding-left:10px; width:545px; text-align:left;" id="_archives">
  <div class="f_12 bold">Archived Reports</div>
  <br />
  
<?php
  echo '<div id="' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . '" style="float:left; padding-top:20px; padding-right:20px;" class="f_10 bold"><a href="javascript:toggleDiv(\'' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . ' - divStart\');">' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . '</a></div>';
  echo '<div style="padding-top:22px;">Pages: ' . $firstPage . ' ' . $pages . ' ' . $lastPage . '</div>';
  echo '<br />';
  echo '<div id="' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . ' - divStart" style="display:block;">'; // starts the div for all the reports in this category
        
  foreach( $archive as $k => $v )
  {
    echo '<div style="padding-top:5px; padding-left:20px;">';
    echo '<div style="float:left; width:400px;"><a href="/reports' . $v['RA_FILENAME'] . '" target="_blank">' . $v['RA_TITLE'] . '</a></div>';
    echo '</div>';
    echo '<br />';
  }
 
  echo '</div>';
?>

</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>