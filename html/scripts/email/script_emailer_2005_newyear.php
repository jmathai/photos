<?php
  die();
  include_once '../../init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_CLASS . '/CMail.php';
  
  $m =& Cmail::getInstance();
  
  $campaignName = '2005_newyear';
  
  $template = file_get_contents('./script_emailer_2005_newyear.html');
  
  $campaign = $GLOBALS['dbh']->query_first("SELECT * FROM email_campaigns WHERE ec_name = '{$campaignName}'");
  $campaignId = $campaign['ec_id'];
  
  $start = intval($_GET['start']);
  $limit = 50;
  
  $sql  = 'SELECT DISTINCT u.* '
        . "FROM (users AS u LEFT JOIN email_campaign_tracker AS ect ON u.u_id = ect.ect_u_id AND ect.ect_ec_id = {$campaignId}) "
        . 'LEFT JOIN email_unsubscribe AS eu ON u.u_id = eu.eu_u_id '
        . "WHERE ect.ect_u_id IS NULL AND eu.eu_u_id IS NULL AND u.u_status = 'Active' "
        . "LIMIT {$start}, {$limit}";
  /*$sql  = 'SELECT DISTINCT u.* '
        . 'FROM users AS u LEFT JOIN email_campaign_tracker AS ect ON u.u_id = ect.ect_u_id '
        . "WHERE (u.u_username = 'vree' OR u.u_username = 'jmathai') "
        . "LIMIT {$start}, {$limit}";*/
  
  //echo $sql;
  $users = $GLOBALS['dbh']->query_all($sql);
  
  $from     = FF_EMAIL_FROM_FORMATTED;
  $from_email = FF_EMAIL_FROM;
  $mail_headers   = "MIME-Version: 1.0\n"
                  . "Content-type: text/html; charset=iso-8859-1\n"
                  . "Return-Path: {$from}\n"
                  . "From: {$from}\n";
  
  echo '************Emails for ' . date(FF_FORMAT_DATE_LONG, NOW) . "************\n";
  $i = 0;
  foreach($users as $v)
  {
    $body = str_replace(array('{USERNAME}', '{EMAIL_FOOTER}'), array($v['u_username'], emailFooter($v['u_key'], $campaignId, 'link')), $template);
    
    $m->send(
            $v['u_email'],
            'A New Year\'s theme and more from FotoFlix',
            $body,
            $mail_headers,
            "-f{$from_email}"
           );
    $GLOBALS['dbh']->execute($s = "INSERT INTO email_campaign_tracker(ect_ec_id, ect_u_id) VALUES({$campaignId}, {$v['u_id']})");
    
    echo "Email sent to {$v['u_username']} ({$v['u_id']}) - {$v['u_email']}<br/>\n";
    $i++;
  }
  echo "************{$i} emails sent on " . date(FF_FORMAT_DATE_LONG, NOW) . "************\n";
  
  if(count($users) > 0)
  {
    echo '<script language="javascript"> 
    setTimeout(\'location.href = "' . $_SERVER['PHP_SELF'] . '?start=' . ($start + $limit) . '"\', 1000);
    </script>';
  }
?>
