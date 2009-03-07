<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>

<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="./css/styles.css">
<link rel="icon" href="/favicon.ico" type="image/x-icon">

<script type="text/javascript" src="/js/prototype/prototype.js"></script>
<script type="text/javascript" src="/js/prototype/dragdrop.js"></script>
<script type="text/javascript" src="/js/prototype/effects.js"></script>
<script type="text/javascript" src="/js/prototype/util.js"></script>
<script type="text/javascript" src="/js/prototype/extensions.js"></script>
<script type="text/javascript" src="/js/prototype/controls.js"></script>
<script type="text/javascript" src="/js/cp.js"></script>
<script type="text/javascript" src="/js/http.js"></script>

</head>

<body>

<div style="width:980px; margin:auto;">
  <div class="bold" style="padding:3px 5px 3px 5px; border-left:solid 1px #dddddd; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left; <?php if(strncmp($action, 'stats.', 6) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=stats.home">Site Stats</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-left:solid 1px #dddddd; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left; <?php if(strncmp($action, 'users.', 6) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=users.search_form">Users</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'flix.search', 11) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=flix.search_form">Flix</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'flix.themes', 11) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=flix.themes">Themes</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'flix.hotspots', 13) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=flix.hotspots">Hotspots</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'music.', 6) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=music.home">Music</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'fotos.', 6) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=fotos.public_fotos">Public Fotos</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'phpinfo.', 8) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=phpinfo.home">PHP Info</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'eaccelerator.', 13) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=eaccelerator.home">EAccelerator</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'quarantined.', 12) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=quarantined.home">Quarantined Fotos</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'email.', 6) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=email.home">Email System</a>
  </div>
  <div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'md5.', 4) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=md5.home">MD5</a>
  </div>

	<div class="bold" style="padding:3px 5px 3px 5px; border-left:solid 1px #dddddd; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'paying_users.', 13) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=paying_users.home">Paying</a>
  </div>
	<div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'trial_users.', 12) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=trial_users.home">Trial Cancelled</a>
  </div>
	<div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'incomplete_users.', 17) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=incomplete_users.home">Incomplete</a>
  </div>
	<div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'cancelled_users.', 16) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=cancelled_users.home">Cancelled</a>
  </div>
	<div class="bold" style="padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;  <?php if(strncmp($action, 'cancelled_users.', 16) == 0){ echo 'background-color:#f9f6c7;'; } ?>">
    <a href="./?action=faq.home">FAQ</a>
  </div>
	<div class="bold" style=" width:580px; padding:3px 5px 3px 5px; border-top:solid 1px #dddddd; float:left;"></div>

  <!--<div class="bold" style="padding:3px 10px 3px 10px; border-top:solid 1px #dddddd; border-right:solid 1px #dddddd; float:left;">
    Users
  </div>-->
</div>
<br clear="all" />
<div id="container" style="width:980px; border:solid 1px #dddddd; margin:auto;">
  <div style="width:90%; margin:auto;">