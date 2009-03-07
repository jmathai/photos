<?php
  $fbm =& CFotoboxManage::getInstance();
  
  // get all affected flix
  $fb =& CFotobox::getInstance();
  $fl =& CFlix::getInstance();
  $flm =& CFlixManage::getInstance();
  
  $foto_ids = isset($_POST['ids']) ? $_POST['ids'] : '';
  $fotos_array = explode(',', $foto_ids);
  
  $dependencies_array = $fb->dependencies($fotos_array);
  
  foreach($dependencies_array as $k => $v)
  {
    $foto_data  = $fb->fotoData($v['P_ID'], $_USER_ID);
    $flix_array = array();
    
    if(isset($v['FLIX_IDS']))
    {
      foreach($v['FLIX_IDS'] as $k2 => $v2)
      {
        $flix_array[] = $fl->flixData($v2, $_USER_ID);
      }
    }
  }
    
  // delete the fotos
  if(isset($_POST['ids']))
  {
    $arr_ids = (array)explode(',', $_POST['ids']);
    $fbm->delete($arr_ids, $_USER_ID);
  }
  
  
  // delete any empty flix
  foreach($flix_array as $v3)
  {
    if($v3['A_FOTO_COUNT'] == 0)
    {
      $flm->delete($v3['A_ID'], $_USER_ID);
    }
  }
  
  $url = "/?action=fotobox.fotobox_myfotos&message=fotos_deleted";
?>
