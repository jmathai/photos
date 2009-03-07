<?php
  $fb =& CFotobox::getInstance();
  $fl =& CFlix::getInstance();
  $facebook =& CFacebook::getInstance();
  
  if(!isset($_GET['auth_token']))
  {
    echo 'Please <a href="' . $facebook->login_url . '">login</a> at facebook.';
  }
  else
  {
    if($_FF_SESSION->value('facebook_session_key') == '')
    {
      $session = $facebook->getSession($_GET['auth_token']);
      $sessionArr = simplexml_load_string($session);
      $fSessionKey = (string) $sessionArr->session_key;
        $tmp = explode('-', $fSessionKey);
      $fUserId    = $tmp[1];
      $_FF_SESSION->register('facebook_session_key', $fSessionKey);
      $_FF_SESSION->register('facebook_uid', $fUserId);
    }
    else
    {
      $fUserId = $_FF_SESSION->value('facebook_uid');
      $fSessionKey = $_FF_SESSION->value('facebook_session_key');
    }
    
    if($fUserId > 0)
    {
      $photos = $fb->fotosSearch(array('USER_ID' => $_USER_ID, 'ORDER' => 'P_TAKEN_BY_DAY', 'LIMIT' => '25'));
      $slideshows = $fl->search(array('USER_ID' => $_USER_ID, 'ORDER_BY' => 'modified', 'LIMIT' => '5'));
      $friends = $facebook->getFriends($fUserId, $fSessionKey);
      $friendsArr = simplexml_load_string($friends);
      
      echo '<div style="float:left; width:600px;">
              Slideshows<br/>
              <div id="slideshows">';
      foreach($slideshows as $v)
      {
        echo $v['US_NAME'] . '<br/>';
      }
      echo '  </div>
            </div>';
      
      echo '<div style="float:left; width:250px;">';
      foreach($friendsArr->user as $k => $v)
      {
        echo $v->name . ' <br/> <img src="' . $v->pic . '" /><br/><hr><br/>';
      }
      echo '</div>';
    }
    else
    {
      $_FF_SESSION->unregister('facebook_session_key');
      $_FF_SESSION->unregister('facebook_uid');
      echo 'Please <a href="' . $facebook->login_url . '">login</a> again at facebook.';
    }
  }
?>

