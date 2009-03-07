<?php
  // delete this file - now in xml_result
  $b =& CBlog::getInstance();
  
  $entryId = $_GET['entryId'];
  $b->delete($_USER_ID, $entryId);
?>