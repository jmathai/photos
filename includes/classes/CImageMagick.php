<?php
 /*******************************************************************************************
  * Name:  CImageMagick.php
  *
  * Image manipulation class
  *
  * Usage:
  *
  *******************************************************************************************/
class CImageMagick
{
  /*******************************************************************************************
  * Description
  *   scales an image
  *
  * Input
  *   height  int
  *   width   int
  *   lock    bool
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function scale($width = false, $height = false, $dest = false, $larger = false)
  {
    $proceed = true;

    if($larger === false)
    {
      $arrImage              =   @getimagesize($this->image);
      $i_width               =   $arrImage[0];
      $i_height              =   $arrImage[1];
      if($i_width <= $width && $i_height <= $height)
      {
        $proceed = false;
      }
    }

    if($proceed === true)
    {
      $width = intval($width);
      $height= intval($height);


      $quality = 90; // vree said so
      if($dest === false)
      {
        $command = "{$this->_path_exe}/convert -size {$width}x{$height} -filter Lanczos -quality {$quality} +profile \"*\" -resize \"{$width}x{$height}>\" {$this->image} {$this->image}";
      }
      else
      {
        $command = "{$this->_path_exe}/convert -size {$width}x{$height} -filter Lanczos -quality {$quality} +profile \"*\" -resize \"{$width}x{$height}>\" {$this->image} {$dest}";
      }
      //echo "<br/>$command<br/>";
      exec($command);
      return true;
    }
    else
    {
      return false;
    }
  }
  
  /*******************************************************************************************
  * Description
  *   crops an image
  *
  * Input
  *   height  int
  *   width   int
  *   dest    string
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function crop($width = false, $height = false, $dest = false)
  {
    $quality = 90; // vree said so
    $imageInfo = getimagesize($this->image);
    $sRatio = floatval($imageInfo[0] / $imageInfo[1]);
    $dRatio = floatval($width / $height);
    if($dRatio > $sRatio) // crop width
    {
      $dWidth = $imageInfo[0];
      $dHeight= $height * floatval($imageInfo[0] / $width);
    }
    if($dRatio < $sRatio) // crop height
    {
      $dWidth = $width * floatval($imageInfo[1] / $height);
      $dHeight= $imageInfo[1];
    }
    
    
    if($dest === false)
    {
      $cmd    = "{$this->_path_exe}/mogrify -gravity Center -crop {$dWidth}x{$dHeight}+0+0 {$this->image}";
    }
    else
    {
      $cmd    = "{$this->_path_exe}/convert -gravity Center -crop {$dWidth}x{$dHeight}+0+0 {$this->image} {$dest}";
    }

    exec($cmd); // perform initial crop maintaining source size
    
    $finalSrc = $dest === false ? $this->image : $dest;
    $command = "{$this->_path_exe}/mogrify -size {$width}x{$height} -filter Lanczos -quality {$quality} +profile \"*\" -resize \"{$width}x{$height}>\" {$finalSrc}";
    exec($command); // perform resize to make image destination size
  }
  
  /*******************************************************************************************
  * Description
  *   Composite images
  *
  * Input
  *   arr_src   array
  *   dest      string
  *   x_offset  int
  *   y_offset  int
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function composite($arr_src, $dest, $x_offset, $y_offset)
  {
    $continue = true;
    if(count($arr_src) == 2)
    {
      $img_1_info = @getimagesize($arr_src[0]);
      $img_2_info = @getimagesize($arr_src[1]);
      if($img_1_info[0] == $img_2_info[0] && $img_1_info[1] == $img_2_info[1])
      {
        $continue = false;
      }
    }

    if($continue === true)
    {
      $images   = implode(' ', $arr_src);
      //$command  = "{$this->_path_exe}/composite -geometry +{$x_offset}+{$y_offset} {$images} {$dest}";
      $command  = "{$this->_path_exe}/composite -geometry +{$x_offset}+{$y_offset} {$images} {$dest}";
      exec($command);
    }

    return true;
  }

  /*******************************************************************************************
  * Description
  *   Crop an image to a square
  *
  * Input
  *   src       string
  *   dest      string
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function square($src = false, $dest = false)
  {
    $dest = $dest !== false ? $dest : $src;

    $imageInfo = @getimagesize($src);

    if($imageInfo !== false)
    {
      $floor  = min($imageInfo[0], $imageInfo[1]);
      $cmd    = "{$this->_path_exe}/convert -gravity Center -crop {$floor}x{$floor}+0+0 {$src} {$dest}";
      //$cmd    = "convert -gravity Center -crop {$floor}x{$floor}+0+0 {$src} {$dest}";
      exec($cmd);
    }
  }


  function rotate($src, $dest, $degrees)
  {
    //$cmd = "{$this->_path_exe}/convert -rotate {$degrees} {$src} {$dest}";
    $cmd = "convert -rotate {$degrees} {$src} {$dest}";
    exec($cmd);
  }

  function convert($src, $dest, $extras = false)
  {
    //$cmd = "{$this->_path_exe}/convert {$src} {$dest}";
    $cmd = "{$this->_path_exe}/convert {$src} {$dest}";
    exec($cmd);
  }
  
  function exiftran($src = false, $dest = false)
  {
    //$cmd = "{$this->_path_exe}/convert {$src} {$dest}";
    if($dest === false)
    {
      $cmd = "{$this->_path_exe}/exiftran -ai {$src}";
    }
    else
    {
      $cmd = "{$this->_path_exe}/exiftran -a {$src} -o {$dest}";
    }
    exec($cmd);
  }
  
  /*******************************************************************************************
  * Description
  *   sets a source image
  *
  * Input
  *   src   string
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function image($src)
  {
    $this->image = $src;
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
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;

    if($inst === null)
    {
      $inst      = new $class;
      $inst->_path_exe = PATH_EXEC;
    }

    return $inst;
  }

  /*******************************************************************************************
  * Description
  *   Constructor
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function CImageMagick()
  {
  }
}
?>
