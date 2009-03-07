<?php

//
// class.cropinterface.php
// version 1.0.0, 28th November, 2003
//
// Andrew Collington, 2003
// php@amnuts.com, http://php.amnuts.com/
//
// Description
//
// This class allows you to use all the power of the crop canvas
// class (class.cropcanvas.php) with a very easy to use and understand
// user interface.
//
// Using your browser you can drag and resize the cropping area and
// select if you want to resize in any direction or proportional to
// the image.
//
// If you wanted to provide users a cropping area without any resizing
// options, then this can easily be acheived.
//
// Requirements
//
// You will need the crop canvas class also from http://php.amnuts.com/
// The cropping area implements the 'Drag & Drop API' javascript by
// Walter Zorn (http://www.walterzorn.com/dragdrop/dragdrop_e.htm).
//
// Feedback
//
// There is message board at the following address:
//
//    http://php.amnuts.com/forums/index.php
//
// Please use that to post up any comments, questions, bug reports, etc.
// You can also use the board to show off your use of the script.
//
// Support
//
// If you like this script, or any of my others, then please take a moment
// to consider giving a donation.  This will encourage me to make updates
// and create new scripts which I would make available to you, and to give
// support for my current scripts.  If you would like to donate anything,
// then there is a link from my website to PayPal.
//
// Example of use
//
//  require_once 'class.cropinterface.php';
//  $ci = new cropInterface();
//  if ($_GET['file']) {
//    $ci->loadImage($_GET['file']);
//    $ci->cropToDimensions($_GET['sx'], $_GET['sy'], $_GET['ex'], $_GET['ey']);
//    header('Content-type: image/jpeg');
//    $ci->showImage('jpg', 100);
//    exit;
//  } else {
//    $ci->loadInterface('myfile.jpg');
//    $ci->loadJavaScript();
//  }
//


class CCropInterface
{
	/*var $file;
	var $img;
	var $crop;
	var $useFilter;*/


	/**
	* @return cropInterface
	* @param bool $debug
	* @desc Class initializer
	*/
	function CCropInterface($image_id = false, $debug = false)
	{
		//parent::canvasCrop($debug);

    if($image_id === false)
    {
      die('No image_id specified.');
    }

		$this->img  = array();
    $this->image_id = $image_id;
		$this->crop = array();
		$this->useFilter = false;

		$agent = trim($_SERVER['HTTP_USER_AGENT']);
		if ((stristr($agent, 'wind') || stristr($agent, 'winnt')) && (preg_match('|MSIE ([0-9.]+)|', $agent) || preg_match('|Internet Explorer/([0-9.]+)|', $agent)))
		{
			$this->useFilter = true;
		}
		else
		{
			$this->useFilter = false;
		}
	}

	/**
	* @return void
	* @param unknown $do
	* @desc Sets whether you want resizing options for the cropping area.
	* This is handy to use in conjunction with the setCropSize function if you want a set cropping size.
	*/
	function setResizing($do = true)
	{
		$this->crop['resize'] = ($do) ? true : false;
	}

	/**
	* @return void
	* @param unknown $do
	* @desc Sets whether you want rescaling turned on or off
	* ADDED BY JM
	*/
	function setRescalble($do = true)
	{
		$this->crop['rescalable'] = ($do) ? true : false;
	}


	/**
	* @return void
	* @param int $w
	* @param int $h
	* @desc Sets the initial size of the cropping area.
	* If this is not specifically set, then the cropping size will be a fifth of the image size.
	*/
	function setCropDefaultSize($w, $h)
	{
		$this->crop['width']  = ($w < 40) ? 40 : $w;
		$this->crop['height'] = ($h < 30) ? 30 : $h;
	}


	/**
	* @return void
	* @param int $w
	* @param int $h
	* @desc Sets the minimum size the crop area can be
	*/
	function setCropMinSize($w = 25, $h = 25)
	{
		$this->crop['min-width']  = ($w < 5) ? 5 : $w;
		$this->crop['min-height'] = ($h < 5) ? 5 : $h;
	}


	/**
	* @return void
	* @param string $filename
	* @desc Load the cropping interface
	*/
	function loadInterface($filename, $src)
	{
    $src_encoded = urlencode($src);
		if (!file_exists(PATH_FOTOROOT . $filename))
		{
			die('The file ' . PATH_FOTOROOT . $filename . ' cannot be found.');
		}
		else
		{
			$this->filename_full = PATH_FOTOROOT . $filename;
			$this->filename_local = PATH_FOTO . $filename;
			$this->img['sizes'] = @getimagesize($this->filename_full);
			if (!$this->crop['width'] || !$this->crop['height'])
			{
				$this->setCropDefaultSize(($this->img['sizes'][0] / 5), ($this->img['sizes'][1] / 5));
			}
		}
		echo '<script type="text/javascript" src="/js/wz_dragdrop.js"></script>', "\n";
		echo '<div id="theCrop" style="position:absolute;background-color:transparent;border:1px dashed black;width:', $this->crop['width'], 'px;height:', $this->crop['height'], 'px;';
		if ($this->useFilter)
		{
			echo 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'/images/transbg.png\',sizingMethod=\'scale\');';
		}
		else
		{
			echo 'background-image:url(/images/transbg.png);';
		}
		echo "\"></div>\n";
		echo '<table border="1" style="border-collapse:collapse; border:1px solid black; width:', $this->img['sizes'][0], 'px;" align="center">';
		echo '<tr><td align="center" style="padding:5px;"><!--<strong>(', $this->img['sizes'][0], ' x ', $this->img['sizes'][1], ')</strong>-->';
		if ($this->crop['resize'])
		{
			echo 'Hold down either the shift or control button to resize the cropping area';
		}
		echo "</td></tr>\n";
		echo '<tr><td><img src="/temp_image?src=' .  $src_encoded . '" ' . $this->img['sizes'][3] . ' alt="hold shift or control and drag the mouse to crop" name="theImage"></td></tr>' . "\n";
		if ($this->crop['resize'] && false)
		{
			echo '<tr><td align="center" style="font-size:11px;vertical-align:middle;padding:5px;"><input type="radio" id="resizeAny" name="resize" onClick="my_SetResizingType(0);" checked> <label for="resizeAny">Any Dimensions</label> &nbsp; <input type="radio" name="resize" id="resizeProp" onClick="my_SetResizingType(1);"> <label for="resizeProp">Proportional</label></td></tr>', "\n";
		}
		echo '
		        <tr>
		          <td align="center">
		            <table border="0">
		              <tr>
		                <td valign="middle">
                      Switch crop mode: ';
		if($this->crop['rescalable'] === true) // free
		{
      echo '<span class="bold italic">free</span> | <a href="' . $_SERVER['PHP_SELF'] . '?action=image_crop&image_id=' . $this->image_id . '&edit_mode=slideshow">slideshow (</a>';
		}
		else // flix
		{
		  echo '<a href="' . $_SERVER['PHP_SELF'] . '?action=image_crop&image_id=' . $this->image_id . '&edit_mode=free">free</a> | <span class="bold italic">slideshow</span>';
		}
		echo '
		                  (<a href="javascript:_open(\'/popup/crop_mode\', 300, 150, \'crop_mode\');">what\'s this?</a>)
		                </td>
		                <td valign="middle"><input type="image" src="/images/buttons/crop_button.gif" id="submit" hspace="15" vspace="3" onClick="my_Submit();"></td>
		              </tr>
		            </table>
  		        </td>
            </tr>
      		</table>';

	}


	/**
	* @return void
	* @desc Load the javascript required for a functional interface.
	* This MUST be called at the very end of all your HTML, just before the closing body tag.
	*/
	function loadJavaScript()
	{
    $params = '"theCrop"+MAXOFFLEFT+0+MAXOFFRIGHT+' . $this->img['sizes'][0] . '+MAXOFFTOP+0+MAXOFFBOTTOM+' . $this->img['sizes'][1] . ($this->crop['resize'] ? '+RESIZABLE' : '') . ($this->crop['rescalable'] === false ? '+SCALABLE' : '') . '+MAXWIDTH+' . $this->img['sizes'][0] . '+MAXHEIGHT+' . $this->img['sizes'][1] . '+MINHEIGHT+' . $this->crop['min-height'] . '+MINWIDTH+' . $this->crop['min-width'] . ',"theImage"+NO_DRAG';
		echo <<< EOT
<script type="text/javascript">
<!--

	SET_DHTML($params);

	dd.elements.theCrop.moveTo(dd.elements.theImage.x, dd.elements.theImage.y);
	dd.elements.theCrop.setZ(dd.elements.theImage.z+1);
	dd.elements.theImage.addChild("theCrop");
	dd.elements.theCrop.defx = dd.elements.theImage.x;

	function my_DragFunc()
	{
		dd.elements.theCrop.maxoffr = dd.elements.theImage.w - dd.elements.theCrop.w;
		dd.elements.theCrop.maxoffb = dd.elements.theImage.h - dd.elements.theCrop.h;
		dd.elements.theCrop.maxw    = {$this->img['sizes'][0]};
		dd.elements.theCrop.maxh    = {$this->img['sizes'][1]};
	}

	function my_ResizeFunc()
	{
		dd.elements.theCrop.maxw = (dd.elements.theImage.w + dd.elements.theImage.x) - dd.elements.theCrop.x;
		dd.elements.theCrop.maxh = (dd.elements.theImage.h + dd.elements.theImage.y) - dd.elements.theCrop.y;
	}

	function my_Submit()
	{
		self.location.href = '{$_SERVER['PHP_SELF']}?action=image_crop.act&image_id={$this->image_id}&sx=' +
			(dd.elements.theCrop.x - dd.elements.theImage.x) + '&sy=' +
			(dd.elements.theCrop.y - dd.elements.theImage.y) + '&ex=' +
			((dd.elements.theCrop.x - dd.elements.theImage.x) + dd.elements.theCrop.w) + '&ey=' +
			((dd.elements.theCrop.y - dd.elements.theImage.y) + dd.elements.theCrop.h);
	}

	function my_SetResizingType(proportional)
	{
		if (proportional)
		{
			dd.elements.theCrop.scalable  = 1;
			dd.elements.theCrop.resizable = 0;
		}
		else
		{
			dd.elements.theCrop.scalable  = 0;
			dd.elements.theCrop.resizable = 1;
		}
	}

//-->
</script>
EOT;
		}
}

?>
