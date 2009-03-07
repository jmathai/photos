<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  ob_start();
  include_once $path = str_replace('scripts', '', dirname(__FILE__)) . 'init_constants.php';
  
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CSubscription.php';
  include_once PATH_CLASS . '/CMail.php';
  
  $s =& CSubscription::getInstance();
  $m =& CMail::getInstance();
  
  $time = mktime(0, 0, 0, date('m', NOW), date('d', NOW), date('Y', NOW));
  $time -= 86400;
  
  $headers = "MIME-Version: 1.0\n"
           . "Content-type: text/html; charset=iso-8859-1\n"
           . 'Return-Path: ' . FF_EMAIL_FROM_FORMATTED . "\n"
           . 'From: ' . FF_EMAIL_FROM_FORMATTED;
  $subscriptionData = $s->getSubscriptionsForReport($time);
  
  $template = file_get_contents(PATH_DOCROOT . '/subscriptions_email.tpl.php');
  
  //print_r($subscriptionData);
  
  $previousEmail = '';
  
  foreach($subscriptionData as $v1)
  {
    $emails = array(); // create a blank email array to hold all email addresses for this subscription entry
    $htmlGenerated = false; // initialize the htmlGenerated variable to false
    $eKey = 0; // initialize an email key
    $slideshowHtml = $photoHtml = $previousEmail = '';
    foreach($v1 as $v2)
    {
      if($previousEmail != $v2['S_EMAIL'])
      {
        $emails[$eKey]['EMAIL'] = $v2['S_EMAIL'];
        $emails[$eKey]['KEY'] = $v2['S_KEY'];
        switch($v2['S_METHOD'])
        {
          case 'pull':
            $emails[$eKey]['INTRO'] = 'You requested';
            $emails[$eKey]['USERNAME'] = $v2['S_USERNAME'];
            break;
          case 'push':
          default:
            $emails[$eKey]['INTRO'] = $v2['S_USERNAME'] . ' wanted you';
            $emails[$eKey]['USERNAME'] = 'they';
            break;
        }
        
        $eKey++;
      }
      
      if(count($emails) > 1)
      {
        $htmlGenerated = true;
      }
      
      if($htmlGenerated === false)
      {
        switch($v2['S_ELEMENTTYPE'])
        {
          case 'Photo_Public':
            $photoHtml .= '<a href="http://' . FF_SERVER_NAME . '/users/' . $v2['S_USERNAME'] . '/photo/' . $v2['S_ELEMENTID'] . '/"><img ' . "\n" . ' src="http://' . FF_SERVER_NAME . PATH_FOTO . $v2['S_THUMBNAIL'] . '"' . "\n" . ' width="75" height="75" hspace="10" vspace="10" border="0" /></a>';
            break;
          case 'Slideshow_Public':
            $slideshowHtml .= '<div class="flix_border"><a href="http://' . FF_SERVER_NAME . '/slideshow?' . $v2['S_ELEMENTID'] . '"><img ' . "\n" . ' src="http://' . FF_SERVER_NAME . PATH_FOTO . $v2['S_THUMBNAIL'] . '"' . "\n" . ' width="75" height="75" border="0" /></a></div>';
            break;
        }
      }
      
      $previousEmail = $v2['S_EMAIL'];
    }
    
    if($photoHtml == '')
    {
      $photoHtml = ''; //'<div class="bold">There are no new photos to see.</div>';
    }
    else
    {
      $photoHtml = '<div class="bold">New Photos</div>' . $photoHtml;
    }
    
    if($slideshowHtml == '')
    {
      $slideshowHtml = ''; //'<div class="bold">There are no new slideshows to see.</div>';
    }
    else
    {
      $slideshowHtml = '<div class="bold">New Slideshows</div>' . $slideshowHtml;
    }
    
    foreach($emails as $email)
    {
      if(strstr($email['EMAIL'], '@'))
      {
        $templateHtml = str_replace(array('{USERNAME}','{INTRO}','{PHOTOS}','{SLIDESHOWS}','{SERVERNAME}','{KEY}'), array($email['USERNAME'],$email['INTRO'],$photoHtml,$slideshowHtml,FF_SERVER_NAME,$email['KEY']), $template);
        $subject = $v2['S_USERNAME'] . ' has new photos/slideshows on their Personal Page';
        echo "\n*email {$email['EMAIL']}";
        $m->send($email['EMAIL'], $subject, $templateHtml, $headers);
      }
    }
  }
?>