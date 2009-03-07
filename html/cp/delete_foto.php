<?php
  include_once './../init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CFotoboxManage.php';
  
  $fb =& CFotoboxManage::getInstance();
  
  if(isset($_GET['p_id']) && isset($_GET['u_id']))
  {
    $fb->violation( $_GET['p_id'], $_GET['u_id'] );
  }
  else
  if(isset($_GET['ids']))
  {
    foreach($_GET['ids'] as $v)
    {
      $parts = explode(',', $v);
      $fb->violation($parts[0], $parts[1]);
    }
  }
  
  header('Location: /cp/?action=fotos.public_fotos&page=' . intval($_GET['page']));
  
?>