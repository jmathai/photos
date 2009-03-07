<?php
  $um =& CUserManage::getInstance();
  
  // db field is varchar(16)
  $arrayMap = array('foto_privacy' => 'FOTO_PRIVACY',
                    'fotobox_per_page' => 'FOTOBOX_PER_PAGE',
                    'fotobox_page' => 'FOTOBOX_PAGE');
  
  $data = array();
  foreach($_GET['nodes'] as $v)
  {
    $nodeParams = explode(',', $v);
    $data[$arrayMap[$nodeParams[0]]] = $nodeParams[1];
  }
  
  $um->setPrefs($_USER_ID, $data);
  
  $url = isset($_GET['redirect']) ? $_GET['redirect'] : '/';
?>