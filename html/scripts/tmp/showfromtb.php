<?php
  include '../../init_constants.php';
  include PATH_INCLUDE . '/functions.php';
  include PATH_HOMEROOT . '/init.php';
  include_once PATH_DOCROOT . '/init_database.php';
  
  include_once PATH_CLASS . '/CSession.php';
  include_once PATH_DOCROOT . '/init_session.php';
  include PATH_CLASS . '/CToolbox.php';
  include PATH_CLASS . '/CFlixManage.php';
  include PATH_CLASS . '/CFotobox.php';
  
  
  $tb =& CToolbox::getInstance();
  $flm=& CFlixManage::getInstance();
  $fb =& CFotobox::getInstance();
  $fotos = $tb->get($_USER_ID);
  
  $params = array('MODE' => 'INSERT', 'USER_ID' => $_USER_ID, 'TAGS' => 'stuff', 'NAME' => date('h:i:s', time()), 'ELEMENTS' => array(), 'SETTINGS' => array());
  
  $params['SETTINGS'] = array(
                          array('instanceName_str' => 'someinstance', 'depth_int' => 0, 'swfPath_str' => '/path/to/swf'),
                          array('instanceName_str' => 'someotherinstance', 'depth_int' => 0, 'swfPath_str' => '/path/to/swf', 'maskPath_str' => '/path/to/mask')
                        );
  
  foreach($fotos as $k => $v)
  {
    $fotoData = $fb->fotoData($v['P_ID']);
    $params['ELEMENTS'][$k] = array('photoId_int' => $fotoData['P_ID'], 'photoPath_str' => $fotoData['P_ORIG_PATH'], 'thumbnailPath_str' => $fotoData['P_THUMB_PATH'], 'photoKey_str' => $fotoData['P_KEY']);
    
    if($k % 2 == 0)
    {
      $params['ELEMENTS'][$k]['hotSpot_arr'] = array(
                                      array('note_str' => 'This is a note', 'fill_bool' => false, 'depth_int' => 1),
                                      array('note_str' => 'This is a note', 'fill_bool' => false, 'depth_int' => 1, 'draw_arr' =>
                                        array(
                                          array('x_int' => 0, 'y_int' => 10/*, 'stroke_int' => 10*/),
                                          array('x_int' => 1, 'y_int' => 11/*, 'stroke_int' => 10*/),
                                          array('x_int' => 2, 'y_int' => 12/*, 'stroke_int' => 10*/)
                                        )
                                      ),
                                    );
    }
  }
  
  //print_r($params);
  echo jsonEncode($params);
  
  $flm->flashInput($params);
?>