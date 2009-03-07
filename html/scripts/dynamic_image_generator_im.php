<?php
  include_once '../init_constants.php'; // this can stay since it's only called via web
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CFotobox.php';
  include_once PATH_CLASS . '/CFotoboxManage.php';
  include_once PATH_CLASS . '/CImageEditor.php';
  include_once PATH_CLASS . '/CImageMagick.php';
  
  if(!empty($_SERVER['REDIRECT_URL'])) // apache
  {
    $requestPath = $_SERVER['REDIRECT_URL'];
    $iExt = substr($requestPath, strrpos($requestPath, '.'));
    $imageKey = $_SERVER['REDIRECT_QUERY_STRING'];
  }
  else // lighty
  {
    $requestPath = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
    $iExt = substr($requestPath, strrpos($requestPath, '.'));
    $imageKey = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?')+1);
  }
  
  // parse image attributes
  $iDest  = $requestPath;
  $iDestRoot = PATH_HOMEROOT . $iDest;
  $iParts = explode('_', dirname($iDest) . '/' . basename($iDest, $iExt));
  $iHeight = ceil(array_pop($iParts));
  $iWidth  = ceil(array_pop($iParts));
  
  // make a copy of the original so we can apply history
  $fullPath = PATH_HOMEROOT . str_replace('/custom/', '/original/', implode('_', $iParts) . $iExt);
  
  // make the imageKey 32 chars
  $imageKey = substr($imageKey, 0, 32);
  
  if(file_exists($fullPath) && strlen($imageKey) == 32 && (strcasecmp('.jpg', $iExt) == 0 || strcasecmp('.jpeg', $iExt) == 0))
  {
    $ie =& CImageEditor::getInstance();
    $im =& CImageMagick::getInstance();
    $fb =& CFotobox::getInstance();
    $fbm=& CFotoboxManage::getInstance();
    
    // check to see if a "base" version exists and generate from that
    if(file_exists($basePath = str_replace('/original/', '/base/', $fullPath)))
    {
      $fullPath = $basePath;
    }
    
    // make a copy of the original so we can apply history
    copy($fullPath, $tmpPath = PATH_HOMEROOT . PATH_TMP . '/' . md5($_SERVER['REDIRECT_QUERY_STRING'] . $_SERVER['REQUEST_URI']) . '-' . mt_rand());
    
    $fotoData = $fb->fotoData($imageKey);
    
    /*if($fotoData['P_ROTATION'] > 0)
    {
      $im->rotate($tmpPath, $tmpPath, $fotoData['P_ROTATION']);
    }*/
    
    $ie->setUser($fotoData['P_U_ID']);
    $ie->loadImage($fotoData['P_ID'], $tmpPath);
    
    $ie->applyHistoryToDynamic($tmpPath);
    
    $fbm->addDynamic($fotoData['P_U_ID'], $fotoData['P_ID'], str_replace(PATH_FOTO, '', $iDest), $iWidth, $iHeight);
    $im->image($tmpPath);
    $im->crop($iWidth, $iHeight, $iDestRoot);
    
    header('HTTP/1.1 200 OK');
    header('Content-type: image/jpeg');
    unlink($tmpPath);
    readfile($iDestRoot);
    die();
  }
  
  header('HTTP/1.0 404 Not Found');
  die();
?>