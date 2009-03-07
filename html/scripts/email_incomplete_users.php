<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  ob_start();
  include_once $path = str_replace('scripts', '', dirname(__FILE__)) . 'init_constants.php';
  
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/variables.php';
  include_once PATH_CLASS . '/CSubscription.php';
  include_once PATH_CLASS . '/CMail.php';
  
  $s =& CSubscription::getInstance();
  $m =& CMail::getInstance();
  
  $time = mktime(0, 0, 0, date('m', NOW), date('d', NOW)-1, date('Y', NOW));
  $sTime = $time - 86400;
  $hour = date('H', NOW) - 1;
  $sHour=$hour+1;
  
  $headers = "MIME-Version: 1.0\n"
           . "Content-type: text/html; charset=iso-8859-1\n"
           . 'Return-Path: ' . FF_EMAIL_FROM_FORMATTED . "\n"
           . 'From: ' . FF_EMAIL_FROM_FORMATTED;
  
  $yesterday = " BETWEEN '" . date("Y-m-d {$sHour}:00:00", $sTime) . "' AND '" . date("Y-m-d {$hour}:59:59", $time) . "' ";
  $incompleteUsers = $GLOBALS['dbh']->query_all($sql = "SELECT * FROM user_incompletes WHERE u_dateCreated {$yesterday}");
  echo $sql . "\n\n";
  
  $template = file_get_contents(PATH_DOCROOT . '/account_incomplete_user.tpl.php');
  $listElements = '';
  foreach($incompleteResponses as $k => $v)
  {
    $listElements .= '<li><a href="http://' . FF_SERVER_NAME . '/?action=account.incomplete_user_response.act&key={KEY}&response=' . $k . '">' . $v . '</a></li>';
  }
  $template = str_replace('{LI}', $listElements, $template);
  
  foreach($incompleteUsers as $v)
  {
    if(strstr($v['u_email'], '@'))
    {
      $m->send($v['u_email'], 'Complete your registration on Photagious', str_replace(array('{GREETING}','{KEY}','{SERVERNAME}'), array($v['u_username'],$v['u_key'],FF_SERVER_NAME), $template), $headers, FF_EMAIL_FROM_FORMATTED);
      echo 'send email to ' . $v['u_email'] . "\n";
    }
  }
?>