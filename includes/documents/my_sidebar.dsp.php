<?php
  include_once PATH_CLASS . '/CFlix.php';
  include_once PATH_CLASS . '/CComment.php';
  
  $c  = &CComment::getInstance();
  $fl = &CFlix::getInstance();
  
  $slideshowData = $fl->search(array('USER_ID' => $user_id, 'PERMISSION' => PERM_SLIDESHOW_PUBLIC, 'LIMIT' => 2));
  $comments= $c->commentsForUser($user_id);
  
  if($subaction == 'blog' && $_USER_ID == $user_id)
  {
    echo '<div class="entryNewLink"><a href="/users/' . $username . '/blog/add_entry"><img src="/images/icons/document_add_24x24.png" class="png" width="24" height="24" hspace="5" border="0" align="absmiddle" />Create a new blog entry</a></div>';
  }
?>
    
    
    <!-- recent slideshows -->
    <div class="border_dark bold">
      <div style="padding:5px 0px 5px 5px;"><img src="images/icons/images_16x16.png" class="png" width="16" height="16" hspace="3" align="absmiddle" />Recent Slideshows</div>
    </div>
    <div class="border_dark">
      <div style="margin:15px 0px 5px 10px;">
        <div style="width:232px; margin:auto;">
          <?php
          if(count($slideshowData) == 0)
          {
            echo '<div class="bold">No Slideshows</div>';
          }
          elseif(count($slideshowData) == 1)
          {
            echo '<div style="float:left;"><div class="flix_border"><a href="/slideshow?' . $slideshowData[0]['US_KEY'] . '"><img src="' . PATH_FOTO . $slideshowData[0]['US_PHOTO']['thumbnailPath_str'] . '" border="0" /></a></div></div>';
          }
          elseif(count($slideshowData) == 2)
          {
            echo '<div style="float:left;"><div class="flix_border"><a href="/slideshow?' . $slideshowData[0]['US_KEY'] . '"><img src="' . PATH_FOTO . $slideshowData[0]['US_PHOTO']['thumbnailPath_str'] . '" border="0" /></a></div></div>';
            echo '<div style="float:left;"><div class="flix_border"><a href="/slideshow?' . $slideshowData[1]['US_KEY'] . '"><img src="' . PATH_FOTO . $slideshowData[1]['US_PHOTO']['thumbnailPath_str'] . '" border="0" /></a></div></div>';
          }
          ?>
        </div>
        <br clear="all" />
      </div>
    </div>
    
    <br/>
    
    <!-- comments -->
    <div class="border_dark bold">
      <div style="padding:5px 0px 5px 5px;"><img src="images/icons/chat_bubble_16x16.png" class="png" width="16" height="16" hspace="3" align="absmiddle" />Recent Comments</div>
    </div>
    <div class="border_dark">
      <div style="margin:15px 0px 15px 0px;">
        <?php
        $commentCount = count($comments);
        if($commentCount == 0)
        {
            echo '<div style="padding-left:10px;" class="bold">No Comments</div>';
        }
        else 
        {
          for($i = 0; $i < $commentCount && $i < 3; $i++)
          {
            switch($comments[$i]['C_TYPE'])
            {
              case 'blog':
                $typeUrl = '/users/' . $username . '/blog/entry/' . $comments[$i]['C_ELEMENT_ID'] . '/#comment' . $comments[$i]['C_ID'];
                break;
              case 'flix':
                $typeUrl = '/?action=flix.comment_forward.act&id=' . $comments[$i]['C_ELEMENT_ID'] . '&commentId=' . $comments[$i]['C_ID'];
                break;
              case 'foto':
                $typeUrl = '/users/' . $username . '/photo/' . $comments[$i]['C_ELEMENT_ID'] . '/#comment' . $comments[$i]['C_ID'];
                break;
            }
            $avatarSrc = $comments[$i]['C_AVATAR'] != '' ? PATH_FOTO . $comments[$i]['C_AVATAR'] : 'images/avatar.jpg';
            $userString = $comments[$i]['C_BY_USERNAME'] != '' ? '<a href="/users/' . $comments[$i]['C_BY_USERNAME'] . '/">' . $comments[$i]['C_BY_USERNAME'] . '</a>' : 'anonymous';
            echo '<div style="padding-bottom:10px; padding-left:2px;">
                    <a name="comment' . $comments[$i]['C_ID'] . '"></a>
                    <div style="float:left; padding-right:5px; width:45px;"><img src="' . $avatarSrc . '" width="40" height="40" border="0" /></div>
                    <div style="float:left; width:190px;">
                      <a name="' . $comments[$i]['C_ID'] . '"></a>
                      <div style="padding-bottom:4px;">' . $userString . ' said:</div>
                      <div style="padding-bottom:4px;">' . nl2br($comments[$i]['C_COMMENT']) . '</div>
                      <div class="italic">' . date(FF_FORMAT_DATE_LONG, $comments[$i]['C_TIME']) . '</div>
                      <div>(<a href="' . $typeUrl . '">view comment</a>)</div>
                    </div>
                    <br clear="all"/>
                    <br clear="all"/>
                  </div>';
          }
        }
        ?>
      </div>
    </div>