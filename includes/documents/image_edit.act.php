<?php
  $f  =& CFotobox::getInstance();
  $fb =& CFotoboxManage::getInstance();
  $ie =& CImageEditor::getInstance();
  
  $image_id = isset($_GET['image_id']) ? $_GET['image_id'] : false;
  
  $image_data = array('up_id' => $image_id);
  
  if($image_id !== false)
  {
    $foto_data = $f->fotoData($image_id, $_USER_ID);
    
    $ie->loadImage($image_id, PATH_FOTOROOT . $foto_data['P_ORIG_PATH']);
    $ie->setUser($_USER_ID);
    
    switch($_GET['method'])
    {
      case 'restore':
        $ie->restore();
        $image_data['up_rotation'] = 0;
        break;
      case 'rotate':
        $degrees = rotation($_GET['original'], $_GET['args']);
        $ie->rotate($degrees);
        $image_data['up_rotation'] = $degrees;
        break;
      case 'auto_contrast':
        $ie->autoContrast();
        break;
      case 'bw':
        $ie->desaturate();
        break;
      case 'sepia':
        $ie->sepia();
        break;
      case 'blur':
        $ie->blur($_GET['args']);
        break;
      case 'sharpen':
        $ie->sharpen($_GET['args']);
        break;
      case 'invert':
        $ie->invert();
        break;
      case 'colorize':
        $ie->colorize($_GET['args']);
        break;
      case 'charcoal':
        $ie->charcoal($_GET['args']);
        break;
    }
    
    $fb->update($image_data);
    
    $url = "/?action=fotobox.image_form&image_id={$_GET['image_id']}&message=image_updated";
  }
  else
  {
    $url = "/?action=fotobox.image_form&image_id={$_GET['image_id']}&message=image_error";
  }
  die();
?>
