<?php
  set_time_limit(0); // no time limit
  include_once dirname(__FILE__) . '/../init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_CLASS . '/CReport.php';
  
  $report =& CReport::getInstance();
  //$freq = $argv[2];
  
  $proUsers = $GLOBALS['dbh']->query_all("SELECT * FROM users WHERE u_accountType = '" . PERM_USER_1 . "' AND u_dateExpires > NOW() AND u_status = 'active'");
  $reportTypes    = $report->getTypes();
  $types = $dates = array();
  
  foreach($reportTypes as $v)
  {
    $types[] = $v['R_ID'];
  }
  $frequencies = array('Monthly');
  
  foreach($proUsers as $user)
  {
    foreach($types as $type)
    {
      foreach($frequencies as $freq)
      {
        $report->SetNewReport($user['u_id'], $type, $freq); // updates (does nothing) if exists
      }
    }
  }
  
  $reports = $report->ActiveReportRules();
  
  foreach( $reports as $v )
  {
    generateReport($report, $v );
  }
  
  
  
  function generateReport( $report, $arr )
  {
    $useTime = strtotime('-1 month');
    $startDate = mktime(0, 0, 0, date('m', $useTime), 1, date('Y', $useTime)); // date('Y-m-01 00:00:00', $useTime);
    $endDate = mktime(0, 0, 0, date('m', $useTime), date('t', $useTime), date('Y', $useTime));
    
    // limit to the top x for the top of the report and the graph
    $limit = 12;
    
    // depending on the type ('fotos report', 'flix report', etc) and the frequency get the information
    echo 'Generating ' . $arr['R_ID'] . "\n";
    switch( $arr['R_TYPE'] )
    {
      case 'Photos Report':
        
        $type = $arr['R_TYPE'];
        
        if( $arr['R_FREQUENCY'] == 'Weekly' )
        {
          $f = $report->fotoReport($arr['R_UID'], 'Photo Viewed', array(strtotime("-1 week"), NOW), $limit);
          $all = $report->fotoReport($arr['R_UID'], 'Photo Viewed', array(strtotime("-1 week"), NOW));
          $dates[0] = strtotime("-1 week");
          $dates[1] = NOW;
        }
        else if( $arr['R_FREQUENCY'] == 'Monthly' )
        {
          $f = $report->fotoReport($arr['R_UID'], 'Photo Viewed', array($startDate, $endDate), $limit);
          $all = $report->fotoReport($arr['R_UID'], 'Photo Viewed', array($startDate, $endDate));
          $dates[0] = strtotime("-1 month");
          $dates[1] = NOW;
        }
        
        $resultArray = array(
                          'TOP' => $f,
                          'ALL' => $all,
                          'DATES' => array($dates[0], $dates[1])
                        );
        $displayType = 'Photo Report';
        break;
        
      case 'Slideshow Report':
        $type = $arr['R_TYPE'];
        
        if( $arr['R_FREQUENCY'] == 'Weekly' )
        {
          include_once PATH_CLASS . '/CFlix.php';
          include_once PATH_CLASS . '/CFotobox.php';
          $fl = &CFlix::getInstance();
          $fb = &CFotobox::getInstance();
          
          $f = $report->flixReport($arr['R_UID'], 'Slideshow Viewed', array(strtotime("-1 week"), NOW), $limit);
          $fc = $report->flixReport($arr['R_UID'], 'Slideshow Viewed Complete', array(strtotime("-1 week"), NOW), $limit);
          $all = $report->flixReport($arr['R_UID'], 'Slideshow Viewed', array(strtotime("-1 week"), NOW));
          $all2 = $report->flixReport($arr['R_UID'], 'Slideshow Viewed Complete', array(strtotime("-1 week"), NOW));
          $dates[0] = strtotime("-1 week");
          $dates[1] = NOW;
        }
        else if( $arr['R_FREQUENCY'] == 'Monthly' )
        {
          include_once PATH_CLASS . '/CFlix.php';
          include_once PATH_CLASS . '/CFotobox.php';
          $fl = &CFlix::getInstance();
          $fb = &CFotobox::getInstance();
          
          $f = $report->flixReport($arr['R_UID'], 'Slideshow Viewed', array($startDate, $endDate), $limit);
          $fc = $report->flixReport($arr['R_UID'], 'Slideshow Viewed Complete', array($startDate, $endDate), $limit);
          $all = $report->flixReport($arr['R_UID'], 'Slideshow Viewed', array($startDate, $endDate));
          $all2 = $report->flixReport($arr['R_UID'], 'Slideshow Viewed Complete', array($startDate, $endDate));
          $dates[0] = $startDate;
          $dates[1] = $endDate;
        }
        
        $resultArray = array(
                          'TOP' => $f,
                          'TOP_COMPLETE' => $fc,
                          'ALL' => $all,
                          'ALL_COMPLETE' => $all2,
                          'DATES' => array($dates[0], $dates[1])
                        );
        
        $displayType = 'Slideshow Report';
        break;
    }
    
    // save the report in the archive
    $reportId = $report->ArchiveReport($arr['R_UID'], $arr['R_ID'], $arr['R_FREQUENCY'] . ' Report for ' . date('M d, Y', $dates[0]) . ' through ' . date('M d, Y', $dates[1]), jsonEncode($resultArray));
    
    // send email if needed
    /*if( $arr['R_EMAIL'] != '' )
    {
      $newReportData = $report->getReportArchive($reportId);
      include_once PATH_CLASS . '/CMail.php';
      $cm =& CMail::getInstance();
    
      $emailArr = split(',', $arr['R_EMAIL']);
      foreach( $emailArr as $v )
      {
        $to .= $v . ',';
      }
      $subject = $arr['R_FREQUENCY'] . ' ' . $displayType . ' (' . date('M d', $dates[0]) . ' through ' . date('M d', $dates[1]) . ')';
      
      $md5 = md5($arr['R_ID'] . NOW);
      $filename = '/' . substr($md5, 0, 2) . '/' . $md5 . '.html';
      //$message = '<html><head><title>Your Report</title></head><body><a href="/reports' . $filename . '">Your Report</a></body>';
      //$message = file_get_contents($url = 'http://' . FF_SERVER_NAME . '/report?' . $newReportData['RA_KEY']);
      $reportUrl = 'http://' . FF_SERVER_NAME . '/report?' . $newReportData['RA_KEY'];
      $message  = 'You have a new report waiting to be viewed.  Click the link to view your <a href="' . $reportUrl . '">' . $subject . '</a> or copy and paste the URL below into your web browser.
                  <br/><br/>
                  ' . $reportUrl . '
                  <br/><br/>
                  The Photagious Team';
      
      $from     = FF_EMAIL_FROM_FORMATTED;
      $headers  = "MIME-Version: 1.0\r\n"
                . "Content-type: text/html; charset=iso-8859-1\r\n"
                . "Return-Path: {$from}\r\n"
                . "From: {$from}\r\n"
                . "Reply-To: " . FF_EMAIL_FROM . "\r\n";
      
      $cm->send( $to, $subject, $message, $headers );
    }*/
  }

  
  
  function flixCompletedViews( $needle, $haystack )
  {
    $found = false;
    foreach( $haystack as $k => $v )
    {
      if( $needle === $v['RD_ELEMENT'] )
      {
        $found = $v['RD_COUNT'];
        break;
      }
    }
    
    if( $found === false )
    {
      return 0;
    }
    else 
    {
      return $found;
    }
  }
?>