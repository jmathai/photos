<?php
  $fb =& CFotobox::getInstance();
  
  $groupId = $_GET['group_id'];
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  
  $maxPages = 10;
  $perPage = 16;
  $startPage = min($page, $page - floor($maxPages / 2)); // which page should paging start on
  if($startPage < 1){ $startPage = 1; } // add some logic so we don't have to do this
  
  $tagsArr = array();
  if(isset($_GET['tags']) && $_GET['tags'] != '')
  {
    $tagsArr = (array)explode(',', $_GET['tags']);
  }
  
  $params = array('MODE' => 'GROUP', 'GROUP_ID' => $group_id, 'TAGS' => $tagsArr, 'PERMISSION' => PERM_PHOTO_PUBLIC, 'ORDER' => 'P_CREATED', 'OFFSET' => ($perPage*$page - $perPage), 'LIMIT' => $perPage);
  $arrFotos = $fb->fotosSearch($params);
  
  $fotosTotal = $GLOBALS['dbh']->found_rows();
  $totalPages = ceil($fotosTotal / $perPage);
  
  echo '<div style="margin-left:55px; margin-top:5px;" class="f_dark bold">
          <div style="margin-bottom:5px;">search by group\'s tags</div>
          <div style="float:left;">
            <form action="" id="userTagSearchHeaderForm" method="get">
              <input type="hidden" name="action" value="group.photos" />
              <input type="hidden" name="group_id" value="' . $group_id . '" />
              <input type="hidden" name="page" value="' . $page . '" />
              <input type="text" id="userTagSearchHeader" name="tags" style="width:100px;" class="formfield" /><div autocomplete="off" id="fbTagSearch_auto_complete" class="auto_complete"></div>
            </form>
          </div>
          <div>
            <a href="javascript:void(0);" onclick="$(\'userTagSearchHeaderForm\').submit();"><img src="images/icons/search_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" hspace="3" title="Search by tags" /></a>
          </div>
        </div>';
  
  if($fotosTotal > 0)
  {
    echo '<div style="padding:5px 100px 5px 15px;">';
    if(count($tagsArr) > 0)
    {
      echo '<div class="center">';
      $str = 'Showing photos tagged with ';
      foreach($tagsArr as $v)
      {
        if($v != '')
        {
          $str .= '<span class="italic">' . $v . '</span>, ';
        }
      }
      echo substr($str, 0, -2);
      echo ' | <a href="/?action=group.photos&group_id=' . $group_id . '">Clear Filter</a></div>';
    }
    
    echo '<div align="right">
          <span style="padding-left:5px;">';
    if($totalPages > 1)
    {
      echo 'Pages: ';
      if($page > ($maxPages / 2))
      { 
        echo ' <a href="/?action=group.photos&group_id=' . $group_id . '&page=1">1</a> ... '; 
      }
      
      $lastPage = min($startPage + $maxPages, $totalPages);
      for($i = $startPage; $i <= $lastPage; $i++)
      {
        if($i != $page)
        {
          echo '<a href="/?action=group.photos&group_id=' . $group_id . '&page=' . $i . '">' . $i . '</a> ';
        }
        else
        {
          echo $i . ' ';
        }
      }
      
      if($i < $lastPage)
      { 
        echo ' ... <a href="/?action=group.photos&group_id=' . $group_id . '&page=' . $lastPage . '">' . $lastPage . '</a>';
      }
    }
    else
    {
      echo 'Showing ' . $fotosTotal . ' photos';
    }
    echo '    <span>
            </div>
          </div>';
    
    $i = 1;
    $ids = '';
    echo '<div style="width:750px; margin:auto;">';
    $offsetUrl = ($page * $perPage) - $perPage;
    foreach($arrFotos as $v)
    {
      $ids .= ',' . $v['P_ID'];
      $url = '?action=group.photo&group_id=' . $group_id . '&page=' . $page . '&id=' . $v['P_ID'];
      $url .= '&offset=' . $offsetUrl;  
      
      if(isset($_GET['tags']) && $_GET['tags'] !== '')
      {
        $url .= '&tags=' . $_GET['tags'];  
      }    
      
      if(($v['P_NAME'] . $v['P_DESC']) != '')
      {
        $title = htmlentities($v['P_NAME'] . ' ' . $v['P_DESC']);
      }
      else
      {
        $title = 'Click to view photo';
      }
        
      $fotoUrl = dynamicImage($v['P_THUMB_PATH'], $v['P_KEY'], 150, 100);
      echo '<div style="float:left; padding:12px;">
                <a href="' . $url . '" title="' . $title . '"><img src="' . $fotoUrl . '" width="150" height="100" border="0" class="border_dark" /></a>
            </div>';
      $offsetUrl++;
      $i++;
    }
    echo '</div>';
  }
  else
  {
    echo '<div style="width:400px; margin:auto; padding-top:20px;">';
    echo '<div class="bold">Your search for photos tagged with <span class="italic">' . htmlentities($_GET['tags']) . '</span> had 0 results.</div>';
    echo '<div style="padding-left:20px; padding-top:5px;" class="bold">';
    echo '<div style="padding-top:4px;">';
    echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
    echo '<div><a href="?action=group.photos&group_id=' . $group_id . '">View all of this group\'s photos</a></div>';
    echo '</div>';
    echo '<div style="padding-top:4px;">';
    echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
    echo '<div><a href="?action=group.slideshows&group_id=' . $group_id . '">View all of this group\'s slideshows</a></div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
?>

<script type="text/javascript">
  new Autocompleter.Local("userTagSearchHeader", "fbTagSearch_auto_complete", userTags, {tokens: ','});
</script>