<?php
  $fb =& CFotobox::getInstance();
  $fbm=& CFotoboxManage::getInstance();
  $c  =& CComment::getInstance();
  
  $group_id = $_GET['group_id'];
  $page = $_GET['page'];
  $offset = $_GET['offset'];
  $tags = $_GET['tags'];
  
  $settingsHtml = '';
  $filterTags = array();
  $tagUrl = '';
  if(isset($tags))
  {
    $filterTags = (array)explode(',', $tags);
  }

  $foto_id = $_GET['id'];
  $f_data = $fb->fotoData($foto_id);
  
  $viewable = permission($f_data['P_PRIVACY'], 1);
  
  if($viewable === true)
  {
    if($_USER_ID != $user_id)
    {
      $fbm->viewed($f_data['P_KEY'], $user_id);
    }
    
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
        $offset--;
        $previous = 0;
        $next     = 2;
        $limit    = 3;
      }
      
      $nextPrevious = $fb->fotosSearch(array('MODE' => 'GROUP', 'GROUP_ID' => $group_id, 'TAGS' => $filterTags, 'ORDER' => 'P_CREATED', 'PERMISSION' => PERM_PHOTO_PUBLIC, 'OFFSET' => $offset, 'LIMIT' => $limit));
    }
    
    $c_data   = $c->comments($foto_id, 'foto');
    $dynPhoto = dynamicImageLock($f_data['P_THUMB_PATH'], $f_data['P_KEY'], $f_data['P_ROTATION'], $f_data['P_WIDTH'], $f_data['P_HEIGHT'], 600, 450);
?>
    <div>
      <div style="float:left; width:600px; height:450px;">
        <?php
          echo '<div style="z-index:2; width:' . $dynPhoto[1] . 'px; height:' . $dynPhoto[2] . 'px; margin:auto;"><img src="' . $dynPhoto[0] . '" id="photoMain" alt="' . htmlentities($f_data['P_NAME']) . '" ' . $dynPhoto[3] . ' border="0" class="my_foto_border" /></div>';
        ?>
      </div>
      <div style="width:200px; float:left; margin-left:15px;">
        <?php
          if(count($nextPrevious) > 0)
          {
            echo '<div style="width:180px; padding:10px; margin:auto;">';
            if($previous !== false)
            {
              $pPhoto = $nextPrevious[$previous];
              $pUrl   = '?action=group.photo&group_id=' . $group_id . '&page=' . $page . '&id=' . $pPhoto['P_ID'] . '&offset=' . $offset;
              
              if(isset($tags) && $tags !== '')
              {
                $pUrl .= '&tags=' . $tags;  
              }   
              
              echo '<div style="margin-right:3px; float:left;" align="center">
                      <div><div class="foto_border"><div class="foto_inside"><a href="' . $pUrl . '"><img src="' . PATH_FOTO . $pPhoto['P_THUMB_PATH'] . '" width="75" height="75" border="0" /></a></div></div></div>
                      <div style=" margin-top:3px;"><a href="' . $pUrl . '" class="plain"><img src="images/icons/previous_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" />Prev</a></div>
                    </div>';
            }
            else
            {
              echo '<div style="height:85px; width:90px; float:left;"></div>';
            }
            
            if(isset($nextPrevious[$next]))
            {
              if($previous !== false)
              {
                $nextOffset = $offset+2;
              }
              else 
              {
                $nextOffset = $offset+1;
              }
              $nPhoto = $nextPrevious[$next];
              $nUrl   = '?action=group.photo&group_id=' . $group_id . '&page=' . $page . '&id=' . $nPhoto['P_ID'] . '&offset=' . $nextOffset;
              if(isset($tags) && $tags !== '')
              {
                $nUrl .= '&tags=' . $tags;  
              }  
              
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
          }
          else
          {
            echo '<div class="bold" style="margin-bottom:10px;">
                    <a href="/?action=group.photos&group_id=' . $group_id . '" class="plain"><img src="images/icons/view_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="absmiddle" /> View more photos</a>
                  </div>';
          }
          
          if($previous !== false)
          {
            $largeOffset = $offset+1;
          }
          else
          {
            $largeOffset = $offset;
          }
          
        if(isset($tags) && $tags !== '')
        {
        ?>
          <div id="photoPageDownload" class="bold" style="margin-bottom:10px;"><a href="/?action=group.photo_large&group_id=<?php echo $group_id; ?>&id=<?php echo $foto_id; ?>&page=<?php echo $page; ?>&offset=<?php echo $largeOffset; ?>&tags=<?php echo $tags; ?>" class="plain"><img src="images/icons/zoom_in_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="middle" /> View larger version</a></div>
        <?php
        }
        else 
        {
        ?>
          <div id="photoPageDownload" class="bold" style="margin-bottom:10px;"><a href="/?action=group.photo_large&group_id=<?php echo $group_id; ?>&id=<?php echo $foto_id; ?>&page=<?php echo $page; ?>&offset=<?php echo $largeOffset; ?>" class="plain"><img src="images/icons/zoom_in_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="middle" /> View larger version</a></div>
        <?php
        }
        ?>
        <!--<div id="photoPageFlag" class="bold" style="margin-bottom:10px;"><a href="javascript:void(o);" onclick="flagFoto('<?php echo $f_data['P_ID']; ?>', '<?php echo $user_id; ?>', '<?php echo $_FF_SESSION->value('sess_hash'); ?>');" class="plain"><img id="flaggedIcon" src="images/icons/event_yellow_24x24.png" class="png" width="24" height="24" border="0" hspace="3" align="absmiddle" /> <span id="flaggedText"><script type="text/javascript"> writeString("Flag", " as", " inappropriate"); </script></span></a></div>-->
        <div class="line_lite"></div>
        <div style="margin-top:10px; margin-bottom:5px;" class="bold">Photo information</div>
        <?php
          if($f_data['P_NAME'] == '' && $f_data['P_DESC'] == '')
          {
            echo '<div style="margin-top:5px;" class="italic">Photo information not set</div>';
          }
          else
          {
            if($f_data['P_NAME'] != '')
            {
              echo '<div style="margin-top:5px;">' . $f_data['P_NAME'] . '</div>';
            }
            
            if($f_data['P_DESC'] != '')
            {
              echo '<div style="margin-top:5px;">' . $f_data['P_DESC'] . '</div>';
            }
          }
          
          
          $fotoTags = (array)explode(',', $f_data['P_TAGS']);
          $has_tags = false;
          if(count($fotoTags) > 0)
          {
            foreach($fotoTags as $v)
            {
              if($v != '')
              {
                $tmp .= '<a href="/?action=group.photos&group_id=' . $group_id . '&tags=' . $v . '" class="bullet">' . $v . '</a><br/>';
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
          if($f_data['P_CAMERA_MODEL'] == '' && $f_data['P_CAMERA_MAKE'] == '' && $f_data['P_CAMERA_MODEL'] == '')
          {
            echo '<div class="italic">Photo extras not available</div>';
          }
          else 
          {
            if($f_data['P_CAMERA_MODEL'] != '')
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
              $avatarSrc = $v['C_AVATAR'] != '' ? PATH_FOTO . $v['C_AVATAR'] : 'images/avatar.jpg';
              $userString = $v['C_USERNAME'] != '' ? '<a href="/users/' . $v['C_USERNAME'] . '/">' . $v['C_USERNAME'] . '</a>' : 'anonymous';
              echo '<div style="padding-bottom:10px;">
                      <a name="comment' . $v['C_ID'] . '"></a>
                      <div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" width="40" height="40" border="0" class="border_dark" /></div>
                      <div style="float:left;">
                        <a name="' . $v['C_ID'] . '"></a>
                        <div style="padding-bottom:4px;">' . $userString . ' said:</div>
                        <div style="padding-bottom:4px;">' . nl2br($v['C_COMMENT']) . '</div>
                        <div class="italic">' . date(FF_FORMAT_DATE_LONG, $v['C_TIME']) . '</div>
                      </div>
                      <br clear="all"/>
                      <div style="padding-top:10px;" class="my_line_lite"></div>
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
            <?php
              }
              else
              {
            ?>
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
          <?php
              }
            }
          ?>
        </div>
      </div>
    </div>
    <br clear="all" />
<?php
  }
  else // photo is no longer on personal page
  {
    echo '<div class="bold" align="center" style="width:545px;">
            <div style="padding-bottom;10px;">The photo you are trying to view is no longer available.</div>
          </div>';
  }
?>