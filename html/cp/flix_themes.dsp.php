<?php
  // slideshows after December 1, 2006
  $sql = 'SELECT * '
       . 'FROM user_slideshows AS us '
       . 'WHERE us_status = \'active\' '
       . 'AND UNIX_TIMESTAMP(us_dateCreated) >= ' . strtotime('12/1/2006') . ' ';
       
  $slideshows = $GLOBALS['dbh']->query_all($sql);
  
  // get all the settings arrays for all the slideshows
  $settingsArr = array();
  foreach($slideshows as $k => $v)
  {
    $settingsArr[] = jsonDecode($v['us_settings']);
  }
  
  // go through each slideshows settings to get the themes
  $themesArr = array();
  foreach($settingsArr as $thisSettings)
  { 
    $currentTheme = 'Default Left-Right';
    $preview = false;
    $altTheme = '';
    
    foreach($thisSettings as $v)
    {
      if($v['instanceName_str'] == 'background_mc')
      {
        if(isset($v['swfPath_str']))
        {
          $currentTheme = $v['swfPath_str'];
        }
        
      }
      
      if($v['instanceName_str'] == 'backgroundGraphic_mc')
      {
        if(isset($v['swfPath_str']))
        {
          $altTheme = basename($v['swfPath_str']);
        }
      }
      
      if($v['instanceName_str'] == 'detail_mc')
      {
        if(isset($v['swfPath_str']))
        {
          $altTheme = basename($v['swfPath_str']);
        }
      }
      
      if($v['instanceName_str'] == 'preview_mc')
      {
        $preview = true;
      }
    }
    
    if($currentTheme == 'Default Left-Right')
    {
      if($altTheme == '')
      {
        if($preview == false)
        {
          $currentTheme = 'No Theme or Default Center';
        }
      }
      else 
      {
        $currentTheme = $altTheme;
      }
    }
    
    $height = $thisSettings[0]['picHeight_int'];
    $width = $thisSettings[0]['picWidth_int'];
    
    /*
      if the theme already exists then
        if the height and width already exists add to its frequency
        else add a new array element and set the frequency to 1
      else
        add a new array element for both the theme and the height and width
        and set it to 1
    */
    if(array_key_exists($currentTheme, $themesArr))
    {
      if(array_key_exists($width . 'x' . $height, $themesArr[$currentTheme]))
      {
        $themesArr[$currentTheme][$width . 'x' . $height]++;
        $themesArr[$currentTheme]['TOTAL']++;
      }
      else 
      {
        $themesArr[$currentTheme][$width . 'x' . $height] = 1;
        $themesArr[$currentTheme]['TOTAL']++;
      }
    }
    else 
    {
      $themesArr[$currentTheme] = array($width . 'x' . $height => 1, 'TOTAL' => 1);
    }
  }
  
  /*
  $themesArr = array();
  $themesArr['Default Left-Right']['TOTAL'] = 10;
  $themesArr['Default Left-Right']['600x865'] = 5;
  $themesArr['Default Left-Right']['570x865'] = 5;
  
  $themesArr['Christmas']['TOTAL'] = 13;
  $themesArr['Christmas']['600x865'] = 7;
  $themesArr['Christmas']['570x865'] = 6;
  
  $themesArr['Valentine']['TOTAL'] = 208;
  $themesArr['Valentine']['600x865'] = 8;
  $themesArr['Valentine']['570x865'] = 100;
  $themesArr['Valentine']['400x300'] = 50;
  $themesArr['Valentine']['500x200'] = 50;
  
  $themesArr['Easter']['TOTAL'] = 27;
  $themesArr['Easter']['600x865'] = 7;
  $themesArr['Easter']['570x865'] = 20;
  
  $themesArr['Wedding']['TOTAL'] = 53;
  $themesArr['Wedding']['600x865'] = 6;
  $themesArr['Wedding']['570x865'] = 47;
  */
  
  // order the sizes
  foreach($themesArr as $k => $v)
  {
    arsort($themesArr[$k]);
  }
  
  echo '<div>
  				<div style="float:left; padding-top:25px;" class="f_12 bold">Top Themes After December 1, 2006</div>
  				<div style="float:left; padding-top:25px; padding-left:100px;" class="f_12 bold">Top Themes</div>
  				<br clear="all" />
  			</div>
				<div style="float:left;">
	        <div>
	          <div style="padding-top:25px;">
	            <div style="float:left; width:200px;" class="f_10 bold">Theme</div>
	            <div style="float:left; width:50px;" class="f_10 bold center">Usage</div>
	            <br clear="all" />
	          </div>';
  
  // print out the results
  $cnt = 0;
  $topTen = array();
  while($themesArr != null)
  {
    $max = 0;
    $maxKey = '';
    foreach($themesArr as $thisThemeKey => $thisThemeValue)
    {
      if($themesArr[$thisThemeKey]['TOTAL'] > $max)
      {
        $max = $themesArr[$thisThemeKey]['TOTAL'];
        $maxKey = $thisThemeKey;
      }
    }
    
    if($cnt < 10)
    {
      $topTen[] = $maxKey;
    }
    $cnt++;
    
    echo '<div style="border-bottom:1px dotted black; width:250px; padding-bottom:5px;">';
	    echo '<div style="padding-top:15px;">
	            <div style="float:left; width:200px;" class="f_8 bold">' . basename($maxKey) . '</div>
	            <div style="float:left; width:50px;" class="f_8 center">' . $max . '</div>
	            <br clear="all" />
	          </div>';
    
    $i = 0;
    foreach($themesArr[$maxKey] as $k => $v)
    {
      if($k != 'TOTAL')
      {
        $color = '#FFFFFF';
        if($i % 2 == 0)
        {
          $color = '#EEEEEE';
          $i = 0;
        }
        $i++;
        
        echo '<div style="background-color:' . $color . '; width:250px; padding-top:5px;">
                <div style="float:left; width:175px; padding-left:25px;" class="f_8">' . $k . '</div>
                <div style="float:left; width:50px;" class="f_8 center">' . $v . '</div>
                <br clear="all" />
              </div>';
      }
    }
    echo '</div>';
    
    unset($themesArr[$maxKey]);
  }
  
  echo '</div>';
  
  // print out the top ten themes (ignoring size)
  echo '<div>
          <div style="padding-top:25px; padding-bottom:25px;" class="f_10 bold">Top Ten</div>';
  
  $place = 1;
  foreach($topTen as $v)
  {
    echo '<div style="padding-top:5px;">
            <div style="float:left; width:20px; text-align:right;">' . $place . '. </div>
            <div style="float:left; padding-left:5px;">' . basename($v) . '</div>
            <br clear="all" />
          </div>';
    $place++;
  }
  
  echo '	</div>
  				<br clear="all" />
  			</div>';
  
  
  
  // slideshows
  $sql = 'SELECT * '
       . 'FROM user_slideshows AS us '
       . 'WHERE us_status = \'active\' ';
       
  $slideshows = $GLOBALS['dbh']->query_all($sql);
  
  // get all the settings arrays for all the slideshows
  $settingsArr = array();
  foreach($slideshows as $k => $v)
  {
    $settingsArr[] = jsonDecode($v['us_settings']);
  }
  
  // go through each slideshows settings to get the themes
  $themesArr = array();
  foreach($settingsArr as $thisSettings)
  { 
    $currentTheme = 'Default Left-Right';
    $preview = false;
    $altTheme = '';
    
    foreach($thisSettings as $v)
    {
      if($v['instanceName_str'] == 'background_mc')
      {
        if(isset($v['swfPath_str']))
        {
          $currentTheme = $v['swfPath_str'];
        }
        
      }
      
      if($v['instanceName_str'] == 'backgroundGraphic_mc')
      {
        if(isset($v['swfPath_str']))
        {
          $altTheme = basename($v['swfPath_str']);
        }
      }
      
      if($v['instanceName_str'] == 'detail_mc')
      {
        if(isset($v['swfPath_str']))
        {
          $altTheme = basename($v['swfPath_str']);
        }
      }
      
      if($v['instanceName_str'] == 'preview_mc')
      {
        $preview = true;
      }
    }
    
    if($currentTheme == 'Default Left-Right')
    {
      if($altTheme == '')
      {
        if($preview == false)
        {
          $currentTheme = 'No Theme or Default Center';
        }
      }
      else 
      {
        $currentTheme = $altTheme;
      }
    }
    
    $height = $thisSettings[0]['picHeight_int'];
    $width = $thisSettings[0]['picWidth_int'];
    
    /*
      if the theme already exists then
        if the height and width already exists add to its frequency
        else add a new array element and set the frequency to 1
      else
        add a new array element for both the theme and the height and width
        and set it to 1
    */
    if(array_key_exists($currentTheme, $themesArr))
    {
      if(array_key_exists($width . 'x' . $height, $themesArr[$currentTheme]))
      {
        $themesArr[$currentTheme][$width . 'x' . $height]++;
        $themesArr[$currentTheme]['TOTAL']++;
      }
      else 
      {
        $themesArr[$currentTheme][$width . 'x' . $height] = 1;
        $themesArr[$currentTheme]['TOTAL']++;
      }
    }
    else 
    {
      $themesArr[$currentTheme] = array($width . 'x' . $height => 1, 'TOTAL' => 1);
    }
  }
  
  /*
  $themesArr = array();
  $themesArr['Default Left-Right']['TOTAL'] = 10;
  $themesArr['Default Left-Right']['600x865'] = 5;
  $themesArr['Default Left-Right']['570x865'] = 5;
  
  $themesArr['Christmas']['TOTAL'] = 13;
  $themesArr['Christmas']['600x865'] = 7;
  $themesArr['Christmas']['570x865'] = 6;
  
  $themesArr['Valentine']['TOTAL'] = 208;
  $themesArr['Valentine']['600x865'] = 8;
  $themesArr['Valentine']['570x865'] = 100;
  $themesArr['Valentine']['400x300'] = 50;
  $themesArr['Valentine']['500x200'] = 50;
  
  $themesArr['Easter']['TOTAL'] = 27;
  $themesArr['Easter']['600x865'] = 7;
  $themesArr['Easter']['570x865'] = 20;
  
  $themesArr['Wedding']['TOTAL'] = 53;
  $themesArr['Wedding']['600x865'] = 6;
  $themesArr['Wedding']['570x865'] = 47;
  */
  
  // order the sizes
  foreach($themesArr as $k => $v)
  {
    arsort($themesArr[$k]);
  }
  
  echo '<div style="float:left; padding-left:180px;">
	        <div>
	          <div style="padding-top:25px;">
	            <div style="float:left; width:200px;" class="f_10 bold">Theme</div>
	            <div style="float:left; width:50px;" class="f_10 bold center">Usage</div>
	            <br clear="all" />
	          </div>';
  
  // print out the results
  $cnt = 0;
  $topTen = array();
  while($themesArr != null)
  {
    $max = 0;
    $maxKey = '';
    foreach($themesArr as $thisThemeKey => $thisThemeValue)
    {
      if($themesArr[$thisThemeKey]['TOTAL'] > $max)
      {
        $max = $themesArr[$thisThemeKey]['TOTAL'];
        $maxKey = $thisThemeKey;
      }
    }
    
    if($cnt < 10)
    {
      $topTen[] = $maxKey;
    }
    $cnt++;
    
    echo '<div style="border-bottom:1px dotted black; width:250px; padding-bottom:5px;">';
    echo '<div style="padding-top:15px;">
            <div style="float:left; width:200px;" class="f_8 bold">' . basename($maxKey) . '</div>
            <div style="float:left; width:50px;" class="f_8 center">' . $max . '</div>
            <br clear="all" />
          </div>';
    
    $i = 0;
    foreach($themesArr[$maxKey] as $k => $v)
    {
      if($k != 'TOTAL')
      {
        $color = '#FFFFFF';
        if($i % 2 == 0)
        {
          $color = '#EEEEEE';
          $i = 0;
        }
        $i++;
        
        echo '<div style="background-color:' . $color . '; width:250px; padding-top:5px;">
                <div style="float:left; width:175px; padding-left:25px;" class="f_8">' . $k . '</div>
                <div style="float:left; width:50px;" class="f_8 center">' . $v . '</div>
                <br clear="all" />
              </div>';
      }
    }
    echo '</div>';
    
    unset($themesArr[$maxKey]);
  }
  
  echo '</div>';
  
  // print out the top ten themes (ignoring size)
  echo '<div>
          <div style="padding-top:25px; padding-bottom:25px;" class="f_10 bold">Top Ten</div>';
  
  $place = 1;
  foreach($topTen as $v)
  {
    echo '<div style="padding-top:5px;">
            <div style="float:left; width:20px; text-align:right;">' . $place . '. </div>
            <div style="float:left; padding-left:5px;">' . basename($v) . '</div>
            <br clear="all" />
          </div>';
    $place++;
  }
  
  echo '  </div>
        </div>';
  echo '<br clear="all" />';
?>