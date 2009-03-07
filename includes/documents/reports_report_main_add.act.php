<?php
  $r = &CReport::getInstance();
  
  $typeId = $_POST['type'];
  $freq = $_POST['frequency'];
  $emails = $_POST['emails'];
  $type = $r->GetType($typeId);
  
  $r_id = $r->SetNewReport($_USER_ID, $typeId, $freq, $emails);
  
  $url = '/?action=reports.report_main';
?>