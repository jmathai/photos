<?php
  if($options[0] == '')
  {
    $t =& CTag::getInstance();
    $tagsArr = $t->tags($user_id, 'TAG');
    $min = $tagsArr[0]['MIN'];
    $max = $tagsArr[0]['MAX'];
    $step= ($max - $min) / 5;
    
    if($variance == 0)
    { 
      $variance = 1;
    }
    
    echo '<div class="bold"></div>
          <div style="padding:5px; text-align:justify;">';
    foreach($tagsArr as $k => $v)
    {
      if($v['TAG'] != '')
      {
        $fontSize = tagsize(intval($v['WEIGHT']), $step);
        echo '&nbsp;<a href="/users/' . $username . '/tags/' . $v['TAG'] .'/" style="line-height:30px; font-size:' . $fontSize . 'px;" title="' . $v['TAG_COUNT'] . ' items tagged with ' . $v['TAG'] . '" class="plain">' . $v['TAG'] . '</a></span>&nbsp; ';
      }
    }
    echo '</div>';
  }
  else
  {
    $fb =& CFotobox::getInstance();
    $fl =& CFlix::getInstance();
    
    $tagsArr = (array)explode(',', $options[0]);
    
    $arrFotos = $fb->fotosSearch(array('USER_ID' => $user_id, 'TAGS' => $tagsArr, 'PERMISSION' => PERM_PHOTO_PUBLIC, 'ORDER' => 'P_TAKEN_BY_DAY', 'LIMIT' => 8));
    $arrFlix  = $fl->search(array('USER_ID' => $user_id, 'TYPE' => 'slideshow', 'TAGS' => $tagsArr, 'PERMISSION' => PERM_SLIDESHOW_PUBLIC, 'ORDER' => 'P_TAKEN', 'LIMIT' => 3));
    //$arrFotos = $fb->fotosByTags($tagsArr, $user_id, 3, 'P_MOD_YMD', 0, 10);
    //$arrFlix  = $fl->flixByTags($tagsArr, $user_id, 3, 'user', false, 0, 4);
    
    echo '<div style="padding-left:10px; padding-bottom:8px;">Photos tagged with: ' . $options[0] . '<span style="font-weight:normal;">&nbsp;(<a href="/users/' . $username . '/photos/tags-' . $options[0] . '/">all photos tagged with ' . $options[0] . '</a>)</span></div>
          <div>';
    if(count($arrFotos) > 0)
    {
      $i = 0;
      foreach($arrFotos as $v)
      {
        $url = '/users/' . $username . '/photo/' . $v['P_ID'] . '/tags-' . $options[0] . '/?offset=' . $i;
        
        //$fotoUrl = dynamicImage($v['P_THUMB_PATH'], $v['P_KEY'], 150, 100);
        $fotoInfo = dynamicImageLock($v['P_THUMB_PATH'], $v['P_KEY'], $v['P_ROTATION'], $v['P_WIDTH'], $v['P_HEIGHT'], 150, 150);
        $imageHspace = intval((150 - $fotoInfo[1]) / 2);
        $imageVspace = intval((150 - $fotoInfo[2]) / 2);
        echo '<div style="float:left; padding:12px; width:150px; height:150px;">
                  <a href="' . $url . '"><img src="' . $fotoInfo[0] . '" ' . $fotoInfo[3] . ' border="0" class="border_dark" hspace="' . $imageHspace . '" vspace="' . $imageVspace . '" /></a>
              </div>';
        $i++;
      }
    }
    else 
    {
      echo '<div class="bold" style="padding-left:20px;">There are no photos tagged with ' . $options[0] . '</div>';
    }
    echo '</div>
          <br clear="all" />';
    
    
    echo '<div style="padding-top:0px; padding-left:10px; padding-bottom:8px;">Slideshows tagged with: ' . $options[0] . '<span style="font-weight:normal;">&nbsp;(<a href="/users/' . $username . '/slideshows/tags-' . $options[0] . '/">all slideshows tagged with ' . $options[0] . '</a>)</span></div>
          <div style="padding-left:20px;">';
    if(count($arrFlix) > 0)
    {
      foreach($arrFlix as $v)
      {
        $fotoURL = dynamicImage($v['US_PHOTO']['thumbnailPath_str'], $v['US_PHOTO']['photoKey_str'], 150, 100);
        //$fotoURL = $v['US_PHOTO']['thumbnailPath_str'];
                
        $tmpLength = floor($v['A_LENGTH'] / 60);
        $length  = $tmpLength . ':' . str_pad(($v['A_LENGTH'] % 60), 2, '0', STR_PAD_LEFT);
        
        echo '<div style="padding-bottom:20px; float:left;" align="center">
                <div class="flix_border_medium"><a href="/slideshow?' . $v['US_KEY'] . '"><img src="' . $fotoURL . '" width="150" height="100" border="0" alt="Click to view slideshow" /></a></div>
                <div style="float:left; width:180px;" class="f_7 bold center"><a href="/slideshow?' . $v['US_KEY'] . '">' . str_mid($v['US_NAME'], 49) . '</a></div>
              </div>';
      }
    }
    else 
    {
      echo '<div class="bold">There are no slideshows tagged with ' . $options[0] . '</div>';
    }
    echo '</div>';
  }
?>