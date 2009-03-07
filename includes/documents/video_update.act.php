<?php
  $v = CVideo::getInstance();
  
  $params = array('v_id' => $_POST['v_id'], 'v_name' => $_POST['name'], 'v_tags' => $_POST['tags'], 'v_description' => $_POST['description'], 'v_privacy' => $_POST['privacy']);
  
  $v->update($params);
  
  $url = '/?action=video.list&message=updated&updated=' . $_POST['v_id'];
?>