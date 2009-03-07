<?php
  $r = &CReport::getInstance();
  
  $type = isset($_GET['type']) ? $_GET['type'] : 2; // 1 - slideshow, 2 - photo
  if($type < 1 || $type > 2)
  {
    $type = 2;
  }
  
  $frequency = 'Monthly';
  
  $reports = $r->Reports($_USER_ID, $type, $frequency);

  echo '<div style="margin-top:25px; margin-left:50px;">';
  
  switch($type)
  {
    case 1:
      echo '<div style="padding-bottom:25px;" class="f_12 bold"><img src="images/icons/view_24x24.png" class="png" width="24" height="24" border="0" hspace="2" /><span style="padding-left:5px;">Slideshow Reports</span></div>';
      break;
    case 2:
      echo '<div style="padding-bottom:25px;" class="f_12 bold"><img src="images/icons/view_24x24.png" class="png" width="24" height="24" border="0" hspace="2" /><span style="padding-left:5px;">Photo Reports</span></div>';
      break;
  }
  
  $date = date('n Y', NOW);
  echo '<div style="padding-bottom:10px; padding-left:8px; margin-left:15px;" class="bullet">' . date('F Y', NOW) . ' (in progress)</div>';
  
  $reportTime = NOW;
  foreach($reports as $k => $v)
  {
    $reportTime = strtotime('-1 month', $reportTime);
    echo '<div style="padding-bottom:10px; padding-left:8px; margin-left:15px;" class="bullet"><a href="/report?' . $v['RA_KEY'] . '" target="_blank" title="view this report">' . date('F Y', $reportTime) . '</a></div>';
  }
  
  echo '</div>';
?>