<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
  
  echo 'successful purchase!';
  // MOVED TO cart_checkout.act.php mail('chip@gamebattles.com', 'purchase complete', 'successful purchase!');
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>