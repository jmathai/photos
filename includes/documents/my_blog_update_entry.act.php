<?php
  $b =& CBlog::getInstance();
  
  $datePosted = $_POST['datePostedYear'] . '-' . $_POST['datePostedMonth'] . '-' . $_POST['datePostedDay'];
  
  $data = array('ube_id' => $_POST['ube_id'], 'ube_u_id' => $_USER_ID, 'ube_subject' => $_POST['ube_subject'], 'ube_body' => $_POST['fck_instance'], 'ube_datePosted' => $datePosted);
  
  $b->update($data);
  
  $url = '/users/' . $_POST['username'] . '/blog/entry/' . $_POST['ube_id'] . '/?updated';
?>