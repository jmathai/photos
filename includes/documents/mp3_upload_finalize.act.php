<?php
  $us =& CUser::getInstance();
  $fb =& CFotobox::getInstance();
  $fbm=& CFotoboxManage::getInstance();
  
  if(isset($_REQUEST['user_enc']) && isset($_REQUEST['sid']))
  {
    $enc = $us->userIdDec($_REQUEST['user_enc']);
    
    if($enc['match'] === false)
    {
      die();
    }
    else
    {
      $_USER_ID = $enc['id'];
      $sid = $_REQUEST['sid'];
      $qstr = file_get_contents($qstringFile = '/tmp/' . $sid . '_qstring');
      unlink($qstringFile);
      
      parse_str($qstr);
      
      $mp3_name = $um_name;
      
      $uploadedFiles = array('name' => $file['name'][0], 'size' => $file['size'][0], 'tmp_name' => $file['tmp_name'][0]);
    }
  }
  
  $file_name = $_USER_ID . '_' . NOW . '.mp3';
  $file_path = '/mp3/' . FF_YM_STAMP . '/' . $file_name;

  $src  = $uploadedFiles['tmp_name'];
  $dest = PATH_FOTOROOT . $file_path;
  
  $file_size = ceil(filesize($src) / KB);
  
  $data = array(
            'um_u_id' => $_USER_ID,
            'um_name' => $mp3_name,
            'um_size' => $file_size,
            'um_path' => $file_path
          );
  $fbm->uploadMp3($src, $dest, $data);
  $url = 'http://' . FF_SERVER_NAME . '/popup/mp3_manage/uploaded';
?>