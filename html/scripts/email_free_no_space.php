<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_CLASS . '/CMail.php';
  
  $m =& Cmail::getInstance();
  
  $campaignName = 'email_free_no_space';
  $spaceLimit   = 3 * KB;
  
  $template = file_get_contents(PATH_DOCROOT . '/email_free_no_space.tpl.php');
  $expiry = date(FF_FORMAT_DATE_LONG, mktime(0, 0, 0, date('m', NOW), date('d', NOW)+3, date('Y', NOW)));
  $threshold = date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m', NOW), date('d', NOW)-3, date('Y', NOW)));
  
  $campaign = $GLOBALS['dbh']->query_first("SELECT * FROM email_campaigns WHERE ec_name = '{$campaignName}'");
  $campaignId = $campaign['ec_id'];
  
  $sql  = 'SELECT * '
        . 'FROM (users AS u LEFT JOIN email_campaign_tracker AS ect ON u.u_id = ect.ect_u_id) '
        . 'LEFT JOIN email_unsubscribe AS eu ON u.u_id = eu.eu_u_id '
        . "WHERE ect.ect_u_id IS NULL AND eu.eu_u_id IS NULL AND u.u_status = 'Active' AND u.u_accountType = 'premium_trial' AND u.u_dateExpires > NOW() AND (u.u_spaceTotal - u.u_spaceUsed) < {$spaceLimit} AND u.u_dateCreated BETWEEN '2005-06-01 00:00:00' AND '{$threshold}'";
  /*$sql  = 'SELECT * '
        . 'FROM users AS u LEFT JOIN email_campaign_tracker AS ect ON u.u_id = ect.ect_u_id '
        . "WHERE (u.u_username = 'vree' OR u.u_username = 'jmathai')";*/
  
  //echo $sql;
  $users = $GLOBALS['dbh']->query_all($sql);
  
  $from     = FF_EMAIL_FROM_FORMATTED;
  $from_email = FF_EMAIL_FROM;
  $mail_headers   = "MIME-Version: 1.0\n"
                  . "Content-type: text/plain; charset=iso-8859-1\n"
                  . "Return-Path: {$from}\n"
                  . "From: {$from}\n";
  
  echo '************Emails for ' . date(FF_FORMAT_DATE_LONG, NOW) . "************\n";
  $i = 0;
  foreach($users as $v)
  {
    $link = 'http://' . FF_SERVER_NAME . '/promo?code=upgrade50MB&key=' . md5('FF' . date('Ymd', NOW));
    $body = str_replace(array('{USERNAME}', '{EXPIRE_DATE}', '{LINK}', '{EMAIL_FOOTER}'), array($v['u_username'], $expiry, $link, emailFooter($v['u_key'], $campaignId, 'text')), $template);
    echo "Email sent to {$v['u_username']} ({$v['u_id']})\n";
    
    $m->send(
              $v['u_email'],
              'Your FotoFlix account is running out of space',
              $body,
              $mail_headers,
              "-f{$from_email}"
             );
    
    $GLOBALS['dbh']->execute($s = "INSERT INTO email_campaign_tracker(ect_ec_id, ect_u_id) VALUES({$campaignId}, {$v['u_id']})");
    $i++;
  }
  echo "************{$i} emails sent on " . date(FF_FORMAT_DATE_LONG, NOW) . "************\n";
?>