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
    
    copy($fullPath, $tmpPath = PATH_HOMEROOT . PATH_TMP . '/' . md5($_SERVER['REDIRECT_QUERY_STRING'] . $_SERVER['REQUEST_URI']) . '-' . mt_rand());
    
    $fotoData = $fb->fotoData($imageKey);
    
    $ie->setUser($fotoData['P_U_ID']);
    $ie->loadImage($fotoData['P_ID'], $tmpPath);
    
    $ie->applyHistoryToDynamic($tmpPath);
    
    $fbm->addDynamic($fotoData['P_U_ID'], $fotoData['P_ID'], str_replace(PATH_FOTO, '', $iDest), $iWidth, $iHeight, $_SERVER['REMOTE_ADDR']);
    
    // create handles for temp file and output file
    $iOrigHandle = imagecreatefromjpeg($tmpPath);
    
    $iOriginalWidth = imagesx($iOrigHandle);
    $iOriginalHeight= imagesy($iOrigHandle);
    
    $origProportion = floatval($iOriginalWidth / $iOriginalHeight);
    $destProportion = floatval($iWidth / $iHeight);
    
    // check and see if the image needs cropping
    if($origProportion > $destProportion) // crop the width
    {
      $srcW  = intval($iOriginalHeight * $iWidth / $iHeight);
      $srcH  = $iOriginalHeight;
      $destX = intval(($iOriginalWidth - $srcW) / 2);
      $destY = 0;
    }
    else
    if($origProportion < $destProportion) // crop the height
    {
      $srcW  = $iOriginalWidth;
      $srcH  = intval($iOriginalWidth * $iHeight / $iWidth);
      $destX = 0;
      $destY = intval(($iOriginalHeight - $srcH) / 2);
    }
    else // aspect ratio matches
    {
      $destY = 0;
      $destX = 0;
      $srcW  = $iOriginalWidth;
      $srcH  = $iOriginalHeight;
    }
    
    
    // check to see if the requested image is bigger/smaller than the original
    // if bigger then set the destination width/height to be the same as original
    // file_800x800.jpg could in reality be a 400x400 image
    if($iOriginalWidth < $iWidth && $iOriginalHeight < $iHeight)
    {
      $iWidth  = $srcW = $iOriginalWidth;
      $iHeight = $srcH = $iOriginalHeight;
    }
    
    // create an image with width = $iWidth and height = $iHeight
    $iDestHandle = imagecreatetruecolor($iWidth, $iHeight);
    
    /*
    ********************* breakdown of variables *********************
    * $iDestHandle = handle of the destination image (to be created)
    * $iOrigHandle = handle of the source image
    * 0            = x coordinate of the destination image to be copied to
    * 0            = y coordinate of the destination image to be copied to
    * $destX       = x coordinate of the source image to be copied from
    * $destY       = y coordinate of the source image to be copied from
    * $iWidth      = width of the destination image
    * $iHeight     = height of the destination image
    * $srcW        = width of the source image
    * $srcH        = height of the source image
    */
    
    header('HTTP/1.1 200 OK');
    header('Content-type: image/jpeg');
    header('Cache-Control: max-age=3, must-revalidate'); // cache for 3 seconds - use this to force updates on newly generated - does not affect static content
    imagecopyresampled($iDestHandle, $iOrigHandle, 0, 0, $destX, $destY, $iWidth, $iHeight, $srcW, $srcH);
    imagejpeg($iDestHandle, $iDestRoot, 90);
    unlink($tmpPath);
    readfile($iDestRoot);
    die();
  }
 
  header('HTTP/1.0 404 Not Found');
  echo 'File not found';
  die();
?>