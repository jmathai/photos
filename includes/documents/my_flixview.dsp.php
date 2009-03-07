<?php
  if($fotoPageIni['fotopage.flix'] > 0)
  {
    if(strlen($options[0]) == 32)
    {
      $flixKey = $options[0];
      $c =& CComment::getInstance();
      $fl =& CFlix::getInstance();
      $flixData = $fl->fastflix($flixKey, $user_id);
      $sizeArr = explode('x', $flixData['A_SIZE']);
      $width = $sizeArr[0];
      $height= $sizeArr[1];
      $cData = $c->comments($flixData['A_ID'], 'flix');
      
      //   background:url(\'/images/flixview_bg.gif\') repeat-x;
      echo '<div style="padding-bottom:5px;"><a href="/users/' . $username . '/flix/">Back to ' . $displayName . '\'s Flix</a></div>
            <div style="position:absolute; margin-left:-7px; top:140px; width:750px; height:' . $height . 'px;" align="center">
              <script language="javascript" type="text/javascript" src="/js/fastflix_remote/' . $flixKey . '/"></script>
            </div>
            <div style="height:' . $height . 'px;"></div>';
?>
      <div style="padding-top:15px; margin-left:20px; width:700px;" class="my_line_lite"></div>
      <div style="padding-top:10px; padding-left:100px;">
        <div style="float:left; width:300px;">
          <?php
            $i = 0;
            foreach($cData as $v)
            {
              if(!is_file($avatarSrc = PATH_FOTO . $v['C_AVATAR']))
              {
                $avatarSrc = 'images/avatar_small.jpg';
              }
              $userString = $v['C_USERNAME'] != '' ? '<a href="/users/' . $v['C_USERNAME'] . '/">' . $v['C_USERNAME'] . '</a>' : 'anonymous';
              echo '<div style="padding-bottom:10px;">
                      <a name="comment' . $v['C_ID'] . '"></a>
                      <div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" width="38" height="38" border="0" /></div>
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
              echo '<div class="italic">No comments for this Flix.</div>';
            }
          ?>
        </div>
        <div style="padding-left:10px; float:left;">
          <a name="flixComment"></a>
          <?php
            if($logged_in === true)
            {
          ?>
              <form method="post" id="flixCommentForm" action="/?action=fotobox.comment.act" style="display:inline;">
                <input type="hidden" name="c_element_id" value="<?php echo $flixData['A_ID']; ?>" />
                <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                <input type="hidden" name="c_type" value="flix"/>
                <div><textarea name="c_comment" style="width:225px; height:75px; padding-bottom:10px;" class="formfield"></textarea></div>
                <div style="padding-top:3px;">
                  <div style="padding-right:3px; float:left;"><a href="javascript:document.getElementById('fotoCommentForm').submit();" style="text-decoration:none;" title="leave a comment"><img src="images/comment.gif" width="16" height="16" border="0" alt="leave comment" /></a></div>
                  <div style="padding-top:1px;"><a href="javascript:document.getElementById('flixCommentForm').submit();" style="text-decoration:none;" title="leave a comment">Leave comment</a></div>
                </div>
              </form>
          <?php
            }
            else 
            {
          ?>
              <div class="bold" style="width:100%;" class="my_line_lite">Log in to comment</div>
              <div style="padding-top:5px;">
                <form id="flixCommentLogin" action="/?action=member.login_form.act" method="post" style="display:inline">
                  <div>Username</div>
                  <div style="padding-bottom:5px;"><input type="text" name="u_username" value="" class="formfield" style="width:90px;" /></div>
                  <div>Password</div>
                  <div style="padding-bottom:3px;"><input type="password" name="u_password" value="" class="formfield" style="width:90px;" /></div>
                  <div style="padding-top:3px;">
                    <div style="padding-right:3px; float:left;"><a href="javascript:document.getElementById('flixCommentLogin').submit();" style="text-decoration:none;" title="log in to leave a comment"><img src="images/login2.gif" width="16" height="16" border="0" alt="leave comment" /></a></div>
                    <div style="padding-top:1px;"><a href="javascript:document.getElementById('flixCommentLogin').submit();" style="text-decoration:none;" title="log in to leave a comment">Log in to comment</a></div>
                  </div>
                  <input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>#flixComment" />
                </form>
              </div>
          <?php
            }
          ?>
        </div>
      </div>
<?php
    }
    else 
    {
      echo '<div class="bold" align="center" style="width:545px;">
            <div style="padding-bottom;10px;">Sorry, we could not find the Flix you are looking for.</div>
          </div>';
    }
  }
  else 
  {
    echo '<div class="bold" align="center" style="width:545px;">
            <div style="padding-bottom;10px;">' . $displayName . ' does not have any Flix on their FotoPage.</div>
            <div>If you\'re ' . $displayName . ' then go read our <a href="http://blog.fotoflix.com/2005/05/setting-up-your-fotopage.html">tutorial</a> to get started.</div>
          </div>';
  }
?>