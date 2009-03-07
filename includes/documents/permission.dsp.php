<?php
  if($_FF_SESSION->value('permissions')) // check if permissions is set
  {
    $permissionArray = array(
                          'flix.flix_list' => 'flix'
                        );
    
    include_once PATH_CLASS . '/CPermission.php';
    
    $prm =& CPermission::getInstance();
    
    if(!$prm->hasPermission($_FF_SESSION->value('sub_account_id'), $permissionArray[$action], 'R')) // check of read (R) permission is NOT set
    {
      $tpl->mode('single');
      $tpl->kill('Sorry, you do not have access to this page.');      
    }
  }
?>