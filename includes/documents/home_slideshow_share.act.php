<?php
  $baseUrl = 'http://' . FF_SERVER_NAME;
  if(true || strncmp($_SERVER['HTTP_REFERER'], $baseUrl, strlen($baseUrl)) == 0) // check if referer is from same domain
  {
    $mail = &CMail::getInstance();
    
    foreach($_POST as $k => $v)
    {
      $_POST[$k] = htmlspecialchars($v);
    }
    
    $to = $_POST['to'];
    $from = $_POST['from'];
    $subject = empty($_POST['subject']) ? 'A slideshow from Photagious' : $_POST['subject'];
    $message = str_replace(
                  array('{USERNAME}','{MESSAGE}','{URL}'), 
                  array($_POST['from'], $_POST['message'], 'http://' . FF_SERVER_NAME . '/slideshow?' . $_POST['key']), 
                  file_get_contents(PATH_DOCROOT . '/home_slideshow_share.tpl.php')
                );
    $headers = "MIME-Version: 1.0\n"
             . "Content-type: text/plain; charset=iso-8859-1\n"
             . 'Return-Path: ' . $from . "\n"
             . 'From: ' . $from;
              
    $toArray = (array)explode(',', $to);
    
    foreach($toArray as $v)
    {
      $mail->send($v, $subject, $message, $headers, $from);
    }
    
    if(isset($_POST['redirect']))
    {
      $url = '/?action=home.slideshow_share&KEY=' . $_POST['key'] . '&confirmation=1';
    }
    else
    if(isset($_POST['flash']))
    {
      $output = '1';
    }
    else
    {
      $url = '/';
    }
  }
?>