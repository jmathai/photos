<?php
  $fb =& CFotobox::getInstance();
  
  $maxPages = 10;
  $perPage = 16;
  $page = isset($page) ? $page : 1;
  $startPage = min($page, $page - floor($maxPages / 2)); // which page should paging start on
  if($startPage < 1){ $startPage = 1; } // add some logic so we don't have to do this
  
  $tagsArr = array();
  if(isset($tags))
  {
    $tagsArr = (array)explode(',', $tags);
  }
  
  //$arrFotos  = $fb->fotosByTags($tagsArr, $user_id, 3, 'P_MOD_YMD', ($perPage*$page - $perPage), $perPage, 3);
  //$arrFotos  = $fb->fotosByTags($tagsArr, $user_id, 3, 'P_MOD_YMD', ($perPage*$page - $perPage), $perPage, 3);
  $params = array('USER_ID' => $user_id, 'TAGS' => $tagsArr, 'PERMISSION' => PERM_PHOTO_PUBLIC, 'ORDER' => 'P_TAKEN_BY_DAY', 'OFFSET' => ($perPage*$page - $perPage), 'LIMIT' => $perPage);
  $arrFotos = $fb->fotosSearch($params);
  
  $fotosTotal = $GLOBALS['dbh']->found_rows();
  $totalPages = ceil($fotosTotal / $perPage);
  
  if($fotosTotal > 0)
  {
    echo '<div style="padding:5px 15px 5px 15px;">';
    if(count($tagsArr) > 0)
    {
      echo '<div style="float:left; padding-left:10px;">';
      $str = 'Photos tagged with: ';
      foreach($tagsArr as $v)
      {
        if($v != '')
        {
          $str .= '<a href="/users/' . $username . '/photos/tags-' . preg_replace('/\W/', '', $v) . '/">' . $v . '</a>, ';
        }
      }
      echo substr($str, 0, -2);
      echo '</div>';
    }
    echo '<div style="width:710px;">
          <div style="float:right;">';
    if($totalPages > 1)
    {
      echo '<div style="float:left; padding-right:10px;">Pages </div>';
      $url = str_replace("/page{$page}", '', $_SERVER['REQUEST_URI']);
      if($page != 1) { echo '<div style="float:left; padding-right:5px;"><a href="' . str_replace('/photos', "/photos/page1", $url) . '"><img src="images/paging_first.gif" border="0" /></a></div>'; }
      if($page > ($maxPages / 2)){ echo '<div style="float:left;"><a href="' . str_replace('/photos', "/photos/page1", $url) . '">1</a> ... </div>'; }
      
      $lastPage = min($startPage + $maxPages, $totalPages);
      for($i = $startPage; $i <= $lastPage; $i++)
      {
        if($i != $page)
        {
          echo '<div style="float:left; padding-right:3px;"><a href="' . str_replace('/photos', "/photos/page{$i}", $url) . '">' . $i . '</a></div> ';
        }
        else
        {
          echo '<div style="float:left; padding-right:3px;">' . $i . '</div>';
        }
      }
      
      if($i < $lastPage){ echo '<div style="float:left;"> ... <a href="' . str_replace('/photos', "/photos/page{$totalPages}", $url) . '">' . $lastPage . '</a></div>'; }
      if($page != $lastPage) { echo '<div style="float:left; padding-left:5px;"><a href="' . str_replace('/photos', "/photos/page{$totalPages}", $url) . '"><img src="images/paging_last.gif" border="0" /></a></div>'; }
    }
    else
    {
      echo 'Showing ' . $fotosTotal . ' photos';
    }
    echo '    </div>
            </div>
          </div>';
    echo '<br />';
    
    $i = 1;
    $ids = '';
    echo '<div style="width:750px; margin:auto;">';
    $offsetUrl = ($page * $perPage) - $perPage;
    foreach($arrFotos as $v)
    {
      $ids .= ',' . $v['P_ID'];
      $url = '/users/' . $username . '/photo/' . $v['P_ID'] . '/';
      if($tags != '')
      {
        $url .= 'tags-' . $tags . '/';
      }
      if($quickset != '')
      {
        $url .= 'quickset-' . $quicksetId . '-' . $quicksetName . '/';
      }
      $url .= '?offset=' . $offsetUrl;        
      
      if(($v['P_NAME'] . $v['P_DESC']) != '')
      {
        $title = htmlentities($v['P_NAME'] . ' ' . $v['P_DESC']);
      }
      else
      {
        $title = 'Click to view photo';
      }
        
      //$fotoUrl = dynamicImage($v['P_THUMB_PATH'], $v['P_KEY'], 150, 100);
      $fotoInfo = dynamicImageLock($v['P_THUMB_PATH'], $v['P_KEY'], $v['P_ROTATION'], $v['P_WIDTH'], $v['P_HEIGHT'], 150, 150);
      $imageHspace = intval((150 - $fotoInfo[1]) / 2);
      $imageVspace = intval((150 - $fotoInfo[2]) / 2);
      echo '<div style="float:left; padding:12px; width:150px; height:150px;">
                <a href="' . $url . '" title="' . $title . '"><img src="' . $fotoInfo[0] . '" ' . $fotoInfo[3] . ' border="0" class="border_dark" hspace="' . $imageHspace . '" vspace="' . $imageVspace . '" /></a>
            </div>';
      $offsetUrl++;
      $i++;
    }
    echo '</div>';
    
    echo '<br clear="all" />';
    echo '<div style="width:727px;">
          <div style="float:right;">';
    if($totalPages > 1)
    {
      echo '<div style="float:left; padding-right:10px;">Pages </div>';
      $url = str_replace("/page{$page}", '', $_SERVER['REQUEST_URI']);
      if($page != 1) { echo '<div style="float:left; padding-right:5px;"><a href="' . str_replace('/photos', "/photos/page1", $url) . '"><img src="images/paging_first.gif" border="0" /></a></div>'; }
      if($page > ($maxPages / 2)){ echo '<div style="float:left;"><a href="' . str_replace('/photos', "/photos/page1", $url) . '">1</a> ... </div>'; }
      
      $lastPage = min($startPage + $maxPages, $totalPages);
      for($i = $startPage; $i <= $lastPage; $i++)
      {
        if($i != $page)
        {
          echo '<div style="float:left; padding-right:3px;"><a href="' . str_replace('/photos', "/photos/page{$i}", $url) . '">' . $i . '</a></div> ';
        }
        else
        {
          echo '<div style="float:left; padding-right:3px;">' . $i . '</div>';
        }
      }
      
      if($i < $lastPage){ echo '<div style="float:left;"> ... <a href="' . str_replace('/photos', "/photos/page{$totalPages}", $url) . '">' . $lastPage . '</a></div>'; }
      if($page != $lastPage) { echo '<div style="float:left; padding-left:5px;"><a href="' . str_replace('/photos', "/photos/page{$totalPages}", $url) . '"><img src="images/paging_last.gif" border="0" /></a></div>'; }
    }
    else
    {
      echo 'Showing ' . $fotosTotal . ' photos';
    }
    echo '    </div>
            </div>';
    echo '<br />';
  }
  else
  if(count($tagsArr) == 0) // no tags were provided and no photos returned...so user has no public photos
  {
    if($user_id != $_USER_ID) // not logged in user
    {
      echo '<div class="bold italic" style="padding:0px 15px 5px 15px;" align="center">' . $displayName . ' has not added any photos to their personal page.</div>';
    }
    else // logged in user
    {
      echo '<div class="bold">
              <div>You have not added any photos to your personal page.</div>
              <div style="margin:5px 0px 0px 25px;">
                <div><a href="/?action=fotobox.upload_installer" class="plain"><img src="images/icons/left_up_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="4" align="absmiddle" />Start by uploading some photos</a></div>
                <div><a href="/xml_result?action=fotopage_list_fotos&subsction=' . $subaction . '" class="plain lbOn"><img src="images/icons/add_alt_2_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="4" align="absmiddle" />Then add your photos or slideshows to your personal page</a></div>
              </div>
            </div>';
    }
  }
  else // tags were provided so display that the search didn't return any results
  {
    echo '<div class="bold italic" style="padding:0px 15px 5px 15px;" align="center">Sorry, your search for <span class="italic">' . $tags . '</span> did not return any matches.</div>';
  }
?>