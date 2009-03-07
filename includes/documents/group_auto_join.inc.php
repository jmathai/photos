<?php
  $auto_join = false;
  $wrong_user  = false;
  
  if(isset($_GET['reference_id']))
  {
    $reference_id = $_GET['reference_id']; 
  }
  
  if($logged_in === true)
  {
    $g =& CGroup::getInstance();
    $u =& CUser::getInstance();
    
    $check_data = $g->inviteData($reference_id);
    $user_data  = $u->find($_USER_ID);
    
    if($check_data['I_EMAIL'] == $user_data['U_EMAIL'])
    {
      $auto_join  = true;
    }
    else
    {
      $wrong_user = true;
    }
  }
?>