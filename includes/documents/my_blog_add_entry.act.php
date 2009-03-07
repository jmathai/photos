<?php
  $b =& CBlog::getInstance();
  $u =& CUser::getInstance();
  $um=& CUserManage::getInstance();
  
  $userData = $u->find($_USER_ID);
  
  $datePosted = $_POST['datePostedYear'] . '-' . $_POST['datePostedMonth'] . '-' . $_POST['datePostedDay'];
  
  $data = array('ube_u_id' => $_USER_ID, 'ube_subject' => $_POST['ube_subject'], 'ube_body' => $_POST['fck_instance'], 'ube_datePosted' => $datePosted);
  
  $postId = $b->add($data);
  $um->addActivity($_USER_ID, $postId, 'newBlogPost', $userData['U_USERNAME'], $_POST['ube_subject']);
  
  $url = '/users/' . $_POST['username'] . '/blog/entry/' . $postId . '/';
?>