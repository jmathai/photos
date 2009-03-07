<?php
  switch($_COOKIE['ff_uploader_preference'])
  {
    case '0': // html uploader
      $url = '/?action=fotobox.upload_form_html'; // html uploader
      break;
    case '1': // activex uploader
      $url = '/?action=fotobox.upload_form';
      break;
    case '2': // java uploader
      $url = '/?action=fotobox.upload_form_compat';
      break;
    default:
      if(strstr($_SERVER["HTTP_USER_AGENT"], 'MSIE'))
      {
        $url = '/?action=fotobox.upload_form'; // activex uploader
      }
      else 
      {
        $url = '/?action=fotobox.upload_form_compat'; // java uploader
      }
      break;
  }
?>