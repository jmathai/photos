<?php
/*******************************************************************************************
 * Name:  CImageEditor.php
 *
 * Class to handle image editing functions (b&w, sepia, REE, etc...
 *
 * Usage:
 *
 *******************************************************************************************/

class CImageEditor {

 /*******************************************************************************************
  * Description
  *   Class constructor, optionally loads in an image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function CImageEditor()
  {
    $this->_convert   = PATH_EXEC . '/convert';
    $this->_foto_root = PATH_FOTOROOT;
    $this->dbh =& $GLOBALS['dbh'];
  }

 /*******************************************************************************************
  * Description
  *   Load the path and name for an image to use in future calls
  *******************************************************************************************/
  function loadImage($image_id=0, $full_path='') {
    $this->_image_id = $image_id;

    if ($full_path != '') {
      $pos = strrpos($full_path, '/');

      $this->_image_path = substr($full_path, 0, $pos);
      $this->_image_name = substr($full_path, $pos+1);

      $pos = strrpos($this->_image_path, '/');
      $this->_image_stamp= substr($this->_image_path, $pos+1);
    }
  }

 /*******************************************************************************************
  * Description
  *   internal function to run the specified imagemagick command on all viewable images
  *******************************************************************************************/
  function _execute($command, $generate_from_web=false, $save_to_history = true) {
    exec($cmd = "{$this->_convert} {$command} {$this->_foto_root}/thumbnail/{$this->_image_stamp}/{$this->_image_name} {$this->_foto_root}/thumbnail/{$this->_image_stamp}/{$this->_image_name}");
    
    if(isset($this->user_id) && isset($this->_image_id))
    {
      $this->applyToDynamics($command, $this->_image_id, $this->user_id);
    }
    
    if($save_to_history === true || true) {
      $this->_saveToHistory($command);
    }
  }

 /*******************************************************************************************
  * Description
  *   internal function to run the specified imagemagick command on original image
  *******************************************************************************************/
  function _executeOriginal($command) {
    exec("{$this->_convert} {$command} {$this->_foto_root}/original/{$this->_image_stamp}/{$this->_image_name} {$this->_foto_root}/original/{$this->_image_stamp}/{$this->_image_name}");
  }

 /*******************************************************************************************
  * Description
  *   internal function to append to the image history
  *******************************************************************************************/
  function _saveToHistory($command) {
    if(strstr($command, '-crop') === false)
    {
      $sql = "
        UPDATE user_fotos SET
          up_history = CONCAT(IF(up_history IS NULL, '', up_history), '" . addslashes($command) . "\n')
        WHERE up_id = '" . $this->_image_id . "'
      ";
    }
    else
    {
      $tmp_data = $this->dbh->fetch_assoc(
                    $this->dbh->query('SELECT up_history AS HISTORY FROM user_fotos WHERE up_id = ' . $this->_image_id)
                  );

      if(strstr($tmp_data['HISTORY'], '-crop') === false)
      {
        $new_history = $command . "\n" . $tmp_data['HISTORY'];
      }
      else
      {
        $tmp_arr = explode("\n", $tmp_data['HISTORY']);
        foreach($tmp_arr as $k => $v)
        {
          if(strncmp($v, '-crop', 5) == 0)
          {
            unset($tmp_arr[$k]);
            break;
          }

          array_unshift($tmp_arr, $command);
        }

        $new_history = implode("\n", $tmp_arr);
      }

      $sql = "
        UPDATE user_fotos SET
          up_history = '" . addslashes($new_history) . "'
        WHERE up_id = '" . $this->_image_id . "'
      ";
    }
    
    $this->dbh->execute($sql);
  }

 /*******************************************************************************************
  * Description
  *   internal function to completely erase image history
  *******************************************************************************************/
  function _clearHistory() {
    $sql = "
      UPDATE user_fotos SET
        up_history = ''
      WHERE up_id = '" . $this->_image_id . "'
    ";
    $this->dbh->execute($sql);
  }

 /*******************************************************************************************
  * Description
  *   internal function to retrieve history
  *******************************************************************************************/
  function _getHistory() {
    $imageId = func_num_args() == 0 ? $this->_image_id : func_get_arg(0);
    $sql = "
      SELECT up_history
      FROM user_fotos
      WHERE up_id = '" . $imageId . "'
    ";
    
    $row = $this->dbh->query_first($sql);

    $history = explode("\n", $row['up_history']);
    return($history);
  }

 /*******************************************************************************************
  * Description
  *   internal function to retrieve history including rotation as last element
  *******************************************************************************************/
  function _getCompleteHistory() {
    $sql = "
      SELECT up_rotation, up_history
      FROM user_fotos
      WHERE up_id = '" . $this->_image_id . "'
    ";
    $row = $this->dbh->query_first($sql);

    $history = explode("\n", $row['up_history']);
    array_push($history, $row['up_rotation']); // place rotation at end of array
    return($history);
  }
  
  /*******************************************************************************************
  * Description
  *   internal function to completely erase image history
  *******************************************************************************************/
  function _applyHistory()
  {
    if(func_num_args() == 0) // apply to image that's loaded
    {
      $commands = $this->_getHistory();
    }
    else // apply from image @ args[0] to loaded image
    {
      $commands = $this->_getHistory(func_get_arg(0));
    }
    
    foreach($commands as $command)
    {
      if (strlen(trim($command)) > 0)
      {
        //$generate_from_web = strpos($command, '-crop') === false ? false : true;
        $generate_from_web = false;
        $this->_execute($command, $generate_from_web, false);
      }
    }
  }

  /*******************************************************************************************
  * Description
  *   function to apply history to dynamic image
  *******************************************************************************************/
  function applyHistoryToDynamic($src)
  {
    if(is_file($src))
    {
      $commands = $this->_getCompleteHistory();
      $rotation = array_pop($commands);
      
      foreach($commands as $command)
      {
        if (strlen(trim($command)) > 0)
        {
          exec("{$this->_convert} {$command} {$src} {$src}");
        }
      }
      
      if($rotation > 0)
      {
        exec("{$this->_convert} -rotate {$rotation} {$src} {$src}");
      }
    }
  }
  
 /*******************************************************************************************
  * Description
  *   internal function to convert hex to an imagemagick pen color (0-100)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function _hex2Pen($color) {
    $r = round((255 - hexdec(substr($color, 0, 2))) / 2.55);
    $g = round((255 - hexdec(substr($color, 2, 2))) / 2.55);
    $b = round((255 - hexdec(substr($color, 4, 2))) / 2.55);
    
    return "{$r}/{$g}/{$b}";
  }

 /*******************************************************************************************
  * Description
  *   Convert the loaded image to black and white
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function desaturate() {
    $this->_execute($command = '-colorspace GRAY');
  }

 /*******************************************************************************************
  * Description
  *   Rotate the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function rotate($degrees) {
    include_once(PATH_CLASS . '/CFotobox.php');
    include_once(PATH_CLASS . '/CFotoboxManage.php');
    $fb =& CFotobox::getInstance($this->_image_stamp);
    $fbm=& CFotoboxManage::getInstance($this->_image_stamp);

    $tmp_src  = $this->temp('generate');
    $orig_src = $this->_image_path . '/' . $this->_image_name;

    $history  = $this->_getHistory();
    foreach($history as $command)
    {
      if(strncmp($command, '-', 1) == 0)
      {
        $new_command = str_replace($orig_src, $tmp_src, $command);
        exec($cmd = "{$this->_convert} {$new_command} {$tmp_src} {$tmp_src}");
      }
    }

    $cmd = "{$this->_convert} -rotate {$degrees} {$tmp_src} {$tmp_src}";

    exec($cmd);
    $fbm->uploadThumbnail($tmp_src, $this->_image_name);
    
    if(isset($this->user_id) && isset($this->_image_id))
    {
      $this->purgeDynamics($this->_image_id, $this->user_id);
    }
  }

 /*******************************************************************************************
  * Description
  *   Generate temp file
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function temp($action = 'generate', $tmp_file = false) {
    if($action == 'generate')
    {
      copy($this->_image_path . '/' . $this->_image_name, PATH_TMPROOT . '/tmp_' . $this->_image_name);
      $return = PATH_TMPROOT . '/tmp_' . $this->_image_name;
    }
    else
    if(is_file($tmp_file))
    {
      unlink($tmp_file);
      $return = $tmp_file;
    }

    return $return;
  }

 /*******************************************************************************************
  * Description
  *   Convert the loaded image to sepia tone
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function sepia() {
    $this->desaturate();
    $this->_execute($command = '-colorize 0/14/47');
    //$this->_execute('-modulate 110 -colorspace GRAY -colors 256 -gamma 1.25/1/0.66');
    //$this->_execute('convert  -colorize 50');
  }


 /*******************************************************************************************
  * Description
  *   Apply a blur filter to the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function blur($radius=0, $sigma=0) {
    $s_sigma = '';
    if ($sigma > 0) {
      $s_sigma = "x{$sigma}";
    }

    $this->_execute($command = "-blur {$radius}{$s_sigma}");
  }

 /*******************************************************************************************
  * Description
  *   Apply a sharpen filter to the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function sharpen($radius=0, $sigma=0) {
    $s_sigma = '';
    if ($sigma > 0) {
      $s_sigma = "x{$sigma}";
    }

    $this->_execute($command = "-sharpen {$radius}{$s_sigma}");
  }


 /*******************************************************************************************
  * Description
  *   Apply a colorize filter operation on the loaded image
  *******************************************************************************************/
  function colorize($hex_color) {
    $this->desaturate();
    $this->_execute($command = '-colorize ' . $this->_hex2pen($hex_color));
  }

 /*******************************************************************************************
  * Description
  *   Apply an auto contrast filter to the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function autoContrast() {
    $this->_execute($command = '-normalize');
  }

 /*******************************************************************************************
  * Description
  *   Apply a auto color level correction filter to the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function autoLevels() {
    $this->_execute($command = '-equalize');
  }

 /*******************************************************************************************
  * Description
  *   Apply a charcoal filter to the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function charcoal($amount) {
    $this->_execute("-charcoal {$amount}");
  }

 /*******************************************************************************************
  * Description
  *   Crop the loaded image
  * Input
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function crop($foto_id = false, $x1 = 0, $y1 = 0, $x2 = false, $y2 = false) {
    $retval = false;
    if($x2 !== false && $y2 !== false)
    {
      include_once PATH_CLASS . '/CImageMagick.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';

      $tmp_src = $this->temp();

      $fb  =& CFotobox::getInstance();
      $im =& CImageMagick::getInstance();
      $foto_data = $fb->fotoData($foto_id);
      $tmp = explode('/', $foto_data['P_THUMB_PATH']);
      $stamp = $tmp[2];
      $fbm =& CFotoboxManage::getInstance();
      $fbm->setStamp($stamp);
      
      if($foto_data['P_ROTATION'] != 0)
      {
        $im->rotate($tmp_src, $tmp_src, $foto_data['P_ROTATION']);
      }
      $src   = @getimagesize($tmp_src);

      if($src[0] > FF_WEB_WIDTH || $src[1] > FF_WEB_HEIGHT)
      {
        if(($src[0] / FF_WEB_WIDTH) == ($src[1] / FF_WEB_HEIGHT))
        {
          $web_w = FF_WEB_WIDTH;
          $web_h = FF_WEB_HEIGHT;
        }
        else
        if(($src[0] / FF_WEB_WIDTH) > ($src[1] / FF_WEB_HEIGHT))
        {
          $web_w = FF_WEB_WIDTH;
          $web_h = $src[1] * (FF_WEB_WIDTH / $src[0]);
        }
        else
        if(($src[0] / FF_WEB_WIDTH) < ($src[1] / FF_WEB_HEIGHT))
        {
          $web_w = $src[0] * (FF_WEB_HEIGHT / $src[1]);
          $web_h = FF_WEB_HEIGHT;
        }
      }
      else
      {
        $web_w = $src[0];
        $web_h = $src[1];
      }


      $w_ratio = ($src[0] / $web_w);
      $h_ratio = ($src[1] / $web_h);

      $width = abs(($x1 - $x2));
      $height= abs(($y1 - $y2));

      $x = ($x1 < $x2) ? $x1 : $x2;
      $y = ($y1 < $y2) ? $y1 : $y2;

      $x = intval($x * $w_ratio);
      $y = intval($y * $h_ratio);
      $width  = intval($width * $w_ratio);
      $height = intval($height * $h_ratio);


      $cmd = "-crop {$width}x{$height}+{$x}+{$y}";

      exec("{$this->_convert} {$cmd} {$tmp_src} {$tmp_src}");
      $fbm->update(array('up_id' => $foto_id, 'up_width' => $width, 'up_height' => $height));
      $fbm->uploadThumbnail($tmp_src, $this->_image_name);

      $this->_applyHistory();

      $this->_saveToHistory($cmd);
      
      $fbm->removeDynamics($foto_data['P_ID'], $foto_data['P_U_ID'], true); // false user_id true force
      
      $retval = array($width, $height);
    }
    
    return $retval;
  }

 /*******************************************************************************************
  * Description
  *   Apply a invert (negative) filter to the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function invert() {
    $this->_execute($command = '-negate');
  }

 /*******************************************************************************************
  * Description
  *   Apply a bevel filter to the loaded image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function bevel($amount) {
    $this->_execute($command = "-raise {$amount}");
  }

/*******************************************************************************************
  * Description
  *   Applies a R=min(G,B) algorithm to the selected region for red eye reduction
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function redEyeReduction() {

  }

 /*******************************************************************************************
  * Description
  *   Restores an image from the original
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function restore($clear_history=true) {
    include_once PATH_CLASS . '/CFotobox.php';
    include_once PATH_CLASS . '/CFotoboxManage.php';
    $fb =& CFotobox::getInstance();
    $fbm =& CFotoboxManage::getInstance();
    $fbm->setStamp($this->_image_stamp);
    
    $fotoData = $fb->fotoData($this->_image_id);
    
    $size = @getImageSize(PATH_FOTOROOT . $fotoData['P_ORIG_PATH']);
    
    $fbm->update(array('up_id' => $this->_image_id, 'up_width' => $size[0], 'up_height' => $size[1]));
    $fbm->uploadThumbnail($this->_foto_root.'/original/'.$this->_image_stamp.'/'.$this->_image_name, $this->_image_name);
    
    if(isset($this->user_id) && isset($this->_image_id))
    {
      $this->purgeDynamics($this->_image_id, $this->user_id);
    }
    
    if ($clear_history === true) {
      $this->_clearHistory();
    }
  }
  
  function purgeDynamics($photoId = false, $userId = false)
  {
    if($photoId !== false && $userId !== false)
    {
      include_once PATH_CLASS . '/CFotoboxManage.php';
      $fbm =& CFotoboxManage::getInstance();
      $fbm->removeDynamics($photoId, $userId, true);
    }
  }
  
  function applyToDynamics($command = false, $photoId = false, $userId = false)
  {
    if($photoId !== false && $userId !== false)
    {
      include_once PATH_CLASS . '/CFotobox.php';
      $fb =& CFotobox::getInstance();
      $dynamics = $fb->getDynamics($userId, $photoId);
      foreach($dynamics as $v)
      {
        exec($exec = "{$this->_convert} {$command} {$this->_foto_root}{$v['D_SOURCE']} {$this->_foto_root}{$v['D_SOURCE']}");
      }
    }
  }
  
/*******************************************************************************************
  * Description
  *   Sets $this->user_id
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function setUser($user_id = false)
  {
    if($user_id !== false)
    {
      $this->user_id = intval($user_id);
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getInstance
  * Description
  *   Static method to invoke this class
  * Output
  *   Class object
  ******************************************************************************************
  */
  static function &getInstance($user_id = false)
  {
    static $inst = null;
    $class = __CLASS__;
    
    if($inst === null)
    {
      $inst = new $class;
      if($user_id !== false)
      {
        $this->user_id = $user_id;
      }
    }
    
    return $inst;
  }
}
?>