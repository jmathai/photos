<?php
  $width = $_GET['width'];
  $height= $_GET['height'];
  $key   = $_GET['key'];
  $swf   = '/swf/container/container_' . $width . '_' . $height . '.swf';
  if(strlen($key) == 32)
  {
    $swf .= '?slideshowKey_str=' . $key;
  }
?>

<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=7,0,0,0" ID=objects WIDTH="<?php echo $width; ?>" HEIGHT="<?php echo $height; ?>">
<PARAM NAME=movie VALUE="<?php echo $swf; ?>">
<EMBED src="<?php echo $swf; ?>" WIDTH="<?php echo $width; ?>" HEIGHT="<?php echo $height; ?>" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
</OBJECT>
