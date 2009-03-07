<?php
  $pm = &CPrivateMessage::getInstance();
  $hasOptedOut = $pm->hasOptedOut($_USER_ID);
  
  if( $hasOptedOut === true )
  {
    echo '<div class="f_12 bold">Your inbox is not available because you have disabled private messaging.  <a href="javascript:void(pm_optIn());">Enable private messages</a></div>';
  }
  else 
  {
    $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
    $limit = 15;
    $offset = ($currentPage-1)*$limit;
    $messages = $pm->getSentMessages($_USER_ID, $limit, $offset);
    
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
  
    <div style="text-align:left; padding-top:25px; padding-left:25px;">
      <div class="f_10 bold">Outbox</div>
      <div style="padding-top:25x; padding-right:15px; text-align:right;"><?php echo $firstPage . ' ' . $prevPage . ' ' . $pages . ' ' . $nextPage . ' ' . $lastPage; ?></div>
      <div style="padding-top:25px;" class="f_8 bold">
        <div style="float:left; width:45%; border:solid gray 1px; padding-left:5px;">Subject</div>
        <div style="float:left; width:25%; border:solid gray 1px; text-align:center;">Sent To</div>
        <div style="float:left; width:25%; border:solid gray 1px; text-align:center;">Date Sent</div>
      </div>
      <div>
        <?php
          foreach($messages as $k => $v)
          {
            echo '<div style="padding-top:25px;" class="f_8">';
              if($v['PM_STATUS'] == 'New')
              {
                echo '<div style="float:left; width:2%; text-align: center;" class="f_7">N</div>';
              }
              echo '<div style="float:left; width:45%; padding-left:5px;"><a href="/?action=pm.message&type=sent&id=' . $v['PM_ID'] . '">' . $v['PM_SUBJECT'] . '</a></div>';
              echo '<div style="float:left; width:25%; text-align:center;">' . $v['U_RECEIVER_USERNAME'] . '</div>';
              echo '<div style="float:left; width:25%; text-align:center;">' . date('M d, Y', $v['PM_DATECREATED']) . '</div>';
            echo '</div>';
          }
        ?>
      </div>
    </div>
<?php
  }
?>
<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>