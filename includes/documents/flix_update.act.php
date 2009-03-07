<?php
  $f  =& CFlix::getInstance();
  $fl =& CFlixManage::getInstance();
  $tb =& CToolbox::getInstance();
  
  $array_main = array('uf_id' => $_GET['uf_id']);
  $array_data = array();
  
  $fotos = $tb->get($_USER_ID, 'foto');
  foreach($fotos as $v)
  {
    $array_data[] = array('ufd_uf_id' => $_GET['uf_id'], 'ufd_up_id' => $v['P_ID'], 'ufd_inTimeline' => 'N');
  }
  
  /*$foto_ids = isset($_GET['foto_i//ds']) ? preg_replace('/^,*|,*$/', '', $_GET['foto_ids']) : '';
  
  $array_fotos = (array)explode(',', $foto_ids);*/
  
  /*foreach($array_fotos as $v)
  {
    $array_data[] = array('ufd_uf_id' => $_GET['uf_id'], 'ufd_up_id' => $v, 'ufd_inTimeline' => 'N');
  }*/
  
  $fl->update(false, $array_data, false);
  
  $flix_data = $f->flixData($_GET['uf_id'], $_USER_ID);
  
  $url = '/?action=flix.flix_form&fastflix=' . $flix_data['A_FASTFLIX'];
?>