<?php
  $groupId = intval($_GET['group_id']);
  $gp =& CGroup::getInstance();
  $fb = &CFotobox::getInstance();
  $us =& CUser::getInstance();
  $fl = &CFlix::getInstance();
  $b = &CBoard::getInstance();
  
  $groupData = $gp->groupData($groupId, $_USER_ID);
  $memberData = $gp->members(array('GROUP_ID' => $groupId, 'LIMIT' => 2));
  
  $params = array('MODE' => 'GROUP', 'GROUP_ID' => $group_id, 'PERMISSION' => PERM_PHOTO_PUBLIC, 'ORDER' => 'P_CREATED', 'LIMIT' => 4);
  $photoData = $fb->fotosSearch($params);
  $photoURL = dynamicImage($photoData[0]['P_THUMB_PATH'], $photoData[0]['P_KEY'], 430, 300);
  $photoLink = '/?action=group.photo&group_id=' . $group_id . '&id=' . $photoData[0]['P_ID'] . '&offset=0';
  $slideshowData = $fl->search(array('MODE' => 'GROUP', 'GROUP_ID' => $group_id, 'LIMIT' => 2));
  $slideshowLinks = array();
  
  $postData = $b->postsByPages($group_id, 0, 3);
?>

<div>
  <div style="float:left; width:430px;">
    <?php
    $photoCount = count($photoData);
    
    if($photoCount > 0)
    {
      echo '<div class="bold" style="margin-bottom:5px;">' . $groupData['G_NAME'] . '\'s recent photos...</div>
            <div style="margin-bottom:20px;" class="border_dark"><a href="' . $photoLink . '"><img src="' . $photoURL . '" width="430" height="300"  border="0" /></a></div>';
      for($i = 1; $i < $photoCount; $i++)
      {
        $photoLink = '/?action=group.photo&group_id=' . $group_id . '&id=' . $photoData[$i]['P_ID'] . '&offset=' . $i;
        $url = dynamicImage($photoData[$i]['P_THUMB_PATH'], $photoData[$i]['P_KEY'], 115, 50);
        echo '<div style="float:left; margin-bottom:10px;"><a href="' . $photoLink . '"><img src="' . $url . '" width="115" height="50" border="0" hspace="10" class="border_dark" /></a></div>';
      }
    }
    else
    {
      echo '<div class="bold italic">' . $groupData['G_NAME'] . ' does not have any photos.</div>';
    }
    ?>
    <br clear="all" />
    <!-- Feed -->
    <div style="border:1px solid lightgray; width:410px;">
      <?php
        $params = array('GROUP_ID' => $group_id, 'OFFSET' => 0, 'LIMIT' => 10);
        $rs = $g->feed($params);
        
        if(empty($rs))
        {
          echo '<div style="padding-top:5px; padding-bottom:5px; padding-left:5px;" class="italic">There are no feeds for this group</div>';
        }
        else 
        {
          echo '<div style="margin: 5px 0px 5px 5px;">';
          echo '<div style="padding-bottom:10px;" class="f_10 bold">Group Feed</div>';

            
          foreach($rs as $k => $v)
          { 
            switch($v['GF_TYPE'])
            {
              case 'Photo_add':
                $userData = $us->find($v['GF_U_ID']);
                
                echo '<div style="padding-bottom:10px;">';
                echo '<div style="float:left; padding-right:5px;"><img src="images/icons/add_alt_2_16x16.png" class="png" border="0" width="16" height="16" /></div>';
                echo '<div><a href="http://' . FF_SERVER_NAME . '/users/' . $userData['U_USERNAME'] . '">' . $userData['U_USERNAME'] . '</a> added ' . $v['GF_CNT'] . ' <a href="/?action=group.photo&group_id=' . $group_id . '&id=' . $v['GF_TYPE_ID'] . '">photo' . (($v['GF_CNT'] > 1) ? 's' : '') . '</a>. <span class="f_7 italic">' . date('M d, Y g:i a', $v['GF_DATE_CREATED']) . '</span></div>';
                echo '</div>';
                break;
                
              case 'Slideshow_add':
                $userData = $us->find($v['GF_U_ID']);
                $slideshowData = $fl->search(array('FLIX_ID' => $v['GF_TYPE_ID']));
                
                echo '<div style="padding-bottom:10px;">';
                echo '<div style="float:left; padding-right:5px;"><img src="images/icons/add_alt_2_16x16.png" class="png" border="0" width="16" height="16" /></div>';
                echo '<div><a href="http://' . FF_SERVER_NAME . '/users/' . $userData['U_USERNAME'] . '">' . $userData['U_USERNAME'] . '</a> added ' . $v['GF_CNT'] . ' <a href="/slideshow?' . $slideshowData['US_KEY'] . '">slideshow' . (($v['GF_CNT'] > 1) ? 's' : '') . '</a>. <span class="f_7 italic">' . date('M d, Y g:i a', $v['GF_DATE_CREATED']) . '</span></div>';
                echo '</div>';
                break;
                
              case 'Group_join':
                $userData = $us->find($v['GF_U_ID']);
                
                echo '<div style="padding-bottom:10px;">';
                echo '<div style="float:left; padding-right:5px;"><img src="images/icons/add_alt_2_16x16.png" class="png" border="0" width="16" height="16" /></div>';
                echo '<div><a href="http://' . FF_SERVER_NAME . '/users/' . $userData['U_USERNAME'] . '">' . $userData['U_USERNAME'] . '</a> joined the group. <span class="f_7 italic">' . date('M d, Y g:i a', $v['GF_DATE_CREATED']) . '</span></div>';
                echo '</div>';
                break;
                
              case 'Forum_post':
                $userData = $us->find($v['GF_U_ID']);
                
                echo '<div style="padding-bottom:10px;">';
                echo '<div style="float:left; padding-right:5px;"><img src="images/icons/add_alt_2_16x16.png" class="png" border="0" width="16" height="16" /></div>';
                echo '<div><a href="http://' . FF_SERVER_NAME . '/users/' . $userData['U_USERNAME'] . '">' . $userData['U_USERNAME'] . '</a> posted <a href="/?action=group.board_post&group_id=' . $v['GF_G_ID'] . '&post_id=' . $v['GF_TYPE_ID'] . '">' . $v['GF_CNT'] . '</a> topic' . (($v['GF_CNT'] > 1) ? 's' : '') . ' to the forum. <span class="f_7 italic">' . date('M d, Y g:i a', $v['GF_DATE_CREATED']) . '</span></div>';
                echo '</div>';
                break;
            }
          }
          echo '</div>';
        }
      ?>
    </div>
  
  </div>
  <div style="float:left; width:245px; margin-left:10px;">
    <!-- recent slideshows -->
    <div class="gradient_lt_grey bold f_black">
      <div style="padding:5px 0px 0px 5px;"><img src="images/icons/images_16x16.png" class="png" width="16" height="16" hspace="3" align="absmiddle" />Recent Slideshows</div>
    </div>
    <div class="border_medium">
      <div style="margin:15px 0px 5px 10px;">
        <div style="width:232px; margin:auto;">
          <?php
          if(count($slideshowData) == 0)
          {
            echo '<div class="bold">No Slideshows</div>';
          }
          elseif(count($slideshowData) == 1)
          {
            $slideshowLinks[0] = '/slideshow?' . $slideshowData[0]['US_KEY'];
            echo '<div style="float:left;"><div class="flix_border"><a href="' . $slideshowLinks[0] . '"><img src="' . PATH_FOTO . $slideshowData[0]['US_PHOTO']['thumbnailPath_str'] . '" border="0" /></a></div></div>';
          }
          elseif(count($slideshowData) == 2)
          {
            $slideshowLinks[0] = '/slideshow?' . $slideshowData[0]['US_KEY'];
            $slideshowLinks[1] = '/slideshow?' . $slideshowData[1]['US_KEY'];
            echo '<div style="float:left;"><div class="flix_border"><a href="' . $slideshowLinks[0] . '"><img src="' . PATH_FOTO . $slideshowData[0]['US_PHOTO']['thumbnailPath_str'] . '" border="0" /></a></div></div>';
            echo '<div style="float:left;"><div class="flix_border"><a href="' . $slideshowLinks[1] . '"><img src="' . PATH_FOTO . $slideshowData[1]['US_PHOTO']['thumbnailPath_str'] . '" border="0" /></a></div></div>';
          }
          ?>
        </div>
        <br clear="all" />
      </div>
    </div>
    
    <!-- forum posts -->
    <div class="gradient_lt_grey bold f_black">
      <div style="padding:5px 0px 0px 5px;">Forum Posts</div>
    </div>
    <div class="border_medium">
      <div style="margin:0px 0px 15px 0px;">
    <?php
      if(count($postData) == 0)
      {
        echo '<div style="margin:5px 0px 0px 15px;" class="bold">No Posts</div>';
      }
      else 
      {
        foreach($postData as $k => $v)
        {
          $userData = $us->find($v['BP_UID']);
          
          echo '<a href="/?action=group.board_post&group_id=' . $group_id . '&post_id=' . $v['BP_ID'] . '"><div class="line_lite" style="padding:5px 0px 5px 0px;">
                  <div style="padding-left:3px;">
                    <div class="bold">' . $v['BP_TITLE'] . '</div>
                    <div>Replies: ' . $v['BP_REPLIES'] . '</div>
                    <div>Last post by ' . $userData['U_USERNAME'] . '</div>
                  </div>
                </div></a>';
        }
      }
    ?>
      </div>
    </div>
    
    <div class="gradient_lt_grey bold f_black">
      <div style="padding:5px 0px 0px 5px;">Member Spotlight</div>
    </div>
    <div class="border_medium">
      <div style="margin:15px 0px 15px 0px;">
        <div style="width:195px; margin:auto;">
          <?php
            foreach($memberData as $v)
            {
              $avatar = $us->pref($v['U_ID'], 'AVATAR');
              $avatarSrc = $avatar != '' ? PATH_FOTO . $avatar : 'images/avatar.jpg';
              echo '<a href="http://' . FF_SERVER_NAME . '/users/' . $v['U_USERNAME'] . '"><div style="float:left; padding:0px 5px 0px 5px;">
                      <div class="foto_border"><div class="foto_inside"><img src="' . $avatarSrc . '" width="75" height="75" border="0" /></div></div>
                      <div class="bold center" style="padding-top:3px;">' . $v['U_USERNAME'] . '</div>
                    </div></a>';
            }
          ?>
        </div>
        <br clear="all" />
      </div>
    </div>
  </div>
  <div style="float:left;">
    <?php include PATH_DOCROOT . '/group_sponsors.dsp.php'; ?>
  </div>
  <br clear="all" />
</div>
