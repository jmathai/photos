<?php
  $fl =& CFlix::getInstance();
  
  $sort = 'order'; // isset($_GET['ORDER']) ? $_GET['ORDER'] : false;
  
  if(!isset($tags))
  {
    $flix_array = $fl->search(array('MODE' => 'USER', 'USER_ID' => $user_id, 'TYPE' => 'slideshow', 'ORDER_BY' => $sort, 'PERMISSION' => PERM_SLIDESHOW_PUBLIC));
  }
  else 
  {
    $tagsArr = (array)explode(',', $tags);
    $flix_array = $fl->search(array('MODE' => 'USER', 'USER_ID' => $user_id, 'TYPE' => 'slideshow', 'TAGS' => $tagsArr, 'ORDER_BY' => $sort, 'PERMISSION' => PERM_SLIDESHOW_PUBLIC));
  }
  
  $cnt_flix_array = $flix_array[0]['ROWS'];
  
  $page = isset($page) ? $page : 1;
  $per_page = 9;
  $total_pages = ceil($cnt_flix_array / $per_page);
  $offset = ($page * $per_page) - $per_page;
  $startPage = 1;
  $p =& new CPaging($page, 10, $total_pages, 'page', '/', $_SERVER['QUERY_STRING']);
  
  if($cnt_flix_array > 0 || isset($tags))
  {
    if($cnt_flix_array > 0)
    {
      echo '<div style="overflow:hidden; padding-top:5px; padding-right:5px; padding-bottom:5px;">';
      echo '<div style="float:right; padding-right:100px;">';
      echo '<div>';
      
      if($total_pages > 1)
      {
        echo '<div style="float:left; padding-right:10px;">Pages</div>';
        $url = str_replace("/page{$page}", '', $_SERVER['REQUEST_URI']);
        if($page != 1) { echo '<div style="float:left; padding-right:5px;"><a href="' . str_replace('/slideshows', "/slideshows/page1", $url) . '"><img src="images/paging_first.gif" border="0" /></a></div>'; }
        for($i = $startPage; $i <= $total_pages; $i++)
        { 
          if($i != $page)
          {
            echo '<div style="float:left; padding-right:3px;"><a href="' . str_replace('/slideshows', "/slideshows/page{$i}", $url) . '">' . $i . '</a></div>';
          }
          else
          {
            echo '<div style="float:left; padding-right:3px;">' . $i . '</div>';
          }
        }
        if($page != $total_pages) { echo '<div style="float:left; padding-left:5px;"><a href="' . str_replace('/slideshows', "/slideshows/page{$total_pages}", $url) . '"><img src="images/paging_last.gif" border="0" /></a></div>'; }
      }
      else 
      {
        echo 'Showing ' . $cnt_flix_array . ' slideshows';
      }
      echo '</div>';
      echo '</div>';
      
      echo '</div>';
      echo '<div style="margin-left:15px;">';
      
      if(isset($tags) || $sort !== false)
      {
        $html = '<div style="text-align:center; padding-top:10px; padding-bottom:10px;" class="bold">Viewing slideshows ';
        
        if($sort !== false)
        {
          $html .= 'ordered by <span class="italic">most viewed</span> ';
        }
        if(isset($tags))
        {
          $html .= 'tagged with <span class="italic">' . $tags . '</span> ';
        }
        
        $html .= ' | <a href="/users/' . $username . '/slideshows/">clear filter</a></div>';
        
        echo $html;
      }
    
      echo '<div style="width:700px; padding-top:8px; margin:auto;">';
      
      $top_limit = $per_page + $offset;
      for($i = $offset; $i < $top_limit; $i++)
      {
        // a flix exists
        if($i < $cnt_flix_array)
        {
          $fotoURL = dynamicImage($flix_array[$i]['US_PHOTO']['thumbnailPath_str'], $flix_array[$i]['US_PHOTO']['photoKey_str'], 150, 100);
          
          echo '<div style="float:left; overflow:hidden; padding:20px;" id="flixBox' . $flix_array[$i]['US_ID'] . '">
                  <div class="flix_border_medium"><a href="/slideshow?' . $flix_array[$i]['US_KEY'] . '"><img src="' . $fotoURL . '" width="150" height="100" border="0" /></a></div>
                  <div style="float:left; width:180px;" class="f_7 bold center"><a href="/slideshow?' . $flix_array[$i]['US_KEY'] . '">' . str_mid($flix_array[$i]['US_NAME'], 25) . '</a></div>
                </div>';
            
          if($cnt_flix_array <= (($page * $per_page) - ($per_page / 2)) && $i == ($offset+(($per_page/2)-1)))
          {
            break;
          }
        }
        else 
        {
          echo '<div style="float:left; overflow:hidden; padding:20px; text-align:center;" id="flixBox' . $flix_array[$i]['US_ID'] . '"></div>';
        }
        
        if($cnt_flix_array <= (($page * $per_page) - ($per_page / 2)) && $i == ($offset+(($per_page/2)-1)))
        {
          break;
        }
      }
    
      echo '</div>
            <br clear="all" />';
      echo '<div style="overflow:hidden; padding-top:5px; padding-right:5px; padding-bottom:5px;">';
      
      echo '<br clear="all" />';
      echo '<div style="float:right; padding-right:100px;">';
      echo '<div>';
      
      if($total_pages > 1)
      {
        echo '<div style="float:left; padding-right:10px;">Pages</div>';
        $url = str_replace("/page{$page}", '', $_SERVER['REQUEST_URI']);
        if($page != 1) { echo '<div style="float:left; padding-right:5px;"><a href="' . str_replace('/slideshows', "/slideshows/page1", $url) . '"><img src="images/paging_first.gif" border="0" /></a></div>'; }
        for($i = $startPage; $i <= $total_pages; $i++)
        { 
          if($i != $page)
          {
            echo '<div style="float:left; padding-right:3px;"><a href="' . str_replace('/slideshows', "/slideshows/page{$i}", $url) . '">' . $i . '</a></div>';
          }
          else
          {
            echo '<div style="float:left; padding-right:3px;">' . $i . '</div>';
          }
        }
        if($page != $total_pages) { echo '<div style="float:left; padding-left:5px;"><a href="' . str_replace('/slideshows', "/slideshows/page{$total_pages}", $url) . '"><img src="images/paging_last.gif" border="0" /></a></div>'; }
      }
      else 
      {
        echo 'Showing ' . $cnt_flix_array . ' slideshows';
      }
      echo '</div>';
      echo '</div>';
    }
    else
    {
      echo '<div style="width:400px; margin:auto; padding-top:20px;">';
      echo '<div class="bold">Your search for slideshows tagged with <span class="italic">' . htmlentities($tags) . '</span> had 0 results.</div>';
      echo '<div style="padding-left:20px; padding-top:5px;" class="bold">';
      echo '<div style="padding-top:4px;">';
      echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
      echo '<div><a href="/users/' . $username . '/slideshows/">View all of ' . $displayName . '\'s slideshows</a></div>';
      echo '</div>';
      echo '<div style="padding-top:4px;">';
      echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
      echo '<div><a href="' . $my_fotos_url . '/">View all of ' . $displayName . '\'s photos</a></div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  }
  else
  {
    if($user_id != $_USER_ID) // not logged in user
    {
      echo '<div style="width:425px; margin:auto;">
              <div class="bold italic">' . $displayName . ' has not added any slideshows to their personal page.</div>
              <div style="padding-left:20px; padding-top:5px;" class="bold">
                <div class="bullet"><a href="' . $my_fotos_url . '/">View all of this ' . $displayName . '\'s photos</a></div>
              </div>
            </div>';
    }
    else // logged in user
    {
      echo '<div class="bold">
              <div>You have not added any slideshows to your personal page.</div>
              <div style="margin:5px 0px 0px 25px;">
                <div><a href="/xml_result?action=fotopage_list_fotos&subsction=' . $subaction . '" class="plain lbOn"><img src="images/icons/add_alt_2_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="4" align="absmiddle" />Add your photos or slideshows to your personal page</a></div>
              </div>
            </div>';
    }
  }
?>