<?php
  include_once './init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CMusic.php';
  
  $m =& CMusic::getInstance();
  
  $music_path = PATH_SWF_MUSIC;
  
  if(isset($_GET['genre']))
  {
    if($_GET['genre'] == 'My Music')
    {
      $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
      $arr = $m->myMusic($user_id);
    }
    else
    {
      $arr = $m->music($_GET['genre']);
    }
  }
  else
  if(isset($_GET['template']) && isset($_GET['user_id']))
  {
    $arr = $m->templatePlaylist($_GET['template'], $_GET['user_id']);
  }
  else
  {
    die('died');
  }
  
  header('Content-type: text/xml');
  
  echo '<?xml version="1.0"?>'
      .'<ffData genre="' . $_GET['genre'] . '" template="' . $_GET['template'] . '">';
  
  foreach($arr as $v)
  {
    echo '<ffItem 
            m_id="' . $v['M_ID'] . '"
            m_swf_src="' . $music_path . $v['M_SWF_SRC'] . '" 
            m_size="' . $v['M_SIZE'] . '"
            m_length="' . $v['M_LENGTH'] . '"
            m_genre="' . $v['M_GENRE'] . '" 
            m_tempo="' . $v['M_TEMPO'] . '" 
            m_name="' . $v['M_NAME'] . '" 
            m_description="' . $v['M_DESC'] . '" 
          />';
  }

  echo '</ffData>';
  
  include_once PATH_DOCROOT . '/garbage_collector.act.php';
?>
