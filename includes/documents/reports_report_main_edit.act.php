<?php
  $r = &CReport::getInstance();
  
  $r_id = $_POST['id'];
  $typeId = $_POST['type'];
  $freq = $_POST['frequency'];
  $emails = $_POST['emails'];
  $type = $r->GetType($typeId);
  
  $r->UpdateReport($r_id, $typeId, $freq, $emails);
  
  $url = '/?action=reports.report_main';
?>