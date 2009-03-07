<?php
  include_once './init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_CLASS . '/CSession.php';
  include_once PATH_CLASS . '/CFlix.php';
  include_once PATH_DOCROOT . '/init_session.php';  

  
  $width = 640;
  $height = 520;
  
  if(isset($_POST['ids']))
  {
    $_POST['ids'] = preg_replace('/^,|,$/', '', $_POST['ids']);
    $ff_src = '/swf/dvd_container.swf?ids=' . $_POST['ids'] . '&uid=' . $_POST['user_id'] . '&delay=' . $_POST['delay'] . '&music=' . $_POST['music'] . '&flix_title=' . $_POST['title'] . '&template=' . $_POST['template'] . '&override_template=' . $_POST['template'] . '&sort_by=';
    $use_template = $_POST['template'];
    if(isset($_POST['_names']))
    {
      $_POST['_names'] = str_replace('"', '&quot;', $_POST['_names']);
      $_POST['_names'] = substr($_POST['_names'], 3);
      $_POST['_names'] = explode('|~|', $_POST['_names']);
      $ff_src .= '&names_serialized=' . urlencode(base64_encode(serialize($_POST['_names'])));
    }
    
    if(isset($_POST['_descs']))
    {
      $_POST['_descs'] = str_replace('"', '&quot;', $_POST['_descs']);
      $_POST['_descs'] = substr($_POST['_descs'], 3);
      $_POST['_descs'] = explode('|~|', $_POST['_descs']);
      $ff_src .= '&descs_serialized=' . urlencode(base64_encode(serialize($_POST['_descs'])));
    }
  }
  else
  if(isset($_GET['flix_id']))
  {
    $flix_id = isset($_GET['flix_id']) ? $_GET['flix_id'] : DEMO_FLIX;
    $user_id = $logged_in === true ? $_FF_SESSION->value('user_id') : DEMO_USER;

    $fl =& CFlix::getInstance();
    $flix_data = $fl->flixData($flix_id, $user_id);
    //$ff_src = '/swf/ff_container.swf?ids=' . $flix_data['A_DATA']['D_PHOTO_IDS'] . '&flix_id=' . $flix_id . '&uid=' . $user_id . '&user_id=' . $user_id . '&flix_template=' . $_GET['template'] . ' &title=' . urlencode($flix_data['A_NAME']) . '&sort_by=&override_template=' . $_GET['override_template'];
    $ff_src = '/swf/dvd_container.swf?ids=' . $flix_data['A_DATA']['D_PHOTO_IDS'] . '&flix_id=' . $flix_id . '&uid=' . $user_id . '&user_id=' . $user_id . '&delay=' . $flix_data['A_DELAY'] . '&template=' . $flix_data['A_TEMPLATE'] . ' &title=' . urlencode($flix_data['A_NAME']) . '&sort_by=';
    $use_template = $flix_data['A_TEMPLATE'];
  }
  else
  if(isset($_GET['fastflix']) || strlen($_SERVER['QUERY_STRING']) == 32)
  {
    $fl =& CFlix::getInstance();
    $fastflix = isset($_GET['fastflix']) ? $_GET['fastflix'] : $_SERVER['QUERY_STRING'];
    $flix_data = $fl->fastflix($fastflix);
    $ff_src = '/swf/dvd_container.swf?fastflix=' . $fastflix;
    $use_template = $flix_data['A_TEMPLATE'];
  }
  else
  if(isset($_GET['xml_src']))
  {
    $ff_src = '/swf/dvd_container.swf?xml_src=' . $_GET['xml_src'];
  }
  
  $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
  
  include_once PATH_DOCROOT . '/header_basic.dsp.php';
  //print_r($_GET['names']);
  //echo $ff_src;
?>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
  <param name="movie" value="<?php echo $ff_src; ?>" />
  <param name="menu" value="false" />
  <param name="quality" value="high" />
  <param name="bgcolor" value="#ffffff" />
  <embed src="<?php echo $ff_src; ?>" menu="false" quality="high" bgcolor="#758DA0" width="<?php echo $width; ?>" height="<?php echo $height; ?>" name="ff_album" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
</object>

<?php
  include_once PATH_DOCROOT . '/footer_basic.dsp.php';
  
  include_once PATH_DOCROOT . '/garbage_collector.act.php';
?>
