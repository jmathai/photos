<?php
  $tb =& CToolbox::getInstance();
  $fb =& CFotobox::getInstance();
  
  if($_GET['purge'] == 1)
  {
    $tb->clear($_USER_ID);
  }
  
  if(!empty($_GET['ids']))
  {
    $idsArray = (array)explode(',', $_GET['ids']);
    rsort($idsArray);
    foreach($idsArray as $v)
    {
      $tb->add($_USER_ID, $v);
    }
  }
  else
  if(!empty($_GET['dateAfter']))
  {
    $fotos = $fb->uploadedSince($_USER_ID, $_GET['dateAfter']);
    foreach($fotos as $v)
    {
      $tb->add($_USER_ID, $v['P_ID']);
    }
  }
  
  if(isset($_GET['redirect']))
  {
    $url = $_GET['redirect'];
  }
  else
  {
    if($action == 'fotobox.toolbox_add_slideshow.act')
    {
      $url = '/?action=flix.flix_form&toolbox=1';
    }
    else
    {
      $url = '/?action=fotobox.fotobox_myfotos';
    }
  }
?>