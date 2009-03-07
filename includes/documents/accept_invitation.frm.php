<?php
  $u =& CUser::getInstance();
  
  $reference_id = isset($_GET['reference_id']) ? $_GET['reference_id'] : '';
  $earn_space = $u->getEarnSpace($reference_id, false, 'pending');
  
  if($earn_space !== false)
  {
    $_get_udf       = array();
    $force_email    = $earn_space['S_EMAIL'];
    $earn_space_key = $reference_id;
    $email_validation_bypass  = true;
    $_get_udf['u_nameFirst']    = $earn_space['S_NAMEFIRST'];
    $_get_udf['u_nameLast']     = $earn_space['S_NAMELAST'];

    include_once PATH_DOCROOT . '/registration_a.frm.php';
  }  
?>