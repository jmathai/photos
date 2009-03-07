<?php
  /* EXPANSION ARRAYS / BOOLEANS */
  $expand_my_account = strncmp($action, 'account', 7) == 0 ? true : false;
  
  $array_expand_my_fotos = array(
                        'fotobox.fotobox_myfotos' => 1,
                        'fotobox.fotobox_myfotos_create_flix' => 1,
                        'fotobox.view_all_tags' => 1,
                        'fotobox.most_viewed' => 1,
                        'fotobox.upload_history' => 1,
                        'fotobox.calendar' => 1,
                        'fotobox.stats' => 1
                        );
  
  $array_expand_my_flix = array(
                        'flix.flix_list' => 1,
                        'flix.view_all_tags' => 1,
                        'flix.most_viewed' => 1,
                        'flix.flix_create_prompt' => 1,
                        'home.slideshow_share' => 1,
                        'flix.manage' => 1,
                        'flix.stats' => 1
                        );
                        
  $array_expand_my_page = array(
                        'mypage.home' => 1,
                        'mypage.profile' => 1,
                        'mypage.flixpage' => 1,
                        'mypage.fotopage' => 1
                        );
                        
  $array_expand_my_group_fotos = array(
                        'fotogroup.group_fotos' => 1,
                        'fotogroup.image_form' => 1,
                        'fotogroup.image_show' => 1,
                        'fotogroup.upload_history' => 1,
                        'fotogroup.advanced_search_form' => 1,
                        'fotogroup.advanced_search_results' => 1,
                        'fotogroup.label_form' => 1,
                        'fotogroup.label_manage' => 1,
                        'fotogroup.label_assign_form' => 1,
                        'fotogroup.group_form' => 1
                        );
  
  $expand_my_fotos = isset($array_expand_my_fotos[$action]) ? true : false; // keep strncmp because fotobox.fotobox_myfotos* satisfies this condition
  $expand_my_flix = isset($array_expand_my_flix[$action]) ? true : false;
  $expand_my_page = isset($array_expand_my_page[$action]) ? true : false;
  $expand_videos = strncmp($action, 'video', 5) == 0 ? true : false;
  $expand_my_groups = strncmp($action, 'fotogroup.', 10) == 0 ? true : false;
  
  //$expand_tags      = strcmp($action, 'fotobox.view_all_tags') == 0 ? true : false;
  //$expand_my_flix   = strncmp($action, 'flix', 4) == 0 ? true : false;
  //$expand_my_page   = strncmp($action, 'mypage.', 7) == 0 ? true : false;
  //$expand_my_page_profile = strcmp($action, 'mypage.profile') == 0 ? true : false;
  //$expand_my_page_flixpage = strcmp($action, 'mypage.flixpage') == 0 ? true : false;
  //$expand_my_page_fotopage = strcmp($action, 'mypage.fotopage') == 0 ? true : false;
  //$expand_my_notepad = strncmp($action, 'notepad', 7) == 0 ? true : false;
  //$expand_my_groups_fotos = isset($array_expand_my_group_fotos[$action]) ? true : false;
  //$expand_group_form = strcmp($action, 'fotogroup.group_form') == 0 ? true : false;
  //$expand_my_groups_flix  = strncmp($action, 'fotogroup.flix', 14) == 0 ? true : false;
  //$expand_my_groups_games = strncmp($action, 'fotogroup.games', 15) == 0 ? true : false;

  echo '<div id="navigation">
          <div><a href="javascript:findUploader();" title="upload your photos"><img src="/images/navigation/upload.gif" border="0" /></a></div>';
  
  // START PHOTOS
  if($expand_my_fotos === true)
  {
    echo '<div class="nav_row_expanded" onclick="location.href=\'/?action=fotobox.fotobox_myfotos\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/?action=fotobox.fotobox_myfotos" title="go to Photos :: manage your photos"><img src="/images/navigation/my_photos_on.gif" class="float-left" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=fotobox.fotobox_myfotos" title="go to Photos :: manage your photos" style="text-decoration:none;">Photos</a></div>
          </div>';
  }
  else 
  {
    echo '<div class="nav_row" onclick="location.href=\'/?action=fotobox.fotobox_myfotos\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/?action=fotobox.fotobox_myfotos" title="go to Photos :: manage your photos"><img src="/images/navigation/my_photos_off.gif" class="float-left" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=fotobox.fotobox_myfotos" title="go to Photos :: manage your photos" style="text-decoration:none;">Photos</a></div>
          </div>';
  }
  
  // START PHOTOS EXPANSION
  if(isset($_FF_SESSION) && $expand_my_fotos === true)
  {
    echo '<div class="sub_nav">
            <a class="plain" href="/?action=fotobox.fotobox_myfotos&ORDER=VIEWS">
              <div style="float:left; padding-right:2px;"><img src="/images/navigation/views.gif" border="0" /></div>
              <div style="padding-top:5px;">Most Viewed</div>
            </a>
          </div>
          <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>
          
          <div class="sub_nav">
            <a class="plain" href="/?action=fotobox.calendar">
              <div style="float:left; padding-right:2px;"><img src="/images/navigation/history.gif" border="0" /></div>
              <div style="padding-top:5px;">Calendar</div>
            </a>
          </div>
          <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>
          
          <div class="sub_nav">
            <a class="plain" href="/?action=fotobox.view_all_tags">
              <div style="float:left; padding-right:2px;"><img src="/images/navigation/tag.gif" border="0" /></div>
              <div style="padding-top:5px;">View All Tags</div>
            </a>
          </div>
          
          <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
          
          /*if(permission($_FF_SESSION->value('account_perm'), PERM_USER_1) == true)
          {
            echo '<div class="sub_nav">
              <a class="plain" href="/?action=fotobox.stats&type=2">
                <div style="float:left; padding-right:2px;"><img src="/images/icons/chart_alt_2_16x16.png" class="png" width="16" height="16" border="0" hspace="4" /></div>
                <div style="padding-top:5px;">Photo Stats</div>
              </a>
            </div>
            <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
          }*/
          
    echo '<div class="sub_nav">
            <div id="_quicksets" style="margin-left:2px; margin-right:2px; border:solid 1px #686868; background-color:#EEEEEE;">
              <div style="padding-top:4px; padding-left:5px;">
                <div style="float:left; padding-top:2px;">Tag Folders</div>
                <div style="float:left; padding-left:5px; padding-right:5px;"><a class="plain" href="javascript:quickSetTrans(1);" onmouseover="mouseY=_getMouseY(event);" title="add a new Tag Folder"><img src="/images/navigation/ad_quickset.gif" border="0" /></a></div>
                <div><a class="plain" href="javascript:void(0);" id="quickSetToggle" title="edit or delete your Tag Folders"><img src="/images/navigation/manage_quicksets.gif" border="0" /></a></div>
              </div>
              
              <div style="display:none; z-index:2;" id="quickSetDialog"></div>
              
              <div id="quickSets">
              </div>
              
              <div id="quickSetsAjax">
              </div>
                
              <script language="javascript">
                if(_USER_ID  > 0)
                {
                  if(userTags.length > 0)
                  {
                    quickSetRefresh("display");
                  }
                }
              </script>
    
            </div>
          </div>';
  }
  // END PHOTOS EXPANSION
  // END PHOTOS
  
  // START SLIDESHOWS
  if($expand_my_flix === true)
  {
    echo '<div class="nav_row_expanded" onclick="location.href=\'/?action=flix.flix_list\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/?action=flix.flix_list" title="go to slideshows :: view your slideshows"><img src="/images/navigation/my_slideshows_on.gif" class="float-left" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=flix.flix_list" title="go to slideshows :: view your slideshows" style="text-decoration:none;">Slideshows</a></div>
          </div>';
  }
  else 
  {
    echo '<div class="nav_row" onclick="location.href=\'/?action=flix.flix_list\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/?action=flix.flix_list" title="go to slideshows :: view your slideshows"><img src="/images/navigation/my_slideshows_off.gif" class="float-left" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=flix.flix_list" title="go to slideshows :: view your slideshows" style="text-decoration:none;">Slideshows</a></div>
          </div>';
  }
  
  // START SLIDESHOWS EXPANSION
  if(isset($_FF_SESSION) && $expand_my_flix === true) // START MY FLIX EXPANSION
  {
    echo '<div class="sub_nav">
            <a class="plain" href="/?action=flix.view_all_tags">
              <div style="float:left; padding-right:2px;"><img src="/images/navigation/tag.gif" border="0" /></div>
              <div style="padding-top:5px;">View All Tags</div>
            </a>
          </div>
          <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
          
    echo '<div class="sub_nav">
            <a class="plain" href="/?action=flix.flix_list&ORDER=views">
              <div style="float:left; padding-right:2px;"><img src="/images/navigation/views.gif" border="0" /></div>
              <div style="padding-top:5px;">Most Viewed</div>
            </a>
          </div>
          <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
          
    echo '<div class="sub_nav">
            <a class="plain" href="/?action=flix.flix_create_prompt">
              <div style="float:left; padding-right:2px;"><img src="/images/navigation/new_slideshow.gif" border="0" /></div>
              <div style="padding-top:5px;">New Slideshow</div>
            </a>
          </div>
          <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
          
          if(permission($_FF_SESSION->value('account_perm'), PERM_USER_1) === true)
          {
            echo '<div class="sub_nav">
                    <a class="plain" href="/?action=flix.manage">
                      <div style="float:left; padding-right:2px;"><img src="/images/navigation/edit_slideshow_page.gif" width="24" height="22" border="0" /></div>
                      <div style="padding-top:5px;">Manage Slideshows</div>
                    </a>
                  </div>
                  <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
            
            echo '<div class="sub_nav">
                    <a class="plain" href="/?action=flix.stats&type=1">
                      <div style="float:left; padding-right:2px;"><img src="/images/icons/chart_alt_2_16x16.png" class="png" width="16" height="16" border="0" hspace="4" /></div>
                      <div style="padding-top:5px;">Slideshow Stats</div>
                    </a>
                  </div>
                  <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
          }
  }
  // END SLIDESHOWS EXPANSION
  // END SLIDESHOWS
  
  // START VIDEOS
  if(permission($_FF_SESSION->value('account_perm'), PERM_USER_1) === true)
  {
    if($expand_videos === true)
    {
      echo '<div class="nav_row_expanded" onclick="location.href=\'/?action=video.list\'" style="cursor:pointer;">
              <div style="padding-top:1px;"><a href="/?action=video.list" title="go to videos :: view your videos"><img src="/images/navigation/my_videos_on.gif" class="float-left" border="0" /></a></div>
              <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=video.list" title="go to videos :: view your videos" style="text-decoration:none;">Videos</a></div>
            </div>
            <div class="sub_nav">
              <a class="plain" href="/?action=video.upload_form">
                <div style="float:left; padding-right:2px;"><img src="/images/navigation/my_video_upload.gif" width="16" height="16" border="0" vspace="2" hspace="4" /></div>
                <div style="padding-top:3px;">Upload Video</div>
              </a>
            </div>
            
            <div class="sub_nav_divider"><img src="/images/navigation/sub_nav_divider.gif" border="0" /></div>';
    }
    else 
    {
      echo '<div class="nav_row" onclick="location.href=\'/?action=video.list\'" style="cursor:pointer;">
              <div style="padding-top:1px;"><a href="/?action=video.list" title="go to videos :: view your videos"><img src="/images/navigation/my_videos_off.gif" class="float-left" border="0" /></a></div>
              <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=video.list" title="go to videos :: view your videos" style="text-decoration:none;">Videos</a></div>
            </div>';
    }
  }
  // END VIDEOS
  
  // START PRINTING
  echo '<div class="nav_row_plain" onclick="location.href=\'/?action=printing.home\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/?action=printing.home" title="go to printing :: print your photos"><img src="/images/navigation/my_printing_' . (strncmp($action, 'printing', 8) == 0 ? 'on' : 'off') . '.gif" class="float-left" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=printing.home" title="go to printing :: print your photos" style="text-decoration:none;">Printing</a></div>
          </div>';
  // END PRINTING
  
  // START PHOTO PAGE
  echo '<div class="nav_row_plain" onclick="location.href=\'/users/' . $_FF_SESSION->value('username') . '/settings/\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/users/' . $_FF_SESSION->value('username') . '/settings/" title="go to videos :: view/edit your personal page"><img src="/images/navigation/my_photo_page_off.png" class="float-left" hspace="6" vspace="5" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/users/' . $_FF_SESSION->value('username') . '/settings/" title="go to Photo Page setup :: set up Photo Page" style="text-decoration:none;">Personal Page</a></div>
          </div>';
  
  // START PHOTO PAGE PRO
  /*else
  {
    if($expand_my_page === false)
    {
    }
    else 
    {
    }
    
    // START PHOTO PAGE EXPANSION PRO
    if(isset($_FF_SESSION) && $expand_my_page === true) // START MY PAGE EXPANSION
    {
    }
    // END PHOTO PAGE EXPANSION PRO
  }*/
  // END PHOTO PAGE
  
  // START GROUPS
  /*
  if($expand_my_groups === true)
  {
    echo '<div class="nav_row_expanded" onclick="location.href=\'/?action=group.home&group_id=100\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/?action=group.home&group_id=100" title="go to groups :: view and participate in groups"><img src="/images/navigation/my_groups_on.gif" class="float-left" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=group.home&group_id=100" title="go to groups :: view and participate in groups" style="text-decoration:none;">Groups</a></div>
          </div>';
  }
  else 
  {
    echo '<div class="nav_row" onclick="location.href=\'/?action=group.home&group_id=100\'" style="cursor:pointer;">
            <div style="padding-top:1px;"><a href="/?action=group.home&group_id=100" title="go to groups :: view and participate in groups"><img src="/images/navigation/my_groups_off.gif" class="float-left" border="0" /></a></div>
            <div style="float:left; padding-top:7px; padding-left:7px;" class="f_8 bold"><a href="/?action=group.home&group_id=100" title="go to groups :: view and participate in groups" style="text-decoration:none;">Groups</a></div>
          </div>';
  }
  
  $group_id = 100;
  
  // START GROUPS EXPANSION
  if(isset($_FF_SESSION) && $expand_my_groups === true) // START FOTOGROUP EXPANSION
  {
    echo '<div style="border-bottom:solid 1px #979DAA" class="sub_nav">
            <a class="plain" href="/?action=fotogroup.group_form">
              <div style="float:left; padding-right:2px;"><img src="/images/navigation/new_group.gif" border="0" /></div>
              <div style="padding-top:5px;">New Group</div>
            </a>
          </div>';
    
    echo '<div class="sub_nav">
            <div id="_groupQuicksets" style="margin-left:2px; margin-right:2px; border:solid 1px #686868; background-color:#EEEEEE;">
              <div style="padding-top:4px; padding-left:5px;">
                <div style="float:left; padding-top:2px;">Tag Folders</div>
                <div style="float:left; padding-left:15px; padding-right:5px;"><a class="plain" href="javascript:groupQuickSetTrans(1, ' . $group_id . ');" onmouseover="mouseY=_getMouseY(event);"><img src="/images/navigation/ad_quickset.gif" border="0" /></a></div>
                <div><a class="plain" href="" id="groupQuickSetToggle"><img src="/images/navigation/manage_quicksets.gif" border="0" /></a></div>
              </div>
              
              <div style="display:none; z-index:2;" id="groupQuickSetDialog"></div>
              
              <div id="groupQuickSets">
              </div>
              
              <div id="groupQuickSetsAjax">
              </div>
                
              <script language="javascript">
                if(userTags.length > 0)
                {
                  groupQuickSetRefresh("display", ' . $group_id . ');
                }
              </script>
    
            </div>
          </div>';
  }
  // END GROUPS EXPANSION
  // END GROUPS
  */
  
  /*echo '
        <br/><br/>
        <!-- Beginning of meebo me widget code.
        Want to talk with visitors on your page?  
        Go to http://www.meebome.com/ and get your widget! -->
        <embed src="http://widget.meebo.com/mm.swf?jSClMgbiTT" type="application/x-shockwave-flash" wmode="transparent" width="150" height="250"></embed>';*/
  
  echo '</div>';
?>