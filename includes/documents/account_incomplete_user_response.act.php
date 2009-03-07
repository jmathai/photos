<?php
  $u  =& CUser::getInstance();
  $um =& CUserManage::getInstance();
  
  $userData = $u->findIncomplete($_GET['key']);
  
  $url = '/?action=account.incomplete_user_response&key=' . $_GET['key'];
  
  $data = array('uir_u_id' => $userData['U_ID']);
  if(isset($_GET['response'])) // from email
  {
    $data['uir_response'] = $incompleteResponses[$_GET['response']];
  }
  
  if(isset($_POST['uir_customResponse'])) // from form
  {
    $data['uir_customResponse'] = $_POST['uir_customResponse'];
    $url = '/?action=confirm.main&type=incomplete_user_response';
  }
  
  $um->addIncompleteReason($data);
  
  //$url set above
?>