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
?>

<div style="padding-left:10px; width:545px; text-align:left;" id="_archives">
  <div class="f_12 bold">Archived Reports</div>
  <br />
  <?php
    $limit = 5;
    foreach( $typeArray as $k => $v )
    {
      foreach( $frequency as $k2 => $v2 )
      {
        $archive = $report->Reports($_USER_ID, $k, $k2, $limit);
        
        if( $archive != null )
        {
          echo '<div id="' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . '" style="float:left; padding-top:20px; padding-right:20px;" class="f_10 bold"><a href="javascript:toggleDiv(\'' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . ' - divStart\');">' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . '</a></div>';
          echo '<div style="padding-top:22px;"><a href="/?action=reports.report_archive_all&type=' . $archive[0]['R_TYPE'] . '&freq=' . $archive[0]['R_FREQUENCY'] . '">View All</a></div>';
          echo '<br />';
          echo '<div id="' . $typeArray[$archive[0]['R_TYPE']] . ' - ' . $archive[0]['R_FREQUENCY'] . ' - divStart" style="display:block;">'; // starts the div for all the reports in this category
          
          foreach( $archive as $k3 => $v3 )
          {
            echo '<div style="padding-top:5px; padding-left:20px;">';
            echo '<div style="float:left; width:400px;"><a href="/reports' . $v3['RA_FILENAME'] . '" target="_blank">' . $v3['RA_TITLE'] . '</a></div>';
            echo '</div>';
            echo '<br />';
          }
          
          echo '</div>'; // ends the div for all the reports in this category
        }
      }
    }
  ?>
</div>

<script language="javascript">
  function toggleDiv(divId)
  {
    if( document.getElementById(divId).style.display == 'block' )
    {
      document.getElementById(divId).style.display = 'none';
    }
    else
    {
      document.getElementById(divId).style.display = 'block';
    }
  }
</script>
<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>