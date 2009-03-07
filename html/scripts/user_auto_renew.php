<?php
  basename($_SERVER['PHP_SELF']);
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  
  $monthlyTypes = array('premium_a_month','premium_a_pro','premium_b_pro','premium_c_pro');
  
  // set expiry date to tomorrow
  $expireTime = mktime(0, 0, 0, date('n', NOW), (date('j', NOW) + 1), date('Y', NOW));
  $expires = date('Y-m-d', $expireTime);
  
  $sql  = "SELECT u_id, u_username, u_accountType FROM users WHERE u_status = 'Active' AND u_dateExpires = '{$expires}'";
  $users= $GLOBALS['dbh']->query_all($sql);
  
  echo 'Updating users for ' . date('Y-m-d', NOW) . "\n";
  
  $i = 0;
  foreach($users as $v)
  {
    $expiry = in_array($v['u_accountType'], $monthlyTypes) ? ($expireTime + 2592000) : ($expireTime + 31536000);
    
    $expiry = date('Y-m-d', $expiry);
    $sqlUpd = "UPDATE users SET u_dateExpires = '{$expiry}' WHERE u_id = '{$v['u_id']}'";
    $GLOBALS['dbh']->execute($sqlUpd);
    echo 'Updated user ' . $v['u_username'] . '(' . $v['u_id'] . ') to expire on ' . $expiry . "\n";
    $i++;
  }
  
  echo  "Updated {$i} users for " . date('Y-m-d', NOW) . "\n"
      . "^^^^^^^^^^^^^^^^^^^^^^^^\n\n";
?>