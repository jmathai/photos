<?php
  $u  =& CUser::getInstance();
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();
  
  $sort = isset($_GET['ORDER']) ? $_GET['ORDER'] : false;
  
  if(!isset($_GET['tags']))
  {
    $flix_array = $fl->search(array('MODE' => 'USER', 'USER_ID' => $_USER_ID, 'TYPE' => 'slideshow', 'ORDER_BY' => $sort));
  }
  else 
  {
    $tags = (array)explode(',', $_GET['tags']);
    $flix_array = $fl->search(array('MODE' => 'USER', 'USER_ID' => $_USER_ID, 'TAGS' => $tags, 'TYPE' => 'slideshow', 'ORDER_BY' => $sort));
  }
  
  $user_data = $u->find($_USER_ID);
  $cnt_flix_array = $flix_array[0]['ROWS'];
  
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $per_page = 12;
  $total_pages = ceil($cnt_flix_array / $per_page);
  $offset = ($page * $per_page) - $per_page;
  $p =& new CPaging($page, 10, $total_pages, 'page', '/', $_SERVER['QUERY_STRING']);
  
  if($cnt_flix_array > 0 || isset($_GET['tags']))
  {
    if($cnt_flix_array > 0)
    {
      echo '<div style="overflow:hidden; padding-top:5px; padding-right:5px; padding-bottom:5px;">';
      echo '<form method="GET" action="">';
      echo '<input type="hidden" name="action" value="flix.flix_list" />';
      
      if($sort !== false)
      {
        echo '<input type="hidden" name="ORDER" value="' . $sort . '" />';
      }
      
      echo '<div style="float:left; width:114px; padding-right:4px; padding-left:15px;"><input onfocus="this.select();" type="text" value="' . (isset($_GET['tags']) ? $_GET['tags'] : "tag search") . '" id="tags" name="tags" class="formfield" style="width:110px;" onblur="tagSearch()" /></div>';
      echo '<div style="float:left; width:29px; padding-right:4px;"><input type="image" src="images/buttons/go.gif" width="25" height="17" border="0" /></div>';
      echo '</form>';
      echo '<div style="float:right; padding-right:50px;">';
      echo '&nbsp;' . $p->getNextPage('<img src="images/paging_next.gif" width="15" height="15" border="0" alt="click to view next" title="click to view next" />');
      if($page != $total_pages)
      {
      echo '&nbsp;' . $p->getLastPage('<img src="images/paging_last.gif" width="15" height="15" border="0" alt="click to view last page" title="click to view last page" />');
      }
      echo '</div>';
      echo '<div style="float:right;">';
      echo '&nbsp;' . $p->getPages();
      echo '</div>';
      echo '<div style="float:right;">';
      if($offset != 0)
      {
        echo $p->getFirstPage('<img src="images/paging_first.gif" width="15" height="15" border="0" alt="click to view first page" title="click to view first page" />');
      }
      echo '&nbsp;' . $p->getPrevPage('<img src="images/paging_previous.gif" width="15" height="15" border="0" alt="click to view previous" title="click to view next" />');
      echo '</div>';
      echo '<div style="float:right;">Page ' . $page . ' of ' . $total_pages . '&nbsp;|&nbsp;</div>';
      echo '</div>';
      echo '<div class="auto_complete" id="tags_auto_complete" style="width:100px;"></div>';
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
          $html .= 'tagged with <span class="italic">' . $_GET['tags'] . '</span> ';
        }
        
        $html .= ' | <a href="/?action=flix.flix_list">clear filter</a></div>';
        
        echo $html;
      }
    
      echo '<div style="width:745px; border-bottom:solid 1px #dddddd; margin-top:8px;"></div>';
      
      $top_limit = $per_page + $offset;
      for($i = $offset; $i < $top_limit; $i++)
      {
        // a flix exists
        if($i < $cnt_flix_array)
        {
          $fotoURL = $flix_array[$i]['US_PHOTO']['thumbnailPath_str'];
          
          if($i % ($per_page/2) == 0)
          {
            echo '<div style="float:left; overflow:hidden; border-left:solid 1px #dddddd; border-right:solid 1px #dddddd; border-bottom:solid 1px #dddddd; padding-top:10px; padding-left:10px; padding-right:10px; text-align:center; width:103px; height:148px;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          else 
          {
            echo '<div style="float:left; overflow:hidden; border-right:solid 1px #dddddd; border-bottom:solid 1px #dddddd; padding-top:10px; padding-left:10px; padding-right:10px; text-align:center; width:103px; height:148px;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          
          echo '  <div class="flix_border"><a href="/xml_result?action=flix_popup&id=' . $flix_array[$i]['US_ID'] . '&offset=' . $i . '&tags=' . (isset($_GET['tags']) ? $_GET['tags'] : false) . '" class="lbOn"><img src="' . PATH_FOTO . $fotoURL . '" border="0" /></a></div>';
          echo '  <div class="f_7 bold"><a href="/xml_result?action=flix_popup&id=' . $flix_array[$i]['US_ID'] . '&offset=' . $i . '&tags=' . (isset($_GET['tags']) ? $_GET['tags'] : false) . '" class="lbOn plain">' . str_mid($flix_array[$i]['US_NAME'], 49) . '</a></div>
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
            echo '<div style="float:left; overflow:hidden; border-left:solid 1px #dddddd; border-right:solid 1px #dddddd; border-bottom:solid 1px #dddddd; padding-top:10px; padding-left:10px; padding-right:10px; text-align:center; width:103px; height:148px;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          else
          {
            echo '<div style="float:left; overflow:hidden; border-right:solid 1px #dddddd; border-bottom:solid 1px #dddddd; padding-top:10px; padding-left:10px; padding-right:10px; text-align:center; width:103px; height:148px;" id="flixBox' . $flix_array[$i]['US_ID'] . '">';
          }
          
          echo '</div>';
        }
        
        if($cnt_flix_array <= (($page * $per_page) - ($per_page / 2)) && $i == ($offset+(($per_page/2)-1)))
        {
          break;
        }
      }
      
      echo '</div>
            <br clear="all" />
            <br/><br/>';
      
      if(permission($_FF_SESSION->value('account_perm'), PERM_USER_1) == true)
      {
        echo '
              <div class="bullet bold"><a href="/?action=flix.gallery_generator">How can I put a gallery of slideshows on my website?</a></div>';
      }
      echo '<div class="bullet"><a href="/?action=home.samples&subaction=all_themes">Where can I see a list of all the slideshow themes?</a></div>
            <div class="bullet"><a href="/?action=home.samples">Can I see samples of slideshows to get some ideas?</a></div>
            ';
      
      echo '<script type="text/javascript"> Event.observe(window, "load", initializeLB, false); </script>';
    }
    else
    {
      echo '<div style="width:300px; margin:auto; padding-top:20px;">';
      echo '<div class="bold">Your search for slideshows had 0 results.</div>';
      echo '<div style="padding-left:20px; padding-top:5px;" class="bold">';
      echo '<div style="padding-top:4px;">';
      echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
      echo '<div><a href="/?action=flix.flix_list">View all of your slideshows</a></div>';
      echo '</div>';
      echo '<div style="padding-top:4px;">';
      echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
      echo '<div><a href="/?action=flix.view_all_tags">View all of your tags</a></div>';
      echo '</div>';
      echo '<div style="padding-top:4px;">';
      echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
      echo '<div><a href="/?action=flix.flix_create_prompt">Create a new slideshow</a></div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
  }
  else
  {
    echo '<div style="width:300px; margin:auto; padding-top:20px;">';
    echo '<div class="bold">You have not created any slideshows.</div>';
    echo '<div style="padding-left:20px; padding-top:5px;" class="bold">';
    echo '<div style="padding-top:4px;">';
    echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
    echo '<div><a href="/?action=flix.flix_create_prompt">Create a new slideshow</a></div>';
    echo '</div>';
    echo '<div style="padding-top:4px;">';
    echo '<div style="float:left; padding-right:3px;"><img src="images/bullet.gif" border="0" /></div>';
    echo '<div><a href="/?action=fotobox.fotobox_myfotos">View all of your photos</a></div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
?>


<script>
new Autocompleter.Local("tags", "tags_auto_complete", userTags);

function tagSearch()
{
  if($('tags').value == '')
  {
    $('tags').value = 'tag search';
  }
}
</script>