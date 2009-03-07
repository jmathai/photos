<?php
  @include_once './init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_CLASS . '/CIdat.php';
  include_once PATH_CLASS . '/CUser.php';
  include_once PATH_CLASS . '/CFotobox.php';
  include_once PATH_CLASS . '/CFlix.php';
  include_once PATH_CLASS . '/CMusic.php';

  
  $fb =& CFotobox::getInstance();
  $us =& CUser::getInstance();
  
  $mode = isset($mode) ? $mode : 'output';
  
  $music_path = PATH_SWF_MUSIC;
  
  if(isset($_GET['fastflix']) || isset($fastflix))
  {
    $fl =& CFlix::getInstance();
    $m  =& CMusic::getInstance();
    
    $fastflix     = isset($fastflix) ? $fastflix : $_GET['fastflix'];
    $arr_flix     = $fl->fastFlix($fastflix);
    $user_data    = $us->find($arr_flix['A_U_ID']);
    $username     = $user_data['U_USERNAME'];
    $music_type   = strstr($arr_flix['A_MUSIC'], '/mp3/') ? 'mp3' : 'swf';
    $autoplay     = $arr_flix['A_AUTOPLAY'];
    $use_template = $arr_flix['A_TEMPLATE'];
    $created_by   = $arr_flix['A_CREATED_BY'];
    $length       = $arr_flix['A_LENGTH'];
    $partner_parts= explode('|', $arr_flix['A_ADDITIONAL']);
    $partner_string= $partner_parts[1];
    //trap($arr_flix);
    if($music_type == 'mp3')
    {
      $arr_music    = $m->myMusic($arr_flix['A_U_ID'], $arr_flix['A_MUSIC']);
      $music        = $arr_flix['A_MUSIC'];
      $music_id     = $arr_music['M_ID'];
      $music_length = $arr_music['M_LENGTH'];
      $music_size   = $arr_music['M_SIZE'] * KB;
      $genre        = $arr_music['M_GENRE'];
    }
    else
    {
      $arr_music    = $m->track($arr_flix['A_MUSIC']);
      $music        = $arr_flix['A_MUSIC'];
      $music_id     = $arr_music['A_ID'];
      $music_length = 0;
      $music_size   = 0;
      $genre = $arr_music['M_GENRE'];
    }
    
    $arr_fotos = $fb->fotosByIds((array)explode(',', $arr_flix['A_DATA']['D_PHOTO_IDS']));
    
    $arr = array();
    $cnt_data = count($arr_flix['A_DATA']) - 1;
    
    for($_i = 0; $_i < $cnt_data; $_i++)
    {
      $tmp = $arr_flix['A_DATA'][$_i];
      $arr[] = array(
                  'P_ID' => $tmp['D_UP_ID'],
                  'P_KEY' => $tmp['D_KEY'],
                  'P_DELAY' => $tmp['D_DELAY'], 
                  'P_IS_TITLE'=> $tmp['D_IS_TITLE'],
                  'P_NAME' => $tmp['D_NAME'],
                  'P_DESC' => $tmp['D_DESC'],
                  'P_SIZE' => $arr_fotos[$_i]['P_SIZE'],
                  'P_LINK' => $tmp['D_LINK'],
                  'P_LINK_TARGET' => $tmp['D_LINK_TARGET'],
                  'P_IN_TIMELINE' => $tmp['D_IN_TIMELINE'],
                  'P_THUMB_PATH' => $arr_fotos[$_i]['P_THUMB_PATH'],
                  'P_FLIX_PATH'  => $arr_fotos[$_i]['P_FLIX_PATH'],
                  'P_WEB_PATH'   => $arr_fotos[$_i]['P_WEB_PATH']
                );
    }
  }
  else
  if(isset($_REQUEST['ids']))
  {
    $arr_flix     = array('A_NAME' => str_replace('/\\', '', $_REQUEST['title']));
    $arrIds       = explode(',', $_REQUEST['ids']);
    $arr          = $fb->fotosByIds($arrIds, $_REQUEST['user_id'], $_REQUEST['sort_by']);
    $user_data    = $us->find($_REQUEST['user_id']);
    $username      = $user_data['U_USERNAME'];
    $use_template = $_REQUEST['template'];
    $autoplay     = $_REQUEST['autoStart'];
    $created_by   = $_REQUEST['created_by'];
    $length       = $_REQUEST['flix_length'];
    $music        = isset($_REQUEST['music']) ? str_replace($music_path, '', $_REQUEST['music']) : '/noMusic.swf';
    $music_id     = '';
    $music_size   = $_REQUEST['music_size'];
    $music_length = $_REQUEST['music_length'];
    $genre        = '';
    $array_titles= explode(',', $_REQUEST['is_title']);
    $array_links = explode(',', $_REQUEST['links']);
    $array_links_target   = explode(',', $_REQUEST['links_target']);
    $array_delays= explode(',', $_REQUEST['delays']);
    $array_names = $_REQUEST['_names'];
    $array_descs = $_REQUEST['_descs'];
    $partner_string= '';
    
    $use_request_names = isset($_REQUEST['_names']) ? true : false;
    $use_request_descs = isset($_REQUEST['_descs']) ? true : false;
    $i = 0;
    foreach($arr as $k => $_tmp)
    {
      $arr[$k]['P_DELAY'] = intval($array_delays[$i]) > 0 ? $array_delays[$i] : 3500;
      $arr[$k]['P_NAME'] = ($use_request_names === true ? $array_names[$i] : $_tmp['P_NAME']);
      $arr[$k]['P_DESC'] = ($use_request_descs === true ? $array_descs[$i] : $_tmp['P_DESC']);
      $arr[$k]['P_SIZE'] = '';
      $arr[$k]['P_LINK'] = $array_links[$i];
      $arr[$k]['P_LINK_TARGET'] = $array_links_target[$i];
      $arr[$k]['P_IS_TITLE'] = !empty($array_titles[$i]) ? $array_titles[$i] : 'N';
      $arr[$k]['P_IN_TIMELINE'] = 'N';
      $i++;
    }
  }
  
  /*if(isset($_REQUEST['_names']) || isset($_REQUEST['_descs']))
  {*/
    /*ob_start();
    print_r($_REQUEST);
    $mail = ob_get_contents();
    ob_end_clean();
    mail('jaisen@jmathai.com', 'request', $mail);*/
  /*}*/
  
  if(isset($_REQUEST['override_template']))
  {
    if(strlen($_REQUEST['override_template']) > 0)
    {
      $use_template = $_REQUEST['override_template'];
    }
  }
  
  $popup_width  = FF_WEB_WIDTH + 10;
  $popup_height = FF_WEB_HEIGHT + 75;
  
  $return =   '<?xml version="1.0"?>';
  $return .=  '<ffData f_id="' . $arr_flix['A_ID'] . '" f_username="' . $username . '" f_u_id="' . $arr_flix['A_U_ID'] . '" f_title="' . $arr_flix['A_NAME'] . '" f_created_by="' . $created_by . '" f_template="' . $use_template . '" f_music="' . $music_path . $music . '" f_music_id="' . $music_id . '" f_music_length="' . $music_length . '" f_music_size="' . $music_size . '" f_music_type="' . $music_type . '" f_genre="' . $genre . '" f_start="' . $autoplay . '" f_length="' . $length . '" f_partner_string="' . $partner_string . '" p_width="' . $popup_width . '" p_height="' . $popup_height . '">';  
  foreach($arr as $v)
  {
    $size   = explode('x', $v['P_SIZE']);
    $width  = $size[0];
    $height = $size[1];
    $return .= '<ffItem 
            p_id="' . $v['P_ID'] . '"
            p_key="' . $v['P_KEY'] . '"
            p_delay="' . $v['P_DELAY'] . '"
            p_name="' . $v['P_NAME'] . '" 
            p_description="' . str_replace("\n", ' ', ($v['P_DESC'])) . '" 
            p_size="' . $v['P_SIZE'] . '" 
            p_title="' . $v['P_IS_TITLE'] . '"
            p_link="' . $v['P_LINK'] . '"
            p_linkTarget="' . $v['P_LINK_TARGET'] . '"
            p_timeline="' . $v['P_IN_TIMELINE'] .'"
            t_src="http://' . FF_SERVER_NAME . PATH_FOTO . $v['P_THUMB_PATH'] . '" 
            f_src="http://' . FF_SERVER_NAME . PATH_FOTO . $v['P_FLIX_PATH'] . '" 
            w_src="http://' . FF_SERVER_NAME . PATH_FOTO . $v['P_WEB_PATH'] . '"
          />';
  }
  
  $return .= '</ffData>';
  
  if($mode == 'output')
  {
    header('Content-type: text/xml');
    echo $return;
  }
  else
  {
    $i  =&new CIdat;
    $id = $i->nextID('fotoflix.preview');

    $filename = isset($filename) ? $filename : md5($id . NOW) . '.xml';
    
    $fp = fopen(PATH_TMPROOT . '/' . $filename, 'w');
    fwrite($fp, $return, strlen($return));
    fclose($fp);
    return $filename;
  }
  
  include_once PATH_DOCROOT . '/garbage_collector.act.php';
?>