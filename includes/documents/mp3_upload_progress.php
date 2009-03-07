<?
// --------------------------------
// Set upload start time
// This is set once as the point in time at which the upload was started
// Elapsed time, est remaining time, and upload speed need this

  $dtstart = time();
// --------------------------------

  $iTotal = urlencode($_REQUEST['iTotal']);
  $iRead = urlencode($_REQUEST['iRead']);
  $iStatus = urlencode($_REQUEST['iStatus']);
  $sessionId = urlencode($_REQUEST['sessionid']);
  
// --------------------------------
// Set current time
// This is set on each refresh to measure elapsed time
// Elapsed time, est remaining time, and upload speed need this
  //$dtnow = urlencode($_REQUEST['dtnow']);
  $dtnow = $dtstart;
// --------------------------------

// --------------------------------
// This is modified to include the new vars ($dtstart, $dtnow) introduced in this file
  $link = ('/cgi-bin/progress.cgi?iTotal=' . $iTotal . '&iRead=' . $iRead . '&iStatus=' . $iStatus . '&sessionid=' . $sessionId . '&dtnow=' . $dtnow . '&dtstart=' . $dtstart);
// --------------------------------
?>