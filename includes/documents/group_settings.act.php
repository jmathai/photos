<?php
  $gm = &CGroupManage::getInstance();
    
  $group_id = $_POST['group_id'];
  
  $headerTitle = $_POST['_headerTitle'];
  $headerDescription = $_POST['_headerDescription'];
  $rightColumnTitle = $_POST['_rightColumnTitle'];
  $rightColumnTags = $_POST['_rightColumnTags'];
  $siteColors = $_POST['p_colors'];
  
  $prefs = array('RIGHT_TITLE' => $rightColumnTitle, 'RIGHT_TAGS' => $rightColumnTags, 'SITE_COLORS' => $siteColors);
  $gm->setPrefs($group_id, $prefs);
  
  $data = array('g_id' => $group_id, 'g_name' => $headerTitle, 'g_description' => $headerDescription);
  $gm->update($data);
  
  $url = '/?action=group.settings&group_id=' . $group_id;
?>