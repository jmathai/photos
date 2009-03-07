<?php
  $my_fotos_url = '/users/' . $username . '/fotos';
  
  if($subaction != 'flixview')
  {
?>
    <div class="my_nav" align="center">
      <div><img src="images/my_nav_top.gif" width="166" height="2" border="0" /></div>
      <div><a href="/users/<?php echo $username; ?>/" title="View :: My FotoPage"><img src="images/my_nav_home_<?php echo (strncmp($subaction, 'home', 4) != 0 || $subaction == '') ? 'off' : 'on'; ?>.gif" width="166" height="27" border="0" /></a></div>
      <div><a href="/users/<?php echo $username; ?>/profile/" title="View :: About Me"><img src="images/my_nav_profile_<?php echo strncmp($subaction, 'profile', 7) != 0 ? 'off' : 'on'; ?>.gif" width="166" height="27" border="0" /></a></div>
      <div><a href="<?php echo $my_fotos_url; ?>/" title="View :: My Fotos"><img src="images/my_nav_fotos_<?php echo strncmp($subaction, 'foto', 4) != 0 ? 'off' : 'on'; ?>.gif" width="166" height="27" border="0" /></a></div>
      <div style="background-image: url('/images/navigation/nav_sub_bg.gif');" align="center">
      <?php
        if(strncmp($subaction, 'foto', 4) == 0)
        {
          include_once PATH_CLASS . '/CTag.php';
          include_once PATH_DOCROOT . '/user_data_js.dsp.php';
          echo '<div style="width:160px; text-align:left;" class="my_bg_lite">
                  <div style="padding-top:5px; padding-left:6px;">
                    <form name="tagwordsForm" style="display:inline;" onsubmit="return doSearchTags();">
                      <div>
                        <div style="float:left;"><img src="images/tag_search_icon.gif" width="11" height="16" border="0" /></div>
                        <div style="float:left; padding-left:3px;"><input autocomplete="off" type="text" id="tagwords" class="formfield" style="width:65px; border:solid 1px #809eba;" value="' . (isset($tags) ? htmlentities($tags) : '') . '" /><div id="auto_complete_tagwords" class="auto_complete" style="width:150px; z-index:75;"></div></div>
                        <div style="font-size:9px; padding-top:3px;">&nbsp;<span style="cursor:pointer;" onclick="void(doSearchTags());">Search tags</span></div>
                      </div>
                    </form>
                    <script language="javascript">
                      function doSearchTags()
                      {
                        if(document.getElementById("tagwords").value.length > 0)
                        {
                          location.href="/users/' . $username . '/fotos/tags-"+document.getElementById("tagwords").value + "/";
                        }
                        return false;
                      }
                      
                      new Autocompleter.Local("tagwords", "auto_complete_tagwords", userTags, {tokens: ","});
                    </script>
                  </div>
                  <div style="padding-top:5px; padding-bottom:5px;">
                    <div style="padding-left:10px;"><a href="/users/' . $username . '/tags/">' . $displayName . '\'s tags</a></div>
                  </div>
                </div>';
          
          $objTags =& CTag::getInstance();
          $arr_tags   = $objTags->quickSets($user_id, 0);
          foreach($arr_tags as $v)
          {
            $arr_children = $objTags->quickSets($user_id, $v['S_ID']);
            $has_children = count($arr_children) == 0 ? false : true;
            $arrow_html = $has_children === false ? '<img src="images/spacer.gif" width="15" height="15" border="0" />' : '<a href="javascript:_toggle_nav(' . $v['L_ID'] . '); _toggle_arrow(\'_arrow_' . $v['L_ID'] . '\');" title="click to show/hide sub labels"><img src="images/navigation/sub_arrow_closed.gif" width="15" height="15" id="_arrow_' . $v['L_ID'] . '" border="0" /></a>';
            echo '<div class="quickSetParentRow">
                   <div>';
            if($has_children === false)
            {
              echo '<div style="float:left; padding-left:2px; padding-right:3px;"><img src="images/spacer.gif" width="15" height="15" border="0" /></div>';
            }
            else 
            {
              echo '<div style="float:left; padding-left:2px; padding-right:3px;"><a href="javascript:_toggle(\'quickSetChild_' . $v['S_ID'] . '\'); _toggle_arrow(\'_arrow_' . $v['S_ID'] . '\');"><img src="images/navigation/sub_arrow_close.gif" id="_arrow_' . $v['S_ID'] . '" width="15" height="15" border="0" /></a></div>';
            }
                    
            if($v['S_TAGS'] != '')
            {
              echo '<div style="float:left;"><a href="/users/' . $username . '/fotos/tags-' . $v['S_TAGS'] . '/" class="f_9 f_black bold" style="text-decoration:none;">' . $v['S_NAME'] . '</a></div>';
            }
            else
            {
              echo '<div style="float:left;"><a href="/users/' . $username . '/fotos/" class="f_9 f_black bold" style="text-decoration:none;">' . $v['S_NAME'] . '</a></div>';
            }  
            echo ' </div>
                  </div>
                  <div id="quickSetChild_' . $v['S_ID'] . '" style="display:none;">';
            
            foreach($arr_children as $v2)
            {
      ?>
              <div class="quickSetChildRow">
               <div style="padding-left:23px; text-align:left;"><a href="/users/<?php echo $username; ?>/fotos/tags-<?php echo $v2['S_TAGS']; ?>/" class="childLink f_white"><?php echo $v2['S_NAME']; ?></a></div>
              </div>
      <?php
            }
            echo '</div>'; // end quickset child div
          } // END INNER LOOP 2
        } // END INNER LOOP 1
      ?>
      </div>
      <div><a href="/users/<?php echo $username; ?>/flix/" title="View :: My Flix"><img src="images/my_nav_fotoflix_<?php echo strncmp($subaction, 'flix', 4) != 0 ? 'off' : 'on'; ?>.gif" width="166" height="27" border="0" /></a></div>
      <?php
        if(strncmp($subaction, 'flix', 4) == 0)
        {
          echo '<div style="background-image: url(\'/images/navigation/nav_sub_bg.gif\');" align="center">
                  <div style="width:160px; text-align:left;" class="my_bg_lite">
                    <div style="padding-top:5px; padding-left:6px;">
                      <form name="tagwordsForm" style="display:inline;" onsubmit="return doSearchTags();">
                        <div>
                          <div style="float:left;"><img src="images/tag_search_icon.gif" width="11" height="16" border="0" /></div>
                          <div style="float:left; padding-left:3px;"><input type="text" id="tagwords" class="formfield" style="width:65px; border:solid 1px #809eba;" value="' . (isset($tags) ? htmlentities($tags) : '') . '" /><div id="auto_complete_tagwords" class="auto_complete" style="width:150px; z-index:75;"></div></div>
                          <div style="font-size:9px; padding-top:3px;">&nbsp;<span style="cursor:pointer;" onclick="void(doSearchTags());">Search tags</span></div>
                        </div>
                      </form>
                      <script language="javascript">
                        function doSearchTags()
                        {
                          if(document.getElementById("tagwords").value.length > 0)
                          {
                            location.href="/users/' . $username . '/flix/tags-"+document.getElementById("tagwords").value + "/";
                          }
                          return false;
                        }
                        
                        new Autocompleter.Local("tagwords", "auto_complete_tagwords", userTags, {tokens: ","});
                      </script>
                    </div>
                    <div style="padding-top:5px; padding-bottom:5px;">
                      <div style="padding-left:10px;"><a href="/users/' . $username . '/tags/">' . $displayName . '\'s tags</a></div>
                    </div>                    
                  </div>
                </div>';
        }
      ?>
      <div><a href="/users/<?php echo $username; ?>/tags/" title="View :: My Tags"><img src="images/my_nav_tags_<?php echo strncmp($subaction, 'tags', 4) != 0 ? 'off' : 'on'; ?>.gif" width="166" height="27" border="0" /></a></div>
      <?php
        if(strncmp($subaction, 'tags', 4) == 0)
        {
          echo '<div style="background-image: url(\'/images/navigation/nav_sub_bg.gif\');" align="center">
                  <div style="width:160px; text-align:left;" class="my_bg_lite">
                    <div style="padding-top:5px; padding-left:6px; padding-bottom:8px;">
                      <form name="tagwordsForm" style="display:inline;" onsubmit="return doSearchTags();">
                        <div>
                          <div style="float:left;"><img src="images/tag_search_icon.gif" width="11" height="16" border="0" /></div>
                          <div style="float:left; padding-left:3px;"><input type="text" id="tagwords" class="formfield" style="width:65px; border:solid 1px #809eba;" value="' . (isset($options[0]) ? htmlentities($options[0]) : '') . '" /><div id="auto_complete_tagwords" class="auto_complete" style="width:150px; z-index:75;"></div></div>
                          <div style="font-size:9px; padding-top:3px;">&nbsp;<span style="cursor:pointer;" onclick="void(doSearchTags());">Search tags</span></div>
                        </div>
                      </form>
                      <script language="javascript">
                        function doSearchTags()
                        {
                          if(document.getElementById("tagwords").value.length > 0)
                          {
                            location.href="/users/' . $username . '/tags/"+document.getElementById("tagwords").value + "/";
                          }
                          return false;
                        }
                        
                        new Autocompleter.Local("tagwords", "auto_complete_tagwords", userTags, {tokens: ","});
                      </script>
                    </div>
                  </div>
                </div>';
        }
      ?>
      <div><img src="images/my_nav_bottom.gif" width="166" height="2" border="0" /></div>
      <?php
        if(strncmp($subaction, 'foto', 4) == 0)
        {
          include_once PATH_CLASS . '/CFlix.php';
          include_once PATH_CLASS . '/CFotobox.php';
          $fl =& CFlix::getInstance();
          $fb =& CFotobox::getInstance();
          
          $arrTags = isset($tags) ? (array)explode(',', $tags) : false;
          $recentFlix = $fl->flixByTags($arrTags, $user_id, 3, 'user', false, 0, 3);
          
          if(count($recentFlix) == 0 && $arrTags !== false)
          {
            $recentFlix = $fl->flixByTags(false, $user_id, 3, 'user', false, 0, 3);
            $arrTags = false;
          }
          
          if(count($recentFlix) > 0)
          {
            echo '<div class="border_dark my_bg_lite" style="margin-top:15px; width:166px;">';
            if($arrTags === false)
            {
              echo '<div style="padding-top:3px; padding-bottom:8px; font-size:11px;">' . $displayName . "'s Recent Flix</div>";
            }
            else 
            {
              echo '<div style="padding-top:3px; padding-bottom:8px; font-size:11px;">' . $displayName . "'s Flix  tagged with <span class=\"italic\">";
              $tmp = '';
              foreach($arrTags as $v)
              {
                $tmp .= '<a href="/users/' . $username . '/flix/tags-' . urlencode($v) . '/" title="view fotos tagged with ' . htmlentities($v) . '">' . $v . '</a>, ';
              }
              echo substr($tmp, 0, -2) . '</span></div>';
            }
            
            foreach($recentFlix as $v)
            {
              $foto_id    = $v['A_DATA'][0]['D_UP_ID'];
              $foto_data  = $fb->fotoData($foto_id);
              
              echo '<div style="padding-bottom:15px;" align="center">
                      <div style="padding-left:20px;">
                        <div class="flix_border"><a href="/fastflix?' . $v['A_FASTFLIX'] . '" title="Click to view Flix" target="_blank"><img src="' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '" width="75" height="75" border="0" /></a></div>
                      </div>
                      ' . str_mid($v['A_NAME'], 30) . '
                    </div>';
            }
            echo '</div>';
          }
        }
        else
        if(strncmp($subaction, 'flix', 4) == 0 || strncmp($subaction, 'profile', 6) == 0 || strncmp($subaction, 'home', 4) == 0)
        {
          include_once PATH_CLASS . '/CFotobox.php';
          $fb =& CFotobox::getInstance();
          
          $arrTags = isset($tags) ? (array)explode(',', $tags) : false;
          $recentFotos = $fb->fotosByTags($arrTags, $user_id, 3, false, 0, 6);
          
          if(count($recentFotos) == 0 && $arrTags !== false)
          {
            $recentFotos = $fl->fotosByTags(false, $user_id, 3, false, 0, 6);
            $arrTags = false;
          }
          
          if(count($recentFotos) > 0)
          {
            echo '<div class="border_dark my_bg_lite" style="margin-top:15px; width:166px;">';
            if($arrTags === false)
            {
              $urlTags = '';
              echo '<div style="padding-top:3px; padding-bottom:8px; font-size:11px;">' . $displayName . "'s Recent fotos</div>";
            }
            else 
            {
              $urlTags = 'tags-' . $tags . '/';
              echo '<div style="padding-top:3px; padding-bottom:8px; font-size:11px;">' . $displayName . "'s fotos tagged with <span class=\"italic\">";
              $tmp = '';
              foreach($arrTags as $v)
              {
                $tmp .= '<a href="' . $my_fotos_url . '/tags-' . urlencode($v) . '/" title="view fotos tagged with ' . htmlentities($v) . '">' . $v . '</a>, ';
              }
              echo substr($tmp, 0, -2) . '</span></div>';
            }
            
            echo '<div style="padding-left:8px;">';
            foreach($recentFotos as $k => $v)
            {
              if($k % 2 == 0)
              {
                echo '<div style="padding-bottom:10px; float:left;"><a href="/users/' . $username . '/foto/' . $v['P_ID'] . '/' . $urlTags . '" title="view this foto"><img src="' . PATH_FOTO . $v['P_THUMB_PATH'] . '" width="65" height="65" border="0" class="border_dark" /></a></div>';
              }
              else
              {
                echo '<div style="padding-bottom:10px;"><a href="/users/' . $username . '/foto/' . $v['P_ID'] . '/' . $urlTags . '" title="view this foto"><img src="' . PATH_FOTO . $v['P_THUMB_PATH'] . '" width="65" height="65" border="0" class="border_dark" /></a></div>';
              }
            }
            echo '  </div>
                  </div>';
          }
        }
      ?>
    </div>
<?php
  }
?>
<div class="my_content">