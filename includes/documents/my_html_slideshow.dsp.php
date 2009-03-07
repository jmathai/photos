<?php
  $fl = &CFlix::getInstance();
  
  $key = isset($_GET['key']) ? $_GET['key'] : false;
  $slide = isset($_GET['slide']) ? $_GET['slide'] : 0;
  $slideshowData = $fl->search(array('KEY' => $key));
  $elementsArr = jsonDecode($slideshowData['US_ELEMENTS']);
  
  // if we have a correct slide number
  if((count($elementsArr) > 0) && ($slide < count($elementsArr)))
  {
    // if it's a photo/title/unknown
    if(array_key_exists('photoPath_str', $elementsArr[$slide]))
    { 
      $img = dynamicImageLock($elementsArr[$slide]['thumbnailPath_str'], $elementsArr[$slide]['photoKey_str'], $elementsArr[$slide]['rotation_int'], $elementsArr[$slide]['width_int'], $elementsArr[$slide]['height_int'], 500, 375);
      echo '<div id="_currentSlide" style="float:left; padding-right:10px;"><img src="' . $img[0] . '" border="0" width="' . $img[1] . '" height="' . $img[2] . '" /></div>';
      
      $html = '';
      // if hotspots exist
      if(array_key_exists('hotSpot_arr', $elementsArr[$slide]))
      {
        $html .= '<div style="padding-top:10px;">Hotspots on this photo:</div>';
        foreach($elementsArr[$slide]['hotSpot_arr'] as $k => $v)
        {
          switch($v['swfPath_str'])
          {
            case 'quote.swf':
              $html .= '<div><img src="images/html_slideshow/chat.png" class="png" border="0" width="16" height="16" style="margin:5px 5px 0px 10px;" />"' . $v['note_str'] . '"</div>';
              break;
              
            case 'eye_blood_shot.swf':
              $html .= '<div><img src="images/html_slideshow/bloodshot_eye.png" class="png" border="0" width="16" height="16" style="margin:5px 5px 0px 10px;" />Bloodshot eye</div>';
              break;
              
            case 'eye.swf':
              $html .= '<div><img src="images/html_slideshow/eye.png" class="png" border="0" width="16" height="16" style="margin:5px 5px 0px 10px;" />Eye</div>';
              break;
              
            case 'hair1.swf':
              $html .= '<div><img src="images/html_slideshow/fro.png" class="png" border="0" width="16" height="16" style="margin:5px 5px 0px 10px;" />Fro hair</div>';
              break;
          }
        }
      }
      else 
      {
        $html .= '<div style="padding-top:10px;">No hotspots on this photo</div>';
      }
    }
    else if(array_key_exists('title_str', $elementsArr[$slide]))
    {
      echo '<div id="_currentSlide" style="float:left; width:500px; height:375px; background-color:#' . substr($elementsArr[$slide]['backgroundColor_str'], 2). '; margin-right:10px;"><span style="font-size:36pt; color:#' . substr($elementsArr[$slide]['mainColor_str'], 2) . ';">' . $elementsArr[$slide]['title_str'] . '</span></div>';
    }
    else 
    {
      echo '<div id="_currentSlide" style="float:left;" class="f_10 bold">Preview not available</div>';
    }
    
    // prev/next title
    echo '<div style="float:left; width:75px;" class="center bold">Previous</div>';
    echo '<div style="width:75px; padding-bottom:10px;" class="center bold">Next</div>';
    
    // previous slide
    $prev = $slide - 1;
    if($prev >= 0)
    {
      if(array_key_exists('thumbnailPath_str', $elementsArr[$prev]))
      {
        echo '<div style="float:left; padding-right:5px;"><a href="/users/' . $username . '/html_slideshow/?key=' . $key . '&slide=' . $prev . '"><img src="' . PATH_FOTO . $elementsArr[$prev]['thumbnailPath_str'] . '" border="0" width="75" height="75" /></a></div>';
      }
      else if(array_key_exists('title_str', $elementsArr[$prev]))
      {
        echo '<a style="cursor:pointer;" href="/users/' . $username . '/html_slideshow/?key=' . $key . '&slide=' . $prev . '" class="plain"><div style="float:left; padding-right:5px; width:75px; height:75px; background-color:#' . substr($elementsArr[$prev]['backgroundColor_str'], 2). ';"><span style="font-size:8pt; color:#' . substr($elementsArr[$prev]['mainColor_str'], 2) . ';">' . $elementsArr[$prev]['title_str'] . '</span></div></a>';
      }
      else 
      {
        echo '<a style="cursor:pointer;" href="/users/' . $username . '/html_slideshow/?key=' . $key . '&slide=' . $prev . '" class="plain"><div style="float:left; padding-right:5px; width:75px; height:75px; background-color:#FFFFFF;">Preview not available</div></a>';
      }
    }
    else 
    {
      echo '<div style="float:left; padding-right:5px; width:75px; height:75px;">&nbsp;</div>';
    }
    
    // next slide
    $next = $slide + 1;
    if($next < count($elementsArr))
    {
      if(array_key_exists('thumbnailPath_str', $elementsArr[$next]))
      {
        echo '<div><a href="/users/' . $username . '/html_slideshow/?key=' . $key . '&slide=' . $next . '"><img src="' . PATH_FOTO . $elementsArr[$next]['thumbnailPath_str'] . '" border="0" width="75" height="75" /></a></div>';
      }
      else if(array_key_exists('title_str', $elementsArr[$next]))
      {
        echo '<a style="cursor:pointer;" href="/users/' . $username . '/html_slideshow/?key=' . $key . '&slide=' . $next . '" class="plain"><div style="width:75px; height:75px; background-color:#' . substr($elementsArr[$next]['backgroundColor_str'], 2). ';"><span style="font-size:8pt; color:#' . substr($elementsArr[$next]['mainColor_str'], 2) . ';">' . $elementsArr[$next]['title_str'] . '</span></div></a>';
      }
      else 
      {
        echo '<a style="cursor:pointer;" href="/users/' . $username . '/html_slideshow/?key=' . $key . '&slide=' . $next . '" class="plain"><div style="width:75px; height:75px; background-color:#FFFFFF;">Preview not available</div></a>';
      }
    }
    else 
    {
      echo '<div style="width:75px; height:75px;">&nbsp;</div>';
    }
    
    // the photo's name
    if($elementsArr['name_str'] != '')
    {
      echo '<div style="padding-top:10px;">Name: ' . $elementsArr['name_str'] . '</div>';
    }
    else 
    {
      echo '<div style="padding-top:10px;">Name: No name specified</div>';
    }
      
    // hotspots
    echo $html;
    
    echo '<br clear="all" />';
    //print_r($elementsArr);
  }
  else 
  {
    echo 'This slide does not exist';
  }
  
?>