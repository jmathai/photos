<?php
  $u =& CUser::getInstance(); 
  
  $cntFriends = $u->countFriends($user_id);
  
  if($logged_in === true)
  {
    $friendStatus = $u->isFriend($_USER_ID, $user_id);
  }
  
  $groupId = intval($_GET['group_id']);
  $my_fotos_url = '/users/' . $username . '/photos';
  
  // get quickset html to be displayed in the navigation for photos, slideshows, videos and tags
  if(strncmp($subaction, 'photo', 5) == 0 || strncmp($subaction, 'slideshow', 9) == 0 || strncmp($subaction, 'tag', 3) == 0 || strncmp($subaction, 'videos', 6) == 0)
  {
    if(strncmp($subaction, 'photo', 5) == 0)
    {
      $sectionContext = 'photos';
      $tagsPrefix = 'tags-';
    }
    else
    if(strncmp($subaction, 'slideshow', 9) == 0)
    {
      $sectionContext = 'slideshows';
      $tagsPrefix = 'tags-';      
    }
    else
    if(strncmp($subaction, 'tag', 3) == 0)
    {
      $sectionContext = 'tags';
      $tagsPrefix = '';
    }
    else
    if(strncmp($subaction, 'videos', 6) == 0)
    {
      $sectionContext = 'videos';
      $tagsPrefix = 'tags-';
    }
    
    include_once PATH_CLASS . '/CTag.php';
    $tg =& CTag::getInstance();
    $quicksets = $tg->quickSets($user_id); // get parent quicksets
    
    $quicksetHtml = '';
    if(count($quicksets) > 0)
    {
      $quicksetHtml .= '<div class="groupNavLink">';
    }
    
    foreach($quicksets as $v) // loop over parent quicksets
    {
      $children = $tg->quickSets($user_id, $v['S_ID']); // check if this quickset has children
      if(count($children) == 0) // if there are no children then just display a link without nesting heirarchy
      {
        $quicksetHtml .= '<div class="quickSetParentRow">
                            <img src="images/spacer.gif" width="15" height="15" border="0" />
                            <a href="/users/' . $username . '/' . $sectionContext . '/' . $tagsPrefix . $v['S_TAGS'] . '/quickset-' . $v['S_ID'] . '-' . urlencode($v['S_NAME']) . '/" class="plain f_white">' . str_mid($v['S_NAME'],15) . '</a>
                          </div>';
      }
      else // if children exist then display with nested children
      {
        $quicksetHtml .= '<div class="quickSetParentRow">
                            <a href="javascript:void(0);" onclick="qsEffect_' . $v['S_ID'] . '.toggle(); _toggle_arrow(\'qsArrow_' . $v['S_ID'] . '\');" class="plain"><img src="images/navigation/sub_arrow_' . ($quicksetId == $v['S_ID'] ? 'open' : 'close') . '.gif" id="qsArrow_' . $v['S_ID'] . '" width="15" height="15" border="0" align="absmiddle" /></a>
                            <a href="/users/' . $username . '/' . $sectionContext . '/' . $tagsPrefix . $v['S_TAGS'] . '/quickset-' . $v['S_ID'] . '-' . urlencode($v['S_NAME']) . '/" class="plain f_white">' . str_mid($v['S_NAME'],15) . '</a>
                          </div>
                          <div id="qsGroup_' . $v['S_ID'] . '">';
        
        foreach($children as $v2) // loop over the children quicksets
        {
          $quicksetHtml .= '<div class="quickSetChildRow">
                              <div style="padding-left:20px;">
                                <a href="/users/' . $username . '/' . $sectionContext . '/' . $tagsPrefix . $v2['S_TAGS'] . '/quickset-' . $v['S_ID'] . '-' . urlencode($v['S_NAME']) . '/" class="plain childLink">' . str_mid($v2['S_NAME'],15) . '</a>
                              </div>
                            </div>';
        }
        
        $quicksetHtml .= '</div>
                          <script type="text/javascript"> var qsEffect_' . $v['S_ID'] . ' = new fx.Height("qsGroup_' . $v['S_ID'] . '");</script>';
        
        if($quicksetId != $v['S_ID'])
        {
          $quicksetHtml .= '<script type="text/javascript"> qsEffect_' . $v['S_ID'] . '.hide(); </script>';
        }
      }
    }
    
    if(count($quicksets) > 0)
    {
      $quicksetHtml .= '</div>';
    }
  }
?>


<div id="navigation" class="bold">
  <?php
    if($logged_in === true && $_USER_ID != $user_id)
    {
      echo '<div style="margin:10px 0px 0px 12px;">
              <img src="/images/icons/user_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;
              <span id="friendStatus">';
      switch($friendStatus['UF_STATUS'])
      {
        case 'Confirmed':
          echo 'You are friends';
          break;
        case 'Requested':
          echo 'Request sent';
          break;
        default:
          echo '<a href="javascript:void(0);" onclick="effRequest.toggle();" class="plain" title="Add ' . urlencode($displayName) . ' as  your friend">Add as friend</a>';
          break;
      }
      
      echo '  </span>
            </div>
            <div id="friendMessage" style="position:absolute; padding:5px; margin:12px; background-color:#fff;" class="border_lite">
              <div class="bold">Enter a message</div>
              <div>
                <textarea id="requestMessage" class="formfield" rows="5" cols="30"></textarea>
              </div>
              <div>
                <a href="javascript:void(0);" onclick="addFriend(' . $user_id . ', $F(\'requestMessage\'));" class="plain"><img src="/images/icons/mail_alt_2_16x16.png" class="png" width="16" height="16" vspace="5" border="0" align="absmiddle" />Send request</a>
              </div>
            </div>
            <script>
              var effRequest = new fx.Opacity("friendMessage");
              effRequest.hide();
            </script>
            ';

    }
  ?>
  <div class="groupNavLink groupNavLinkOff">
    <div style="margin:10px 0px 10px 0px;">
      <form action="" id="userTagSearchHeaderForm" onsubmit="return doHeaderSearch('<?php echo $username; ?>', $('userTagSearchHeader').value);">
        <input type="text" id="userTagSearchHeader" value="search tags" style="width:100px; font-weight:normal;" class="formfield formfield_inactive" onfocus="formFieldActive(this);" onblur="formFieldInactive(this, this.value);" /><div autocomplete="off" id="fbTagSearch_auto_complete" class="auto_complete"></div>
        <a href="javascript:void(0);" onclick="doHeaderSearch('<?php echo $username; ?>', $('userTagSearchHeader').value);"><img src="images/icons/search_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" hspace="3" title="Search by this user's tags" /></a>
      </form>
      <?php
        /*if($cntFriends > 0)
        {
          echo '<div>include friends &nbsp; <input type="checkbox" value="1" id="includeFriends" /></div>';
        }*/
      ?>
      <script type="text/javascript"> new Autocompleter.Local("userTagSearchHeader", "fbTagSearch_auto_complete", userTags, {tokens: ','}); </script>
    </div>
  </div>
  <div class="groupNavLink <?php echo ($subaction == 'home' ? 'groupNavLinkOn' : 'groupNavLinkOff'); ?>"><a href="/users/<?php echo $username; ?>/" class="plain">Home</a></div>
  <?php
    if($cntFriends > 0)
    {
      echo '<div class="groupNavLink ' . ($subaction == 'friends' ? 'groupNavLinkOn' : 'groupNavLinkOff') . '"><a href="/users/' . $username . '/friends/" class="plain">Friends</a></div>';
    }
  ?>
  <div class="groupNavLink <?php echo ($subaction == 'photo' || $subaction == 'photos' ? 'groupNavLinkOn' : 'groupNavLinkOff'); ?>"><a href="<?php echo $my_fotos_url; ?>/" class="plain">Photos</a></div>
  <?php
    // show quicksets for photos
    if(strncmp($subaction, 'photo', 5) == 0)
    {
      echo $quicksetHtml;
    }
  ?>
  <div class="groupNavLink <?php echo ($subaction == 'slideshows' ? 'groupNavLinkOn' : 'groupNavLinkOff'); ?>"><a href="/users/<?php echo $username; ?>/slideshows/" class="plain">Slideshows</a></div>
  <?php
    // show quicksets for slideshows
    if(strncmp($subaction, 'slideshow', 9) == 0)
    {
      echo $quicksetHtml;
    }
    
    // show videos for professionals
    if(permission($userData['U_ACCOUNTTYPE'], PERM_USER_1))
    {
      echo '<div class="groupNavLink ' . ($subaction == 'videos' ? 'groupNavLinkOn' : 'groupNavLinkOff') . '"><a href="/users/' . $username . '/videos/" class="plain">Videos</a></div>';
      if(strncmp($subaction, 'videos', 6) == 0)
      {
        echo $quicksetHtml;
      }
    }
  ?>
  <div class="groupNavLink <?php echo ($subaction == 'tags' ? 'groupNavLinkOn' : 'groupNavLinkOff'); ?>"><a href="/users/<?php echo $username; ?>/tags/" class="plain">Tags</a></div>
  <?php
    // show quicksets for photos
    if(strncmp($subaction, 'tag', 3) == 0)
    {
      echo $quicksetHtml;
    }
  ?>
  <div class="groupNavLink <?php echo ($subaction == 'profile' ? 'groupNavLinkOn' : 'groupNavLinkOff'); ?>"><a href="/users/<?php echo $username; ?>/profile/" class="plain">Profile</a></div>
  <?php
    if($_USER_ID == 1 || $_USER_ID == 2 || $_USER_ID == 3 || $user_id == 1 || $user_id == 2 || $user_id == 3)
    {
  ?>
      <div class="groupNavLink <?php echo ($subaction == 'blog' ? 'groupNavLinkOn' : 'groupNavLinkOff'); ?>"><a href="/users/<?php echo $username; ?>/blog/" class="plain">Blog</a></div>
  <?php
    }
  ?>
</div>