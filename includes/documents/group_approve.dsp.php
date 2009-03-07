<?php
  $g = &CGroup::getInstance();
  $fb = &CFotobox::getInstance();
  $fl = &CFlix::getInstance();
  $u = &CUser::getInstance();
  
  $group_id = $_GET['group_id'];
  
  if($g->isModerator($_USER_ID, $group_id) == true)
  {
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'photo';
    if($tab != 'photo' && $tab !== 'slideshow')
    {
      $tab = 'photo';
    }
    
    $per_page = 20;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : $per_page;
    $per_row = 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startPage = 1;
    $maxPages = 6;
    $offset = ($page * $per_page) - $per_page;
  
    switch($tab)
    {
      case 'photo':
        $params = array('MODE' => 'PHOTO', 'GROUP_ID' => $group_id, 'LIMIT' => $limit, 'OFFSET' => $offset);
        $photoContent = $g->pendingApproval($params);
        $countPhotos = $GLOBALS['dbh']->found_rows();
  
        echo '<div style="float:left; border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Photos</div>';
        echo '<a href="/?action=group.approve&group_id=' . $group_id . '&tab=slideshow" title="View slideshows to approve" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Slideshows</div></a>';
        echo '<div style="float:left; border-bottom:1px solid #dddddd; width:598px; height:24px;"></div>';
        echo '<br clear="all" />';
        echo '<div style="border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd; margin-top:-1px; width:800px;">';
        
        echo '<div id="photosApproval">';
        echo '<div style="margin-left:21px; padding-top:10px;">';
        if(count($photoContent) > 0)
        {
          $i = 0;
          
          $total_pages = ceil($countPhotos / $per_page);
        
          echo '<div style="width:756px; border-bottom:1px solid #dddddd;"></div>';
          foreach($photoContent as $k => $v)
          {
            $photoData = $fb->fotoData($v['UP_ID']);
            $photoUrl = dynamicImage($photoData['P_THUMB_PATH'], $photoData['P_KEY'], 120, 90);
            if($i % $per_row == 0)
            {
              echo '<div style="float:left; width:150px; height:120px; border-left:1px solid #dddddd; border-bottom:1px solid #dddddd; border-right:1px solid #dddddd;"><img src="' . $photoUrl. '" border="0" width="120" height="90" style="margin-left:15px; margin-top:5px;" />';
              echo '<div style="float:left; padding-top:5px; padding-right:10px; padding-left:54px;"><a href="javascript:void(0);" onclick="approvePhoto(' . $group_id . ', ' . $v['UP_ID'] . ', ' . $v['UP_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Approve photo"><img src="images/icons/checkmark_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '<div style="padding-top:5px;"><a href="javascript:void(0);" onclick="rejectPhoto(' . $group_id . ', ' . $v['UP_ID'] . ', ' . $v['UP_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Reject photo"><img src="images/icons/delete_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '</div>';
            }
            else 
            {
              echo '<div style="float:left; width:150px; height:120px; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;"><img src="' . $photoUrl. '" border="0" width="120" height="90" style="margin-left:15px; margin-top:5px;" />';
              echo '<div style="float:left; padding-top:5px; padding-right:10px; padding-left:54px;"><a href="javascript:void(0);" onclick="approvePhoto(' . $group_id . ', ' . $v['UP_ID'] . ', ' . $v['UP_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Approve photo"><img src="images/icons/checkmark_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '<div style="padding-top:5px;"><a href="javascript:void(0);" onclick="rejectPhoto(' . $group_id . ', ' . $v['UP_ID'] . ', ' . $v['UP_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Reject photo"><img src="images/icons/delete_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '</div>';
            }
            
            $i++;
            
            if($i % $per_row == 0)
            {
              echo '<br clear="all" />';
            }
          }
          
          while($i % $per_row != 0)
          {
            echo '<div style="float:left; width:150px; height:120px; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;"></div>';
            $i++;
          }
  
          echo '<br clear="all" />';
          echo '<div style="padding-right:60px; padding-bottom:10px; padding-top:10px;" align="right">';
          echo '<span style="padding-left:5px;">';
          if($total_pages > 1)
          {
            echo 'Pages: ';
            if($page > ($maxPages / 2))
            { 
              echo ' <a href="/?action=group.approve&group_id=' . $group_id . '&tab=photo&page=1">1</a> ... '; 
            }
            
            $lastPage = min($startPage + $maxPages, $total_pages);
            for($i = $startPage; $i <= $lastPage; $i++)
            {
              if($i != $page)
              {
                echo '<a href="/?action=group.approve&group_id=' . $group_id . '&tab=photo&page=' . $i . '">' . $i . '</a> ';
              }
              else
              {
                echo $i . ' ';
              }
            }
            
            if($i < $lastPage)
            { 
              echo ' ... <a href="/?action=group.approve&group_id=' . $group_id . '&tab=photo&page=' . $lastPage . '">' . $lastPage . '</a>';
            }
          }
          else
          {
            //echo '<div>Showing ' . count($photoContent) . ' photos</div>';
          }
          echo '</span>';
          echo '</div>';
        }
        else 
        {
          echo 'No photos waiting for approval';
        }
        echo '</div>';
        echo '</div>';
      
        echo '</div>';
        break;
        
      case 'slideshow':
        $params = array('MODE' => 'SLIDESHOW', 'GROUP_ID' => $group_id, 'LIMIT' => $limit, 'OFFSET' => $offset);
        $slideshowContent = $g->pendingApproval($params);
        $countSlideshows= $GLOBALS['dbh']->found_rows();
        
        echo '<a href="/?action=group.approve&group_id=' . $group_id . '&tab=photo" title="View photos to approve" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Photos</div></a>';
        echo '<div style="float:left; border-right:1px solid #dddddd; border-top:1px solid #dddddd; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Slideshows</div>';
        echo '<div style="float:left; border-bottom:1px solid #dddddd; width:598px; height:24px;"></div>';
        echo '<br clear="all" />';
        echo '<div style="border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd; margin-top:-1px; width:800px;">';
        
        echo '<div id="slideshowsApproval"">';
        echo '<div style="margin-left:21px; padding-top:10px;">';
        if(count($slideshowContent) > 0)
        {
          $i = 0;
          
          $total_pages = ceil($countSlideshows / $per_page);
          
          echo '<div style="width:756px; border-bottom:1px solid #dddddd;"></div>';
          foreach($slideshowContent as $k => $v)
          {
            $slideshowData = $fl->search(array('FLIX_ID' => $v['UF_ID'], 'GROUP_ID' => $group_id));
            $photoURL = dynamicImage($slideshowData['US_PHOTO']['thumbnailPath_str'], $slideshowData['US_PHOTO']['photoKey_str'], 120, 90);
            if($i % $per_row == 0)
            {          
              echo '<div style="float:left; width:150px; height:120px; border-left:1px solid #dddddd; border-bottom:1px solid #dddddd; border-right:1px solid #dddddd;" class=""><img src="' . $photoURL . '" border="0" width="120" height="90" style="margin-left:15px; margin-top:5px;" />';
              echo '<div style="float:left; padding-top:5px; padding-right:10px; padding-left:54px;"><a href="javascript:void(0);" onclick="approveSlideshow(' . $group_id . ', ' . $v['UF_ID'] . ', ' . $v['UF_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Approve slideshow"><img src="images/icons/checkmark_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '<div style="padding-top:5px;"><a href="javascript:void(0);" onclick="rejectSlideshow(' . $group_id . ', ' . $v['UF_ID'] . ', ' . $v['UF_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Reject slideshow"><img src="images/icons/delete_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '</div>';
            }
            else 
            {
              echo '<div style="float:left; width:150px; height:120px; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;"><img src="' . $photoURL . '" border="0" width="120" height="90" style="margin-left:15px; margin-top:5px;" />';
              echo '<div style="float:left; padding-top:5px; padding-right:10px; padding-left:54px;"><a href="javascript:void(0);" onclick="approveSlideshow(' . $group_id . ', ' . $v['UF_ID'] . ', ' . $v['UF_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Approve slideshow"><img src="images/icons/checkmark_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '<div style="padding-top:5px;"><a href="javascript:void(0);" onclick="rejectSlideshow(' . $group_id . ', ' . $v['UF_ID'] . ', ' . $v['UF_ORIG_ID'] . ', ' . $v['U_ORIG_ID'] . ');" title="Reject slideshow"><img src="images/icons/delete_16x16.png" class="png" border="0" width="16" height="16" /></a></div>';
              echo '</div>';
            }
            
            $i++;
            
            if($i % $per_row == 0)
            {
              echo '<br clear="all" />';
            }
          }
          
          while($i % $per_row != 0)
          {
            echo '<div style="float:left; width:150px; height:120px; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd;"></div>';
            $i++;
          }
          
          echo '<br clear="all" />';
          echo '<div style="padding-right:60px; padding-top:10px; padding-bottom:10px;" align="right">';
          echo '<span style="padding-left:5px;">';
          if($total_pages > 1)
          {
            echo 'Pages: ';
            if($page > ($maxPages / 2))
            { 
              echo ' <a href="/?action=group.approve&group_id=' . $group_id . '&tab=slideshow&page=1">1</a> ... '; 
            }
            
            $lastPage = min($startPage + $maxPages, $total_pages);
            for($i = $startPage; $i <= $lastPage; $i++)
            {
              if($i != $page)
              {
                echo '<a href="/?action=group.approve&group_id=' . $group_id . '&tab=slideshow&page=' . $i . '">' . $i . '</a> ';
              }
              else
              {
                echo $i . ' ';
              }
            }
            
            if($i < $lastPage)
            { 
              echo ' ... <a href="/?action=group.approve&group_id=' . $group_id . '&tab=slideshow&page=' . $lastPage . '">' . $lastPage . '</a>';
            }
          }
          else
          {
            //echo '<div>Showing ' . count($slideshowContent) . ' slideshows</div>';
          }
          echo '</span>';
          echo '</div>';
        }
        else 
        {
          echo 'No slideshows waiting for approval';
        }
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
        break;
    }
  }
  else 
  {
    echo 'You are no a group administrator';
  }
?>