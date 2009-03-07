<?php
  $fb =& CFotobox::getInstance();
  $fbm=& CFotoboxManage::getInstance();
  $c  =& CComment::getInstance();
  
  $settingsHtml = '';
  $filterTags = array();
  $tagUrl = '';
  if(isset($tags))
  {
    $tagUrl = 'tags-' . $tags . '/';
    $filterTags = (array)explode(',', $tags);
  }

  $foto_id = $options[0];
  $f_data = $fb->fotoData($foto_id, $user_id);
  
  $viewable = permission($f_data['P_PRIVACY'], 1);
  
  if($viewable === true)
  {
    if($_USER_ID != $user_id)
    {
      $fbm->viewed($f_data['P_KEY'], $user_id);
    }
    
    $offset = $_FF_SESSION->value($username . '-offset');
    
    
    if(isset($_GET['offset']))
    {
      $_FF_SESSION->register($username . '-offset', intval($_GET['offset']));
    }
    else
    if($options[1] == 'previous' && $offset !== false && $_FF_SESSION->value($username . '-last-id') != $foto_id)
    {
      $_FF_SESSION->register($username . '-offset', ($_FF_SESSION->value($username . '-offset')-1));
      $_FF_SESSION->register($username . '-last-id', $foto_id);
    }
    else
    if($options[1] == 'next' && $offset !== false && $_FF_SESSION->value($username . '-last-id') != $foto_id)
    {
      $_FF_SESSION->register($username . '-offset', ($_FF_SESSION->value($username . '-offset')+1));
      $_FF_SESSION->register($username . '-last-id', $foto_id);
    }
    
    $offset = $_FF_SESSION->value($username . '-offset');
    
    if($offset !== false)
    {
      if($offset == 0)
      {
        $previous = false;
        $next     = 1;
        $limit    = 2;
      }
      else
      {
        $offset   --;
        $previous = 0;
        $next     = 2;
        $limit    = 3;
      }
      
      $nextPrevious = $fb->fotosSearch(array('USER_ID' => $user_id, 'TAGS' => $filterTags, 'ORDER' => 'P_TAKEN_BY_DAY', 'PERMISSION' => PERM_PHOTO_PUBLIC, 'OFFSET' => $offset, 'LIMIT' => $limit));
    }
    else
    {
      $before = $fb->fotosSearch(array('USER_ID' => $user_id, 'BEFORE' => $f_data['P_TAKEN'], 'TAGS' => $filterTags, 'ORDER' => 'P_TAKEN', 'PERMISSION' => PERM_PHOTO_PUBLIC, 'LIMIT' => 1));
      if(count($before) > 0)
      {
        $nextPrevious[0] = $before[0];
        $previous = 0;
      }
      else
      {
        $previous = false;
      }
      
      $after  = $fb->fotosSearch(array('USER_ID' => $user_id, 'AFTER' => $f_data['P_TAKEN'], 'TAGS' => $filterTags, 'ORDER' => 'P_TAKEN_ASC', 'PERMISSION' => PERM_PHOTO_PUBLIC, 'LIMIT' => 1));
      if(count($after) > 0)
      {
        $nextPrevious[2] = $after[0];
        $next = 2;
      }
      else
      {
        $next = false;
      }
    }
    
    $c_data   = $c->comments($foto_id, 'foto');
    $dynPhoto = dynamicImageLock($f_data['P_THUMB_PATH'], $f_data['P_KEY'], $f_data['P_ROTATION'], $f_data['P_WIDTH'], $f_data['P_HEIGHT'], 600, 450);
?>
    <div>
      <div style="float:left; width:600px; height:450px;">
        <?php
          //echo '<div style="cursor:pointer; width:16px; height:16px;" onclick="photoToolbar.toggle();"><img src="/images/icons/down_16x16.png" class="png" border="0" width="16" height="16" /></div>';
          //echo '<div id="photoMainControls" style="height:25px;">';
          //echo '</div>';
          
          echo '<a name="photoAnchor"></a><div style="width:' . $dynPhoto[1] . 'px; height:' . $dynPhoto[2] . 'px; margin:auto;" class="border_dark"><img src="' . $dynPhoto[0] . '" id="photoMain" alt="' . htmlentities($f_data['P_NAME']) . '" ' . $dynPhoto[3] . ' border="0" class="my_foto_border" /></div>';
        ?>
      </div>
      <div style="width:200px; float:left; margin-left:15px;">
      <?php
        /*echo '<div style="cursor:pointer; width:16px; height:16px;" onclick="photoToolbar.toggle();"><img src="/images/icons/down_16x16.png" class="png" border="0" width="16" height="16" /></div>';
        echo '<div id="photoMainControls" style="height:25px;">';
        echo '</div>';*/
      ?>
        <?php
          if(count($nextPrevious) > 0)
          {
            echo '<div style="width:180px; padding:10px; margin:auto;">';
            if($previous !== false)
            {
              $pPhoto = $nextPrevious[$previous];
              $pUrl   = '/users/' . $username . '/photo/' . $pPhoto['P_ID']  . '/previous/';
              if($tags != '')
              {
                $pUrl .= 'tags-' . $tags . '/';
              }
              if($quickset != '')
              {
                $pUrl .= 'quickset-' . $quicksetId . '-' . $quicksetName . '/';
              }
              
              $pUrl .= '#photoAnchor';
              echo '<div style="margin-right:3px; float:left;" align="center">
                      <div><div class="foto_border"><div class="foto_inside"><a href="' . $pUrl . '"><img src="' . PATH_FOTO . $pPhoto['P_THUMB_PATH'] . '" width="75" height="75" border="0" /></a></div></div></div>
                      <div style=" margin-top:3px;"><a href="' . $pUrl . '" class="plain"><img src="images/icons/previous_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" />Prev</a></div>
                    </div>';
            }
            else
            {
              echo '<div style="height:85px; width:90px; float:left;"></div>';
            }
            
            if(isset($nextPrevious[$next]) && $next !== false)
            {
              $nPhoto = $nextPrevious[$next];
              $nUrl   = '/users/' . $username . '/photo/' . $nPhoto['P_ID']  . '/next/';
              if($tags != '')
              {
                $nUrl .= 'tags-' . $tags . '/';
              }
              if($quickset != '')
              {
                $nUrl .= 'quickset-' . $quicksetId . '-' . $quicksetName . '/';
              }
              
              $nUrl .= '#photoAnchor';
              echo '<div style="margin-left:3px; float:left;" align="center">
                      <div><div class="foto_border"><div class="foto_inside"><a href="' . $nUrl . '"><img src="' . PATH_FOTO . $nPhoto['P_THUMB_PATH'] . '" width="75" height="75" border="0" /></a></div></div></div>
                      <div style=" margin-top:3px;"><a href="' . $nUrl . '" class="plain"><img src="images/icons/next_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" />next</a></div>
                    </div>';
            }
            else
            {
              echo '<div style="height:85px; width:90px; float:left;"></div>';
            }
            
            echo '<br clear="all" /></div>';
            
            if(permission($f_data['P_PRIVACY'], PERM_PHOTO_DOWNLOAD))
            {
              echo '<div id="photoPageDownload" class="bold" style="margin-bottom:10px;"><a href="/download?key=' . $f_data['P_KEY'] . '" class="plain"><img src="images/icons/log_in_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="middle" /> Download original</a></div>';
              if($options[1] == 'downloadable')
              {
                echo '<script type="text/javascript"> photoPageDownloadEffect = new fx.Opacity("photoPageDownload", {duration:1500}); photoPageDownloadEffect.hide(); photoPageDownloadEffect.toggle(); </script>';
              }
              $settingsHtml .= '<div style="padding-bottom:10px;"><a href="javascript:void(0);" onclick="photoPagePermissionSet(' . $options[0] . ', \'download\', 0);" class="plain" title="Do not let others download the original version"><img src="images/icons/private_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" style="margin-right:4px;" />Do not let others download the original version</a></div>';
            }
            else
            {
              $settingsHtml .= '<div style="padding-bottom:6px;"><a href="javascript:void(0);" onclick="photoPagePermissionSet(' . $options[0] . ', \'download\', 1);" class="plain" title="Allow others to download the original version"><img src="images/icons/log_in_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" style="margin-right:4px;" />Allow others to download the original version</a></div>';
            }
            
            if(permission($f_data['P_PRIVACY'], PERM_PHOTO_PRINT))
            {
              echo '<div id="photoPagePrint" class="bold" style="margin-bottom:10px;"><a href="javascript:void(0);" onclick="addPhotoToCart(' . $f_data['P_ID'] . ');" class="plain"><img src="images/icons/shopping_chart_alt_2_24.png" class="png" width="24" height="24" border="0" hspace="3" align="absmiddle" /> Print this photo</a></div>
                    <div class="bg_white" style="margin:10px;" id="cartDiv">
                      <div style="padding:5px;" id="cartDivContents">
                      </div>
                    </div>
                    <script type="text/javascript">
                      var effectCart = new fx.Height("cartDiv");
                      effectCart.hide();
                    </script>';
            }
          }
          else
          {
            echo '<div class="bold" style="margin-bottom:10px;">
                    <a href="/users/' . $username . '/photos/" class="plain"><img src="images/icons/view_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="absmiddle" /> View more photos</a>
                  </div>';
          }
        ?>
        <div id="photoPageDownload" class="bold" style="margin-bottom:10px;"><a href="/users/<?php echo $username; ?>/photo-large/<?php echo $foto_id; ?>/" class="plain"><img src="images/icons/zoom_in_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="middle" /> View larger version</a></div>
        <div id="photoPageFlag" class="bold" style="margin-bottom:10px;"><a href="javascript:void(o);" onclick="flagFoto('<?php echo $f_data['P_ID']; ?>', '<?php echo $user_id; ?>', '<?php echo $_FF_SESSION->value('sess_hash'); ?>');" class="plain"><img id="flaggedIcon" src="images/icons/event_yellow_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="absmiddle" /> <span id="flaggedText"><script type="text/javascript"> writeString("Flag", " as", " inappropriate"); </script></span></a></div>
        <div class="line_lite"></div>
        <div style="margin-top:10px; margin-bottom:5px;" class="bold">Photo information</div>
        <?php
          if($f_data['P_NAME'] != '')
          {
            echo '<div style="margin-top:5px;">' . $f_data['P_NAME'] . '</div>';
          }
          
          if($f_data['P_DESC'] != '')
          {
            echo '<div style="margin-top:5px;">' . $f_data['P_DESC'] . '</div>';
          }
          
          
          $fotoTags = (array)explode(',', $f_data['P_TAGS']);
          $has_tags = false;
          if(count($fotoTags) > 0)
          {
            foreach($fotoTags as $v)
            {
              if($v != '')
              {
                $tmp .= '<a href="/users/' . $username . '/photos/tags-' . $v . '/" title="View ' . $username . '\'s photos tagged with ' . $v . '" class="bullet">' . $v . '</a><br/>';
                if($has_tags === false)
                {
                  $has_tags = true;
                }
              }
            }

            if($has_tags === true)
            {
              echo '<div class="bold" style="margin-top:10px;">Tags</div><div>' . substr($tmp, 0, -2) . '</div>';
            }
          }
          
          echo '<div class="bold" style="margin-top:10px;">Extras</div>';
          if($f_data['P_TAKEN'] > 0)
          {
            echo '<div class="bullet">Taken on: ' . date(FF_FORMAT_DATE_LONG, $f_data['P_TAKEN']) . '</div>';
          }
          if($f_data['P_CAMERA_MAKE'] != '')
          {
            echo '<div class="bullet" style="text-transform:capitalize;">Make: ' . strtolower($f_data['P_CAMERA_MAKE']) . '</div>';
          }
          
          if($f_data['P_CAMERA_MODEL'] != '')
          {
            echo '<div class="bullet" style="text-transform:capitalize;">Model: ' . strtolower($f_data['P_CAMERA_MODEL']) . '</div>';
          }
          ?>
        </div>
      </div>
      <br clear="all" />
      
      <div style="padding-top:20px;">
        <div style="float:left; width:350px;">
          <?php
            $i = 0;
            foreach($c_data as $v)
            {
              $avatarSrc = $v['C_AVATAR'] != '' ? PATH_FOTO . $v['C_AVATAR'] : '/images/avatar.jpg';
              $userString = $v['C_USERNAME'] != '' ? '<a href="/users/' . $v['C_USERNAME'] . '/">' . $v['C_USERNAME'] . '</a>' : 'anonymous';
              echo '<div id="_comment_' . $v['C_ID'] . '">
                      <div style="padding-bottom:10px;">
                        <a name="comment' . $v['C_ID'] . '"></a>
                        <div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" width="40" height="40" border="0" class="border_dark" /></div>
                        <div style="float:left; width:170px;">
                          <a name="' . $v['C_ID'] . '"></a>
                          <div style="padding-bottom:4px;">' . $userString . ' said:</div>
                          <div style="padding-bottom:4px;">' . nl2br($v['C_COMMENT']) . '</div>
                          <div class="italic">' . date(FF_FORMAT_DATE_LONG, $v['C_TIME']) . '</div>
                        </div>';
              
              if($user_id == $_USER_ID)
              {
                  echo '<div style="float:left;"><a href="javascript:void(0);" onclick="deleteComment(' . $v['C_ID'] . ');" title="delete comment"><img src="images/icons/close_16x16.png" border="0" widht="16" height="16" /></a></div>';
              }
              
                  echo '<br clear="all"/>
                        <div style="padding-top:10px;" class="my_line_lite"></div>
                      </div>
                    </div>';
              $i++;
            }

            if($i == 0)
            {
              echo '<div class="italic bold">No comments for this photo.</div>';
            }
          ?>
        </div>
        <div style="padding-left:10px; float:left;">
          <?php
            if(permission($f_data['P_PRIVACY'], PERM_PHOTO_COMMENT))
            {
              echo '<a name="fotoComment"></a>';
              if($logged_in === true)
              {
            ?>
                <div id="_commentForm">
                  <form method="post" id="fotoCommentForm" action="/?action=fotobox.comment.act" style="display:inline;">
                    <input type="hidden" name="c_element_id" value="<?php echo $foto_id; ?>" />
                    <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                    <input type="hidden" name="c_type" value="foto"/>
                    <input type="hidden" name="c_for_u_id" value="<?php echo $f_data['P_U_ID']; ?>" />
                    <div><textarea name="c_comment" style="width:225px; height:75px; padding-bottom:10px;" class="formfield"></textarea></div>
                    <div style="padding-top:3px;">
                      <div style="padding-right:3px; float:left;"><a href="javascript:document.getElementById('fotoCommentForm').submit();" style="text-decoration:none;" title="leave a comment"><img src="images/comment.gif" width="16" height="16" border="0" alt="leave comment" /></a></div>
                      <div style="padding-top:1px;"><a href="javascript:document.getElementById('fotoCommentForm').submit();" style="text-decoration:none;" title="leave a comment">Leave comment</a></div>
                    </div>
                  </form>
                </div>
            <?php
              }
              else
              {
            ?>
                <div id="_commentLogIn" style="display:block;">
                  <div class="bold" style="width:100%;" class="my_line_lite">Log in to comment</div>
                  <div style="padding-top:5px;">
                    <form id="fotoCommentLogin" action="/?action=member.login_form.act" method="post" style="display:inline;">
                      <div>Username</div>
                      <div style="padding-bottom:5px;"><input type="text" name="u_username" value="" class="formfield" style="width:90px;" /></div>
                      <div>Password</div>
                      <div style="padding-bottom:3px;"><input type="password" name="u_password" value="" class="formfield" style="width:90px;" /></div>
                      <div style="padding-top:3px;">
                        <div style="padding-right:3px; float:left;"><a href="javascript:document.getElementById('fotoCommentLogin').submit();" style="text-decoration:none;" title="log in to leave a comment"><img src="images/login2.gif" width="16" height="16" border="0" alt="leave comment" /></a></div>
                        <div style="padding-top:1px;"><a href="javascript:document.getElementById('fotoCommentLogin').submit();" style="text-decoration:none;" title="log in to leave a comment">Log in to comment</a></div>
                      </div>
                      <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>#fotoComment" />
                    </form>
                  </div>
                  <div>
                    <br/>
                    -- OR --
                    <br/>
                    <div style="padding-top:10px;"><a href="javascript:commentAnon();" style="text-decoration:none;">Comment anonymously</a></div>
                  </div>
                </div>
                <div id="_commentForm" style="display:none;">
                  <form method="post" id="fotoCommentForm" action="" style="display:inline;">
                    <input type="hidden" name="c_element_id" value="<?php echo $foto_id; ?>" />
                    <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                    <input type="hidden" name="c_type" value="foto"/>
                    <input type="hidden" name="c_for_u_id" value="<?php echo $f_data['P_U_ID']; ?>" />
                    <div><textarea name="c_comment" style="width:225px; height:75px; padding-bottom:10px;" class="formfield"></textarea></div>
                    <div style="padding-top:3px;">
                      <div style="padding-right:3px; float:left;"><a href="javascript:document.getElementById('fotoCommentForm').submit();" style="text-decoration:none;" title="leave a comment"><img src="images/comment.gif" width="16" height="16" border="0" alt="leave comment" /></a></div>
                      <div style="padding-top:1px;"><a href="javascript:document.getElementById('fotoCommentForm').submit();" style="text-decoration:none;" title="leave a comment">Leave comment</a></div>
                    </div>
                  </form>
                </div>
          <?php
              }
              
              $settingsHtml .= '<div style="padding-bottom:10px;"><a href="javascript:void(0);" onclick="photoPagePermissionSet(' . $options[0] . ', \'comment\', 0);" class="plain" title="Do not let others leave comments"><img src="images/icons/private_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" style="margin-right:4px;" />Do not let others leave comments</a></div>';
            }
            else
            {
              $settingsHtml .= '<div style="padding-bottom:6px;"><a href="javascript:void(0);" onclick="photoPagePermissionSet(' . $options[0] . ', \'comment\', 1);" class="plain" title="Allow others to leave comments"><img src="images/icons/chat_bubble_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" style="margin-right:4px;" />Allow others to leave comments</a></div>';
            }
          ?>
        </div>
      </div>
    </div>
    <br clear="all" />
<?php
    if($user_id == $_USER_ID) // if user is logged in on own page then set the photo setting div
    {
      $settingsHtml .= '<a id="removeLink" href="javascript:void(0);" onclick="removePhotoFromPage(' . $options[0] . ');" class="plain"><img src="images/icons/remove_alt_2_16x16.png" class="png" border="0" width="16" height="16" align="absmiddle" /> Remove this photo from My Page</a>';
      
      $settingsHtml = '<div style="padding:10px;" class="border_lite">' . $settingsHtml . '</div>';
      echo '<script type="text/javascript"> $("photoSettingDiv").innerHTML = \'' . str_replace("'", "\\'", $settingsHtml) . '\'; </script>';
  
      //echo '<script type="text/javascript"> $("photoMainControls").innerHTML = \'' . str_replace("'", "\\'", $settingsHtml) . '\'; </script>';
    }
  }
  else // photo is no longer on personal page
  {
    echo '<div class="bold" align="center" style="width:545px;">
            <div style="padding-bottom;10px;">The photo you are trying to view is no longer on ' . $displayName . '\'s personal page.</div>
          </div>';
  }
?>

<!--<script type="text/javascript">
  var photoToolbar = new fx.Height('photoMainControls');
  photoToolbar.hide();
</script>-->
<script>
  function commentAnon()
  {
    $('_commentLogIn').style.display = 'none';
    $('_commentForm').style.display = 'block';
    $('fotoCommentForm').action = '/?action=fotobox.comment.act';
  }
  
  function toggleComment()
  {
    $('_commentLink').style.display = 'none';
    $('_commentForm').style.display = 'block';
  }
</script>
