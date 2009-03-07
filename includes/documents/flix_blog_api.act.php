<?php
  $api =& CApiClient::getInstance();
  $usm =& CUserManage::getInstance();
  $us  =& CUser::getInstance();
  $fb  =& CFotobox::getInstance();
  $fbm =& CFotoboxManage::getInstance();
  $fl  =& CFlix::getInstance();
  
  $userData = $us->find($_USER_ID);
  
  $result   = 0;
  $codes    = array();
  $foto_ids = explode(',', $_REQUEST['foto_ids']);
  $fastflix = $_REQUEST['fastflix'];
  $blogIds  = array();
  
  if(isset($_GET['quickpost']))
  {
    if(isset($_REQUEST['foto_ids']))
    {
      $ids = explode(',', $foto_ids);
      $fotos = $fb->fotosByIds($foto_ids, $_USER_ID);
      $cnt_fotos = count($fotos);
      $ff_code_embed = '';
      
      foreach($fotos as $v)
      {
        $ff_code_embed .= '<div style="padding-top:5px;"><a href="http://' . FF_SERVER_NAME . '/users/' . $user_data['U_USERNAME'] . '/foto/' . $v['P_ID'] . '/" target="_blank"><img src="http://' . FF_SERVER_NAME . '/foto?key=' . $v['P_KEY'] . '&size=400x300" border="0" /></a></div>' . "\n";
      }
    }
    else
    if(isset($_REQUEST['fastflix']))
    {
      $xml_orig   = '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '.xml';
      $xml_path   = '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '_' . NOW . '.xml';
      $flix_data = $fl->fastflix($_REQUEST['fastflix'], $_USER_ID);
      $sizeArr   = explode('x', $flix_data['A_SIZE']);
      $containerWidth = $sizeArr[0];
      $containerHeight= $sizeArr[1];
      $ff_code_embed = '<embed src="http://' . FF_SERVER_NAME . '/swf/' . $flix_data['A_CONTAINER'] . '?fastflix=' . $fastflix . '&xml_src=http://' . FF_SERVER_NAME . PATH_FOTO . $xml_path . '&server_name=' . FF_SERVER_NAME . '&version=' . FF_VERSION_TEMPLATE . '&referrer=&destination=' . urlencode('http://' . FF_SERVER_NAME) . '" menu="false" quality="high" width="' . $containerWidth . '" height="' . $containerHeight . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" swLiveConnect="true"></embed>';
      $_POST['xml_orig'] = $xml_orig;
      $_POST['xml_path'] = $xml_path;
    }
    
    $_POST['title'] = 'QuickPost from FotoFlix';   
    $_POST['blog_code'] = $ff_code_embed;
    $_POST['post'] = '';
  }
  
  $blogContent = str_replace(array('{BLOG_CODE}', '{FOTOPAGE}', '{USERNAME}', '{POST}'/*, '{IMGSRC}'*/),
                              array($_POST['blog_code'], 'http://' . FF_SERVER_NAME . '/users/' . $userData['U_USERNAME'] . '/', $userData['U_USERNAME'], $_POST['post']/*, 'http://' . FF_SERVER_NAME . '/images/dynamic/' . substr($userData['U_USERNAME'], 0, 2) . '/ff_published_' . $userData['U_USERNAME'] . '.png'*/),
                              file_get_contents(PATH_DOCROOT . '/blog.tpl.php'));
  
  if(isset($_REQUEST['ub_id']))
  {
    $result = 1;
    $blogs = $us->blogs($_USER_ID, $_REQUEST['ub_id']);
    
    foreach($blogs as $v)
    {
      $res = 0;
      switch($v['B_TYPE'])
      {
        case 'Blogger':
          $content = $api->atomRequestContent(array('TITLE' =>$_POST['title'], 'CONTENT' => $blogContent));
          $res = $api->atom(array('HOST' => 'www.blogger.com', 'PATH' => "/atom/{$v['B_BLOGID']}", 'USERNAME' => $v['B_USERNAME'], 'PASSWORD' => base64_decode($v['B_PASSWORD']), 'METHOD' => 'POST', 'AUTHENTICATION' => 'BASIC', 'PROTOCOL' => 'SSL', 'CONTENT' => $content));
          $rsp_code = $api->getHttpStatus($res);
          $okCode = 200;
          break;
        case 'TypePad':
          $content = $api->atomRequestContent(array('TITLE' => $_POST['title'], 'CONTENT' => $blogContent));
          $res = $api->atom(array('HOST' => 'www.typepad.com', 'PATH' => "/t/atom/weblog/blog_id={$v['B_BLOGID']}", 'USERNAME' => $v['B_USERNAME'], 'PASSWORD' => base64_decode($v['B_PASSWORD']), 'METHOD' => 'POST', 'AUTHENTICATION' => 'SECURE', 'PROTOCOL' => 'SSL', 'CONTENT' => $content, 'ISSUED' => NOW));
          $rsp_code = $api->getHttpStatus($res);
          $okCode = 201;
          break;
        case 'LiveJournal':
          $content = $api->atomRequestContent(array('TITLE' => $_POST['title'], 'CONTENT' => $blogContent));
          $res = $api->atom(array('HOST' => 'www.livejournal.com', 'PATH' => '/interface/atom/post', 'USERNAME' => $v['B_USERNAME'], 'PASSWORD' => base64_decode($v['B_PASSWORD']), 'METHOD' => 'POST', 'AUTHENTICATION' => 'SECURE', 'PROTOCOL' => 'HTTP', 'CONTENT' => $content, 'ISSUED' => NOW));
          $rsp_code = $api->getHttpStatus($res);
          $okCode = 201;
          break;
        case 'MovableType':
        case 'WordPress':
          $urlParts = parse_url($v['B_ENDPOINT']);
          
          $content = $api->atomRequestContent(array('METHOD' => 'metaWeblog.newPost', 'USERNAME' => $v['B_USERNAME'], 'PASSWORD' => base64_decode($v['B_PASSWORD']), 'BLOG_ID' => $v['B_BLOGID'], 'TITLE' => $_POST['title'], 'CONTENT' => $blogContent, 'PUBLISH' => 1, 'ISSUED' => NOW));
          $res = $api->atom(array('HOST' => $urlParts['host'], 'PATH' => $urlParts['path'], 'USERNAME' => $v['B_USERNAME'], 'USERNAME' => base64_decode($v['B_PASSWORD']), 'METHOD' => 'POST', 'AUTHENTICATION' => 'BASIC-RPC', 'PROTOCOL' => 'HTTP', 'CONTENT' => $content, 'ISSUED' => NOW));
          $rsp_code = $api->getHttpStatus($res);
          $okCode = 200;
          break;
      }
      
      if($rsp_code != $okCode)
      {
        $codes[]= $rsp_code;
        $result = -1;
      }
      else
      {
        $blogIds[] = $v['B_ID'];
      }
    }
  }
  else
  if(isset($_POST['ub_username']) && isset($_POST['ub_password']) && isset($_POST['ub_blogId']))
  {
    $result = 1;
    
    switch($_POST['ub_type'])
    {
      case 'Blogger':
        $content = $api->atomRequestContent(array('TITLE' => $_POST['title'], 'CONTENT' => $blogContent));
        $res = $api->atom(array('HOST' => 'www.blogger.com', 'PATH' => "/atom/{$_POST['ub_blogId']}", 'USERNAME' => $_POST['ub_username'], 'PASSWORD' => $_POST['ub_password'], 'METHOD' => 'POST', 'AUTHENTICATION' => 'BASIC', 'PROTOCOL' => 'SSL', 'CONTENT' => $content));
        $rsp_code = $api->getHttpStatus($res);
        $okCode = 200;
        break;
      case 'TypePad':
        $content = $api->atomRequestContent(array('TITLE' => $_POST['title'], 'CONTENT' => $blogContent));
        $res = $api->atom(array('HOST' => 'www.typepad.com', 'PATH' => "/t/atom/weblog/blog_id={$_POST['ub_blogId']}", 'USERNAME' => $_POST['ub_username'], 'PASSWORD' => $_POST['ub_password'], 'METHOD' => 'POST', 'AUTHENTICATION' => 'SECURE', 'PROTOCOL' => 'SSL', 'CONTENT' => $content, 'ISSUED' => NOW));
        $rsp_code = $api->getHttpStatus($res);
        $okCode = 201;
        break;
      case 'LiveJournal':
        $content = $api->atomRequestContent(array('TITLE' => $_POST['title'], 'CONTENT' => $blogContent));
        $res = $api->atom(array('HOST' => 'www.livejournal.com', 'PATH' => '/interface/atom/post', 'USERNAME' => $_POST['ub_username'], 'PASSWORD' => $_POST['ub_password'], 'METHOD' => 'POST', 'AUTHENTICATION' => 'SECURE', 'PROTOCOL' => 'HTTP', 'CONTENT' => $content, 'ISSUED' => NOW));
        $rsp_code = $api->getHttpStatus($res);
        $okCode = 201;
        break;
      case 'MovableType':
      case 'WordPress':
        $urlParts = parse_url($_POST['ub_endPoint']);
        
        $content = $api->atomRequestContent(array('METHOD' => 'metaWeblog.newPost', 'USERNAME' => $_POST['ub_username'], 'PASSWORD' => $_POST['ub_password'], 'BLOG_ID' => $_POST['ub_blogId'], 'TITLE' => $_POST['title'], 'CONTENT' => $blogContent, 'PUBLISH' => 1, 'ISSUED' => NOW));
        $res = $api->atom(array('HOST' => $urlParts['host'], 'PATH' => $urlParts['path'], 'USERNAME' => $_POST['ub_username'], 'PASSWORD' => $_POST['ub_password'], 'METHOD' => 'POST', 'AUTHENTICATION' => 'BASIC-RPC', 'PROTOCOL' => 'HTTP', 'CONTENT' => $content, 'ISSUED' => NOW));
        $rsp_code = $api->getHttpStatus($res);
        $okCode = 200;
        break;
    }
    
    if($rsp_code == $okCode && isset($_POST['save_blog']))
    {
      $data = array('ub_u_id' => $_USER_ID, 'ub_name' => $_POST['ub_username'] . '@' . $_POST['ub_type'], 'ub_username' => $_POST['ub_username'], 'ub_password' => base64_encode($_POST['ub_password']), 'ub_url' => 'http://' . str_replace('http://', '', $_POST['ub_url']), 'ub_blogId' => $_POST['ub_blogId'], 'ub_endPoint' => $_POST['ub_endPoint'], 'ub_type' => $_POST['ub_type']);
      $tmpBlogId = $usm->addBlog($data);
    }
    
    if($rsp_code != $okCode)
    {
      $codes[]= $rsp_code;
      $result = -1;
    }
    else
    {
      $blogIds[] = $tmpBlogId;
    }
  }
  
  if($action == 'flix.flix_blog_api.act') // BLOG A FLIX
  {
    if($result >= 0)
    {
      if(isset($_POST['xml_orig']))
      {
        copy(PATH_FOTOROOT . $_POST['xml_orig'], PATH_FOTOROOT . $_POST['xml_path']);
      }
      
      $fotos = $fl->fotosByFlix($fastflix, $_USER_ID);
      
      $fotoArray = array();
      foreach($fotos as $v)
      {
        $fotoArray[] = $v['P_ID'];
      }
      
      if(isset($_REQUEST['make_fotos_public']))
      {
        $fbm->setPrivacyByIds($_USER_ID, $fotoArray, 3333);
        $usm->setFotoPage($_USER_ID, $userData['U_KEY']);
      }
      $url = '/?action=flix.flix_post_message&b_ids=' . implode(',', $blogIds) . '&fastflix=' . $fastflix . '&result=' . $result;
    }
    else
    {
      $url = '/?action=flix.flix_post_message&fastflix=' . $fastflix . '&result=' . $result . '&codes=' . implode(',', $codes);
    }
  }
  else // BLOG A FOTO
  {
    if($result >= 0)
    {
      //$fbm->update(array('up_public' => 'Y', 'up_id' => $foto_id));
      if(isset($_REQUEST['make_fotos_public']))
      {
        $fbm->setPrivacyByIds($_USER_ID, $foto_ids, 3333);
        $usm->setFotoPage($_USER_ID, $userData['U_KEY']);
      }
      $url = '/?action=fotobox.foto_post_message&b_ids=' . implode(',', $blogIds) . '&foto_ids=' . $_REQUEST['foto_ids'] . '&result=' . $result;
    }
    else
    {
      $url = '/?action=fotobox.foto_post_message&foto_ids=' . $_REQUEST['foto_ids'] . '&result=' . $result . '&codes=' . implode(',', $codes);
    }
  }
?>