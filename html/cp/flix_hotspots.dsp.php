<?php
  // slideshows after December 1, 2006
  $sql = 'SELECT * '
       . 'FROM user_slideshows AS us '
       . 'WHERE us_status = \'active\' '
       . 'AND UNIX_TIMESTAMP(us_dateCreated) >= ' . strtotime('12/1/2006') . ' ';
       
  $slideshows = $GLOBALS['dbh']->query_all($sql);
  
  // get all the elements arrays for all the slideshows
  $elementsArr = array();
  foreach($slideshows as $k => $v)
  {
    $elementsArr[] = jsonDecode($v['us_elements']);
  }
  
  // go through each slideshows elements to get the hotspots
  $hotspotsArr = array();
  if(is_array($elementsArr))
  {
	  foreach($elementsArr as $thisElement)
	  { 
	    $currentHotspot = false;
	    
	    if(is_array($thisElement))
	    {
		    foreach($thisElement as $v)
		    {
		    	if(is_array($v))
		    	{
		    		// if a hotspot exists
			      if(array_key_exists('hotSpot_arr', $v))
			      {
			    		foreach($v['hotSpot_arr'] as $k2 => $v2)
			    		{
			    			$currentHotspot = isset($v2['swfPath_str']) ? $v2['swfPath_str'] : false;

				    		/*
						      if the element already exists then
						        add to its frequency
						      else
						        add a new array element and set the frequency to 1
						    */
						    if($currentHotspot !== false)
						    {
							    if(array_key_exists($currentHotspot, $hotspotsArr))
							    {
							      $hotspotsArr[$currentHotspot]++;
							    }
							    else 
							    {
							      $hotspotsArr[$currentHotspot] = 1;
							    }
						    }
			    		}
			      }
		    	}
		    }
	    }
	  }
  }
  
  /*
  $hotspotsArr = array();
  $hotspotsArr['eye.swf'] = 10;
  $hotspotsArr['teeth_1.swf'] = 15;
  $hotspotsArr['hair.swf'] = 5;
  $hotspotsArr['sparkle.swf'] = 20;
  $hotspotsArr['teeth_2.swf'] = 7;
  $hotspotsArr['blood_eye.swf'] = 6;
  */
  
  
  // order them
  arsort($hotspotsArr);
  
  echo '<div>
  				<div style="float:left; padding-top:25px;" class="f_12 bold">Top Hotspots After December 1, 2006</div>
  				<div style="float:left; padding-top:25px; padding-left:100px;" class="f_12 bold">Top Hotspots</div>
  				<br clear="all" />
  			</div>
				<div style="float:left;">
	        <div>
	          <div style="padding-top:25px;">
	            <div style="float:left; width:200px;" class="f_10 bold">Hotspot</div>
	            <div style="float:left; width:50px;" class="f_10 bold center">Usage</div>
	            <br clear="all" />
	          </div>';
  
  // print out the results
  $i = 0;
  foreach($hotspotsArr as $k => $v)
  {
  	$color = '#FFFFFF';
    if($i % 2 == 0)
    {
      $color = '#EEEEEE';
      $i = 0;
    }
    $i++;
    
    echo '<div style="background-color:' . $color . '; border-bottom:1px dotted black; width:250px; padding-bottom:5px;">';
	  echo '<div style="padding-top:15px;">
            <div style="float:left; width:200px;" class="f_8 bold">' . $k . '</div>
            <div style="float:left; width:50px;" class="f_8 center">' . $v . '</div>
            <br clear="all" />
          </div>';
    echo '</div>';
  }
  
  echo '</div>';
  
  echo '	</div>';
  
  
  
  
  
  
  // slideshows
  $sql = 'SELECT * '
       . 'FROM user_slideshows AS us '
       . 'WHERE us_status = \'active\' ';
       
  $slideshows = $GLOBALS['dbh']->query_all($sql);
  
  // get all the elements arrays for all the slideshows
  $elementsArr = array();
  foreach($slideshows as $k => $v)
  {
    $elementsArr[] = jsonDecode($v['us_elements']);
  }
  
  // go through each slideshows elements to get the hotspots
  $hotspotsArr = array();
  if(is_array($elementsArr))
  {
	  foreach($elementsArr as $thisElement)
	  { 
	    $currentHotspot = false;
	    
	    if(is_array($thisElement))
	    {
		    foreach($thisElement as $v)
		    {
		    	if(is_array($v))
		    	{
		    		// if a hotspot exists
			      if(array_key_exists('hotSpot_arr', $v))
			      {
			    		foreach($v['hotSpot_arr'] as $k2 => $v2)
			    		{
			    			$currentHotspot = isset($v2['swfPath_str']) ? $v2['swfPath_str'] : false;
			    			
				    		/*
						      if the element already exists then
						        add to its frequency
						      else
						        add a new array element and set the frequency to 1
						    */
						    if($currentHotspot !== false)
						    {
							    if(array_key_exists($currentHotspot, $hotspotsArr))
							    {
							      $hotspotsArr[$currentHotspot]++;
							    }
							    else 
							    {
							      $hotspotsArr[$currentHotspot] = 1;
							    }
						    }
			    		}
			      }
		    	}
		    }
	    }
	  }
  }
  
  
  /*
  $hotspotsArr = array();
  $hotspotsArr['eye.swf'] = 30;
  $hotspotsArr['teeth_1.swf'] = 45;
  $hotspotsArr['hair.swf'] = 55;
  $hotspotsArr['sparkle.swf'] = 60;
  $hotspotsArr['teeth_2.swf'] = 47;
  $hotspotsArr['blood_eye.swf'] = 86;
  */
  
  
  // order them
  arsort($hotspotsArr);
  
  echo '<div style="float:left; padding-left:180px;">
	        <div>
	          <div style="padding-top:25px;">
	            <div style="float:left; width:200px;" class="f_10 bold">Hotspot</div>
	            <div style="float:left; width:50px;" class="f_10 bold center">Usage</div>
	            <br clear="all" />
	          </div>';
  
  // print out the results
  $i = 0;
  foreach($hotspotsArr as $k => $v)
  {
  	$color = '#FFFFFF';
    if($i % 2 == 0)
    {
      $color = '#EEEEEE';
      $i = 0;
    }
    $i++;
    
    echo '<div style="background-color:' . $color . '; border-bottom:1px dotted black; width:250px; padding-bottom:5px;">';
	  echo '<div style="padding-top:15px;">
            <div style="float:left; width:200px;" class="f_8 bold">' . $k . '</div>
            <div style="float:left; width:50px;" class="f_8 center">' . $v . '</div>
            <br clear="all" />
          </div>';
    echo '</div>';
  }
  
  echo '</div>';
  
  echo '	</div>
  				<br clear="all" />';
?>