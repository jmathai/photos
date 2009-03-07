<?php
  if($logged_in || ($_FF_SESSION->value('temp_user_id') > 0 && $action == 'home.registration_form_b2'))
  {
  }
  else
  if($logged_in === false)
  {
    include PATH_DOCROOT . '/login.frm.php';
    include_once PATH_DOCROOT . '/footer.dsp.php';
    die();
  }
?>
