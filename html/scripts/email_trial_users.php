<?php
  /*
  * This file was once used to extend free trial users.
  * This file now extends trial users on Photagious
  */
  ini_set('max_execution_time', 0);
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  
  chdir(dirname(__FILE__));
  ob_start();
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CMail.php';
  include_once PATH_CLASS . '/CUserManage.php';
  
  $m =& CMail::getInstance();
  $um=& CUserManage::getInstance();
  
  $usernames = "'jmathai','vree','uckevin111'";
  
  $bodyWith = file_get_contents(PATH_DOCROOT . '/email_trial_users_with_photos.tpl.php');
    $titleWith = 'How are we doing?';
  $bodyWithout = file_get_contents(PATH_DOCROOT . '/email_trial_users_no_photos.tpl.php');
    $titleWithout = 'Start uploading photos to Photagious';
  $bodyExtend =  file_get_contents(PATH_DOCROOT . '/email_trial_users_extend.tpl.php');
    $titleExtend = 'More time to use Photagious';
  $bodyPurchase =  file_get_contents(PATH_DOCROOT . '/email_trial_users_purchase.tpl.php');
    $titlePurchase = 'How was your trial at Photagious?';
  
  $headers = "MIME-Version: 1.0\n"
           . "Content-type: text/html; charset=iso-8859-1\n"
           . 'Return-Path: ' . FF_EMAIL_FROM_FORMATTED . "\n"
           . 'From: ' . FF_EMAIL_FROM_FORMATTED;
  
  echo "\n*** " . date(FF_FORMAT_DATE_LONG, NOW) . " ***\n";

  /***********************************************************************************************/
  
  // day 3 - send email
  $sql = "SELECT *, COUNT(up_u_id) AS _CNT FROM users LEFT JOIN user_fotos ON u_id = up_u_id WHERE DATEDIFF(NOW(), u_dateCreated) = 2 AND u_isTrial = " . USER_IS_TRIAL . " AND u_status = 'active' GROUP BY u_id";
  //$sql = "SELECT *, '0' _CNT FROM users WHERE u_username IN({$usernames})";
  $users = $GLOBALS['dbh']->query_all($sql);
  foreach($users as $v)
  {
    if($v['_CNT'] > 0) // user has uploaded photos
    {
      $body = str_replace(array('{SERVER_NAME}','{USERNAME}'), array(FF_SERVER_NAME, $v['u_username']), $bodyWith);
      $m->send($v['u_email'], $titleWith, $body, $headers, FF_EMAIL_FROM_FORMATTED);
      echo "Sent email (with photos) to {$v['u_username']} ({$v['u_email']})\n";
    }
    else // user has not uploaded photos
    {
      $body = str_replace(array('{SERVER_NAME}','{USERNAME}'), array(FF_SERVER_NAME, $v['u_username']), $bodyWithout);
      $m->send($v['u_email'], $titleWithout, $body, $headers, FF_EMAIL_FROM_FORMATTED);
      echo "Sent email (no photos) to {$v['u_username']} ({$v['u_email']})\n";
    }
  }
  
  /***********************************************************************************************/
  
  // day 6 has photos - send email to buy
  $sql = "SELECT * FROM users WHERE u_spaceUsed IS NOT NULL AND DATEDIFF(NOW(), u_dateCreated) = 5 AND u_isTrial = " . USER_IS_TRIAL . " AND u_status = 'active'";
  //$sql = "SELECT *, '10' _CNT FROM users WHERE u_username IN({$usernames})";
  $users = $GLOBALS['dbh']->query_all($sql);
  foreach($users as $v)
  {
    $body = str_replace(array('{SERVER_NAME}','{USERNAME}'), array(FF_SERVER_NAME, $v['u_username']), $bodyPurchase);
    $m->send($v['u_email'], $titlePurchase, $body, $headers, FF_EMAIL_FROM_FORMATTED);
    echo "Sent email (purchase on day 6) to {$v['u_username']} ({$v['u_email']})\n";
  }
  
  /***********************************************************************************************/
  
  // day 7 does not have photos - extend and send email
  $sql = "SELECT * FROM users WHERE  u_spaceUsed IS NULL AND DATEDIFF(NOW(), u_dateCreated) = 6 AND u_isTrial = " . USER_IS_TRIAL . " AND u_status = 'active'";
  //$sql = "SELECT *, '10' _CNT FROM users WHERE u_username IN({$usernames})";
  $users = $GLOBALS['dbh']->query_all($sql);
  $extension = strtotime('+7 days', NOW);
  foreach($users as $v)
  {
    $um->update(array('u_id' => $v['u_id'], 'u_dateExpires' => date('Y-m-d', $extension)));
    $body = str_replace(array('{SERVER_NAME}','{USERNAME}','{NEW_TRIAL_END}'), array(FF_SERVER_NAME, $v['u_username'], date('l, F jS', $extension)), $bodyExtend);
    $m->send($v['u_email'], $titleExtend, $body, $headers, FF_EMAIL_FROM_FORMATTED);
    echo "Sent email (extended account on day 7) to {$v['u_username']} ({$v['u_email']})\n";
  }
  
  /***********************************************************************************************/
  
  // day 14 - send email to buy
  $sql = "SELECT * FROM users WHERE DATEDIFF(NOW(), u_dateCreated) = 13 AND u_isTrial = " . USER_IS_TRIAL . " AND u_status = 'active'";
  //$sql = "SELECT *, '10' _CNT FROM users WHERE u_username IN({$usernames})";
  $users = $GLOBALS['dbh']->query_all($sql);
  foreach($users as $v)
  {
    $body = str_replace(array('{SERVER_NAME}','{USERNAME}'), array(FF_SERVER_NAME, $v['u_username']), $bodyPurchase);
    $m->send($v['u_email'], $titlePurchase, $body, $headers, FF_EMAIL_FROM_FORMATTED);
    echo "Sent email (purchase on day 14) to {$v['u_username']} ({$v['u_email']})\n";
  }
  
  ob_end_flush();
?>