<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  
  <title><?php echo (isset($title) ? $title : 'Photagious - Online Photo Slideshow Software - Create Flash Slideshows'); ?></title>
  <base href="<?php echo ($_SERVER['SERVER_PORT'] == 80 ? 'http' : 'https') . '://' . FF_SERVER_NAME . '/'; ?>" />
  <link rel="icon" href="/favicon.ico" type="image/x-icon" />
  <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
  <meta http-equiv="imagetoolbar" content="no" />
  <meta name="description" content=" Photagious :: Photo slideshow software by photo enthusiasts for photo enthusiasts.  Create flash slideshows quickly and easily, store and manage photos and share you photos online.">
  <meta name="keywords" content="photo, slideshow, flash, create, software">
  <script type="text/javascript" src="/js/prototype/prototype.js"></script>
  <script type="text/javascript" src="/js/prototype/moo.fx.js"></script>
  <script type="text/javascript" src="/js/javascript.js"></script>
  <script type="text/javascript" src="/js/http.js"></script>
  
  <?php
    if(strlen($key) == 32) // for the slideshow page to delete comments
    {
      echo '<script type="text/javascript" src="/js/photopage.js"></script>';
    }

    if(FF_MODE == 'live')
    {
      if($_SERVER['SERVER_PORT'] == '80')
      {
        echo '<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
              <script type="text/javascript">
                _uacct = "UA-88708-1";
                urchinTracker();
              </script>';
      }
      else
      {
        echo '<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript"></script>
              <script type="text/javascript">
                _uacct = "UA-88708-1";
                urchinTracker();
              </script>';
      }
    }
    
    switch($action)
    {
      case 'flix_manage_public':
      case 'mypage_flix_config_public':
        echo '<script type="text/javascript" src="/js/prototype/effects.js"></script>
              <script type="text/javascript" src="/js/prototype/dragdrop.js"></script>';
        break;
    }
  ?>
    
  <script language="javascript">
    var _USER_ID = <?php echo intval(isset($_USER_ID) ? $_USER_ID : 0); ?>;
  </script>
  
  <link rel="stylesheet" type="text/css" href="/css/layout.css">
  <link rel="stylesheet" type="text/css" href="/css/basic.css">
  
</head>
<body bgcolor="<?php echo (isset($bgcolor) ? $bgcolor : '#ffffff'); ?>" <?php if(isset($background)){ echo 'background="' . $background . '"'; } ?>>
<div class="body_basic">
