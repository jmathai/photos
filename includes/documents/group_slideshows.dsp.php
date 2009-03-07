<?php
  $fl =& CFlix::getInstance();
  
  $sort = isset($_GET['ORDER']) ? $_GET['ORDER'] : false;
  $tags = isset($_GET['tags']) ? $_GET['tags'] : '';
  $group_id = $_GET['group_id'];
  
  $tagsArr = array();
  if(isset($_GET['tags']) && $_GET['tags'] != '')
  {
    $tagsArr = (array)explode(',', $_GET['tags']);
  }
  $flix_array = $fl->search(array('MODE' => 'GROUP', 'GROUP_ID' => $group_id, 'TAGS' => $tags, 'ORDER_BY' => $sort, 'PERMISSION' => PERM_SLIDESHOW_PUBLIC));
  
  $cnt_flix_array = $flix_array[0]['ROWS'];
  
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $per_page = 9;
  $per_row = 3;
  $total_pages = ceil($cnt_flix_array / $per_page);
  $offset = ($page * $per_page) - $per_page;
  $startPage = 1;
  $maxPages = 6;
  $p =& new CPaging($page, 10, $total_pages, 'page', '/', $_SERVER['QUERY_STRING']);
  
  echo '<div style="margin-left:55px; margin-top:5px;" class="f_dark bold">
          <div style="margin-bottom:5px;">search by group\'s tags</div>
          <div style="float:left;">
            <form action="" id="userTagSearchHeaderForm" method="get">
              <input type="hidden" name="action" value="group.slideshows" />
              <input type="hidden" name="group_id" value="' . $group_id . '" />
              <input type="hidden" name="page" value="' . $page . '" />
              <input type="text" id="userTagSearchHeader" name="tags" style="width:100px;" class="formfield" /><div autocomplete="off" id="fbTagSearch_auto_complete" class="auto_complete"></div>
            </form>
          </div>
          <div>
            <a href="javascript:void(0);" onclick="$(\'userTagSearchHeaderForm\').submit();"><img src="images/icons/search_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" hspace="3" title="Search by tags" /></a>
          </div>
        </div>';
  
  if($cnt_flix_array > 0 || isset($_GET['tags']))
  {
    if($cnt_flix_array > 0)
    {
      echo '<div style="overflow:hidden; padding-top:5px; padding-right:100px; padding-bottom:5px; padding-bottom:15px;">';
      if(count($tagsArr) > 0)
      {
        echo '<div class="center">';
        $str = 'Showing slideshows tagged with ';
        foreach($tagsArr as $v)
        {
          if($v != '')
          {
            $str .= '<span class="italic">' . $v . '</span>, ';
          }
        }
        echo substr($str, 0, -2);
        echo ' | <a href="/?action=group.slideshows&group_id=' . $group_id . '">Clear Filter</a></div>';
      }
    
      echo '<div align="right">
          <span style="padding-left:5px;">';
      if($total_pages > 1)
      {
        echo 'Pages: ';
        if($page > ($maxPages / 2))
        { 
          echo ' <a href="/?action=group.slideshows&group_id=' . $group_id . '&page=1">1</a> ... '; 
        }
        
        $lastPage = min($startPage + $maxPages, $total_pages);
        for($i = $startPage; $i <= $lastPage; $i++)
        {
          if($i != $page)
          {
            echo '<a href="/?action=group.slideshows&group_id=' . $group_id . '&page=' . $i . '">' . $i . '</a> ';
          }
          else
          {
            echo $i . ' ';
          }
        }
        
        if($i < $lastPage)
        { 
          echo ' ... <a href="/?action=group.slideshows&group_id=' . $group_id . '&page=' . $lastPage . '">' . $lastPage . '</a>';
        }
      }
      else
      {
        echo 'Showing ' . $cnt_flix_array . ' slideshows';
      }

      echo '</span>';
      echo '</div>';
      
      echo '</div>';
      echo '<div style="margin-left:15px;">';
    
      echo '<div style="width:700px; padding-top:8px; margin:auto;">';
      
      $top_limit = $per_page + $offset;
      for($i = $offset; $i < $top_limit; $i++)
      {
        // a flix exists
        if($i < $cnt_flix_array)
        {
          $fotoURL = dynamicImage($flix_array[$i]['US_PHOTO']['thumbnailPath_str'], $flix_array[$i]['US_PHOTO']['photoKey_str'], 150, 100);
          
          if($i % $per_row == 0)
          {
            echo '<div style="float:left; overflow:hidden; padding-top:10px; padding-left:10px; padding-right:10px; text-align:center;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          else 
          {
            echo '<div style="float:left; overflow:hidden; padding-top:10px; padding-left:35px; padding-right:10px; text-align:center;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          
          echo '  <div class="flix_border_medium"><a href="/slideshow?' . $flix_array[$i]['US_KEY'] . '"><img src="' . $fotoURL . '" width="150" height="100" border="0" /></a></div>';
          echo '  <div class="bold">' . str_mid($flix_array[$i]['US_NAME'], 25) . '</div>
                </div>';
            
          if($cnt_flix_array <= (($page * $per_page) - ($per_page / 2)) && $i == ($offset+(($per_page/2)-1)))
          {
            break;
          }
        }
        else 
        {
          if($i % ($per_page/2) == 0)
          {
            echo '<div style="float:left; overflow:hidden; padding-top:10px; padding-left:10px; padding-right:10px; text-align:center;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          else
          {
            echo '<div style="float:left; overflow:hidden; padding-top:10px; padding-left:10px; padding-right:10px; text-align:center;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          
          echo '</div>';
        }
        
        if($cnt_flix_array <= (($page * $per_page) - ($per_page / 2)) && $i == ($offset+(($per_page/2)-1)))
        {
          break;
        }
      }
    
      echo '</div>
            <br clear="all" />';
      echo '<div style="overflow:hidden; padding-top:5px; padding-right:5px; padding-bottom:5px;">';
    }
    else
    {
      echo '<div style="width:400px; margin:auto; padding-top:20px;">';
      echo '<div class="bold">Your search for slideshows tagged with <span class="italic">' . htmlentities($_GET['tags']) . '</span> had 0 results.</div>';
      echo '<div style="padding-left:20px; padding-top:5px;" class="bold">';
      echo '<div style="padding-top:4px;">';
      echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
      echo '<div><a href="?action=group.slideshows&group_id=' . $group_id . '">View all of this group\'s slideshows</a></div>';
      echo '</div>';
      echo '<div style="padding-top:4px;">';
      echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
      echo '<div><a href="?action=group.photos&group_id=' . $group_id . '">View all of this group\'s photos</a></div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  }
  else
  {
    echo '<div style="width:400px; margin:auto; padding-top:20px;">';
    echo '<div class="bold">There are no slideshows for this group</div>';
    echo '<div style="padding-left:20px; padding-top:5px;" class="bold">';
    echo '<div style="padding-top:4px;">';
    echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
    echo '<div><a href="?action=group.photos&group_id=' . $group_id . '">View all of this group\'s photos</a></div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
?>