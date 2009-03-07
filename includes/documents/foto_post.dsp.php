<?php
  $fb =& CFotobox::getInstance();
  $u  =& CUser::getInstance();
  
  $supportedBlogs = array('Blogger','LiveJournal','MovableType','TypePad','WordPress');
  $blogs = $u->blogs($_USER_ID);
  
  $user_data= $u->find($_USER_ID);
  $fotos = array();
  if(isset($_GET['foto_id']))
  {
    $fotos = $fb->fotoData($_GET['foto_id'], $_USER_ID);
    $img_path = PATH_FOTO . $foto_data['P_FLIX_PATH'];
    $singleFoto = true;
    $foto_ids = $_GET['foto_id'];
  }
  else
  if(isset($_GET['foto_ids']))
  {
    $foto_ids = explode(',', $_GET['foto_ids']);
    $ids = explode(',', $foto_ids);
    $fotos = $fb->fotosByIds($foto_ids, $_USER_ID);
    $singleFoto = false;
  }
  
  $blog_code = '';
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'error':
        $msg = 'An unknown error occured while trying to publish to your blog.<br/>Please make sure that all your information is correct.';
        break;
    }
    
    if(isset($msg))
    {
      echo '<div class="confirm">' . $msg . '</div>';
    }
  }
?>

<div style="width:545px;" align="left">
  <div class="bold" style="padding-top:5px; padding-bottom:5px;" align="center"><span style="padding-right:10px; margin-top:2px;"><img src="images/beta_small.gif" width="42" height="10" border="0" /></span><span>Post this foto directly to your blog.<a href="/contactus/?beta_blogging=1" style="padding-left:10px;">Report problems.</a></span></div>
    <div style="width:545;" class="line_lite">
      <div style="padding-top:10px;" align="center">
        <div>
          <?php
            if($singleFoto === false)
            {
              foreach($fotos as $k => $v)
              {
                $blog_code = $ff_code_embed .= htmlentities('<div style="padding-top:5px;"><a href="http://' . FF_SERVER_NAME . '/users/' . $user_data['U_USERNAME'] . '/foto/' . $v['P_ID'] . '/" target="_blank"><img src="http://' . FF_SERVER_NAME . '/foto?key=' . $v['P_KEY'] . '&size=400x300" border="0" /></a></div>' . "\n");
                echo '<img src="' . PATH_FOTO . $v['P_THUMB_PATH'] . '" width="75" height="75" hspace="5" vspace="5" border="0" />';
                if($k % 4 == 0){ echo '<br/>'; }
              }
            }
            else
            {
              $blog_code = $ff_code_embed = htmlentities('<div style="padding-top:10px;"><a href="http://' . FF_SERVER_NAME . '/users/' . $user_data['U_USERNAME'] . '/foto/' . $fotos['P_ID'] . '/" target="_blank"><img src="http://' . FF_SERVER_NAME . '/foto?key=' . $fotos['P_KEY'] . '&size=400x300" border="0" /></a></div>');
              echo '<img src="' . PATH_FOTO . $fotos['P_FLIX_PATH'] . '" border="0" />';
            }
          ?>
        </div>
        <div style="padding-top:5px;">
          <img src="images/offsite_logo_blogger.gif" width="68" height="18" hspace="5" align="absmiddle" border="0" />
          <img src="images/offsite_logo_livejournal.gif" width="69" height="15" hspace="5" align="absmiddle" border="0" />
          <img src="images/offsite_logo_movabletype.gif" width="91" height="18" hspace="5" align="absmiddle" border="0" />
          <img src="images/offsite_logo_typepad.gif" width="70" height="18" hspace="5" align="absmiddle" border="0" />
          <img src="images/offsite_logo_wordpress.gif" width="83" height="15" hspace="5" align="absmiddle" border="0" />
        </div>
      </div>
      <div align="center">
        <form name="_blogContent" style="display:inline;">
          <div style="width:255px;" align="left">
            <div>Title:</div>
            <div style="padding-bottom:4px;"><input type="text" name="title" class="formfield" style="width:250px;" /></div>
            <div>Post:</div>
            <div style="padding-bottom:5px;"><textarea name="post" class="formfield" style="width:250px; height:65px;"></textarea></div>
          </div>
        </form>
      </div>
    </div>
    <div style="padding-bottom:5px;"></div>
    <?php
      $blogCnt = count($blogs);
      echo '<div id="_blog_exists" style="display:' . ($blogCnt > 0 ? 'block' : 'none') . ';">';
      if($blogCnt > 0)
      {
        echo '<form name="_blogExisting" action="/?action=fotobox.foto_blog_api.act" method="post" style="display:inline; padding-bottom:5px;">
              <input type="hidden" name="title" value=""/>
              <input type="hidden" name="post" value=""/>
              <input type="hidden" name="blog_code" value="' . $blog_code . '"/>
              <input type="hidden" name="foto_ids" value="' . $_GET['foto_ids'] . '" />
              <div><span class="bold" style="padding-right:5px;">Select which blog(s) to publish to.</span>(<a href="javascript:_toggle(\'_blog_new\'); _toggle(\'_blog_exists\');">Specify different blog</a>)</div>';
        foreach($blogs as $k => $v)
        {
          if(in_array($v['B_TYPE'], $supportedBlogs))
          {
            $class = $k % 2 == 1 ? 'bg_white' : 'bg_lite';
            echo '<div style="padding-top:5px; padding-bottom:5px;" class="line_lite">
                    <div style="float:left; margin-top:-3px;"><input type="checkbox" name="ub_id[]" value="' . $v['B_ID'] . '" /></div>
                    <div style="float:left;">' . $v['B_URL'] . '</div>
                    <br/>
                  </div>';
          }
        }
        echo '    <div style="padding-top:10px;">
                    <div style="margin-top:-3px; float:left;"><input type="checkbox" value="1" name="make_fotos_public"/></div>
                    <div style="float:left;">Make these fotos public</div>
                    <br clear="all" />
                  </div>
                </form>
                <div style="clear:left;"><a href="javascript:postToExisting();"><img src="images/buttons/blog_foto.gif" width="79" height="23" vspace="4" border="0" /></a></div>
                <div>*This feature is currently in Beta</div>';
      }
      
      echo '</div>';
    ?>
    <div id="_blog_new" style="display:<?php echo ($blogCnt == 0 ? 'block' : 'none'); ?>;">
      <form name="_blogNew" action="/?action=fotobox.foto_blog_api.act" method="post" style="display:inline;">
      <input type="hidden" name="title" value=""/>
      <input type="hidden" name="post" value=""/>
      <input type="hidden" name="blog_code" value="<?php echo $blog_code; ?>"/>
      <input type="hidden" name="up_id" value="<?php echo $foto_data['P_ID']; ?>" />
      <div><span class="bold" style="padding-right:5px;">Enter your blog information below.</span><?php if($blogCnt > 0){ echo '(<a href="javascript:_toggle(\'_blog_new\'); _toggle(\'_blog_exists\');">Choose a saved blog</a>)'; } ?></div>
      <div style="padding-bottom:3px;">
        <div>Service:</div>
        <div>
          <select name="ub_type" class="formfield" onChange="adjustForm(this.value);">
            <?php
              foreach($supportedBlogs as $v)
              {
                echo '<option value="' . $v . '">' . $v . '</option>';
              }
            ?>
          </select>
        </div>
      </div>
      <div style="padding-bottom:3px;">
        <div>Username:</div>
        <div><input type="text" name="ub_username" class="formfield" style="width:100px;" /></div>
      </div>
      <div style="padding-bottom:3px;">
        <div>Password:</div>
        <div><input type="password" name="ub_password" class="formfield" style="width:100px;" /></div>
      </div>
      <div style="padding-bottom:3px; display:none;" id="_form_endPoint">
        <div>Path to endpoint:</div>
        <div><input type="text" name="ub_endPoint" class="formfield" style="width:150px;" /></div>
        <div>For example: <span id="endPointUrl"></span></div>
      </div>
      <div style="padding-bottom:3px;">
        <div id="blogIdText"><input type="hidden" name="ub_blogId" /></div>
      </div>
      <div style="padding-bottom:3px;">
        <div>Blog Url:</div>
        <div><input type="text" name="ub_url" class="formfield" style="width:150px;" value="http://" /></div>
      </div>
      <div style="padding-top:10px;">
        <div style="margin-top:-3px; float:left;"><input type="checkbox" value="1" name="make_fotos_public"/></div>
        <div style="float:left;">Make these fotos public</div>
        <br clear="left"/>
      </div>
      <div style="padding-top:3px;">
        <div style="float:left; margin-top:-3px; padding-right:3px;"><input type="checkbox" name="save_blog" value="1" /></div>
        <div style="float:left;">Remember this blog</div>
        <br clear="left"/>
      </div>
      <div style="padding-bottom:3px;"><a href="javascript:blogIdent(document.forms['_blogNew'].elements['ub_type'].options[document.forms['_blogNew'].elements['ub_type'].selectedIndex].value, document.forms['_blogNew'].elements['ub_username'].value, document.forms['_blogNew'].elements['ub_password'].value);"><img src="images/buttons/blog_foto.gif" width="79" height="23" vspace="4" border="0" /></a></div>
      <div>*This feature is currently in Beta</div>
      </form>
    </div>
  </form>
  <div style="padding-top:15px;"></div>
  <div class="line_dark"></div>
  <div style="padding-top:15px;"></div>
  <div class="bold">Your blog not listed above?  Not a problem, just follow the instructions below.</div>
  <br /><br />
  <table border="0" cellpadding="0" cellspacing="0" width="545">
    <tr>
      <td valign="top">
        <div class="border_dark bg_lite">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td width="45">&nbsp;</td>
              <td class="f_dark_accent bold" align="left" colspan="2">Embed this foto into your page!</td>
            </tr>
            <tr>
              <td align="center" valign="middle"><img src="images/blog_embed.gif" width="30" height="39" border="0" /></td>
              <td valign="middle" class="f_8 f_red bold" nowrap>Copy &amp; paste</td>
              <td valign="middle" align="right"><img src="images/offsite_logo_myspace.gif" width="68" height="17" hspace="5" align="absmiddle" border="0" /><img src="images/offsite_logo_friendster.gif" width="70" height="14" hspace="5" align="absmiddle" border="0" /></td>
            </tr>
            <tr>
              <td colspan="3" align="center">
                <div style="padding-top:20px;"></div>
                <textarea name="ff_embed_blogger" wrap="virtual" class="formfield" style="width:500px; height:95px;"><?php echo $ff_code_embed; ?></textarea>
              </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>