<?php
  ob_start();
  include_once './../init_constants.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_DOCROOT . '/init_database.php';

  $action = isset($_GET['action']) ? $_GET['action'] : 'stats.home';

  include './header.dsp.php';

  switch($action)
  {
    case 'eaccelerator.home':
      eaccelerator();
      break;

		case 'cancelled_users.home':
			include './cancelled_users.dsp.php';
			break;

    case 'email.home':
      include PATH_CLASS . '/CUser.php';
      include './email_home.dsp.php';
      break;
      
    case'faq.form':
      include_once PATH_CLASS . '/CFaq.php';
      include_once './faq_form.dsp.php';
      break;
    case'faq.home':
      include_once PATH_CLASS . '/CFaq.php';
      include_once './faq_home.dsp.php';
      break;
      
    case 'flix.hotspots':
      include './flix_hotspots.dsp.php';
      break;
    case 'flix.search_form':
      include './flix_search.frm.php';
      break;
    case 'flix.search_results':
      include PATH_CLASS . '/CPaging.php';
      include './flix_search.frm.php';
      include './flix_search_results.dsp.php';
      break;
    case 'flix.themes':
      include './flix_themes.dsp.php';
      break;

    case 'fotos.public_fotos':
      include PATH_CLASS . '/CFotobox.php';
      include PATH_CLASS . '/CPaging.php';
      include './public_fotos.dsp.php';
      break;

		case 'incomplete_users.home':
			include './incomplete_users.dsp.php';
			break;

    case 'md5.home':
      include './md5_home.dsp.php';
      break;

    case 'music.home':
      include PATH_CLASS . '/CFormValidator.php';
      include './music_home.dsp.php';
      break;

		case 'paying_users.home':
			include './paying_users.dsp.php';
			break;

    case 'phpinfo.home':
      phpinfo();
      break;

    case 'quarantined.home':
      include PATH_CLASS . '/CFotobox.php';
      include PATH_CLASS . '/CUser.php';
      include PATH_CLASS . '/CPaging.php';
      include './quarantined.dsp.php';
      break;

    case 'stats.home':
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CVideo.php';
      include './jpgraph/src/jpgraph.php';
      include './jpgraph/src/jpgraph_line.php';
      include './stats_home.dsp.php';
      break;

		case 'trial_users.home':
			include './trial_users.dsp.php';
			break;
			
    case 'users.search_form':
      include './users_search.frm.php';
      break;
    case 'users.search_results':
      include './users_search.frm.php';
      include './user_search_results.dsp.php';
      break;
    case 'users.single_result':
      include './user_single_result.dsp.php';
      break;
      
    /////////////////////////////////////////////////////////////////////////////////////////////////
    
    case 'delete_foto.act':
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include './delete_foto.act.php';
      header('Location: ' . $url);
      die();
      break;
			
    case'faq.form.act':
      include_once './faq_form.act.php';
      header('Location: ' . $url);
      die();
      break;
      
    case 'music.home.act':
    	include './music_home.act.php';
    	header('Location: ' . $url);
      die();
    	break;
			
    case 'restore_foto.act':
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include './restore_foto.act.php';
      header('Location: ' . $url);
      die();
      break;
    	
    case 'users.disable.act':
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CMail.php';
      include './user_disable.act.php';
      header('Location: ' . $url);
      echo $url;
      die();
      break;

    case 'users.enable.act':
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CMail.php';
      include './user_enable.act.php';
      header('Location: ' . $url);
      die();
      break;
  }

  include './footer.dsp.php';
  ob_end_flush();
?>
