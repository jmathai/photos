<?php
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();
  $u  =& CUser::getInstance();
  
  $supportedBlogs = array('Blogger','MovableType','TypePad','WordPress');
  
  $us_key = $_GET['us_key'];
  
  $blogs = $u->blogs($_USER_ID);
  
  $flix_data = $fl->search(array('KEY' => $us_key, 'USER_ID' => $_USER_ID, 'RETURN_TYPE' => 'SINGLE_FOTO'));
  $foto_id    = $flix_data['US_DATA']['ID']['VALUE'];
  $foto_data  = $fb->fotoData($foto_id);
  
  $swf_src = '/swf/flix_theme/layout_small/small_' . substr($flix_data['A_TEMPLATE'], 1) . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $flix_data['A_FASTFLIX'] . '&containerWidth=' . $containerWidth . '&containerHeight=' . $containerHeight;
  $ff_code_embed = '<embed src="http://' . FF_SERVER_NAME . '/swf/' . $flix_data['A_CONTAINER'] . '?fastflix=' . $fastflix . '&xml_src=http://' . FF_SERVER_NAME . PATH_FOTO . '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '.xml&server_name=' . FF_SERVER_NAME . '&version=' . FF_VERSION_TEMPLATE . '&referrer=&destination=' . urlencode('http://' . FF_SERVER_NAME) . '" menu="false" quality="high" width="' . $containerWidth . '" height="' . $containerHeight . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="always" swLiveConnect="true"></embed>';
  $ff_code_link  = '<a href="http://' . FF_SERVER_NAME . '/fastflix?' . $us_key . '" target="_blank">View My Slideshow!</a>';
  
  /*$blog_src   = 'http://' . FF_SERVER_NAME . '/swf/' . $flix_data['A_CONTAINER'] . '?fastflix=' . $fastflix . '&server_name=' . FF_SERVER_NAME . '&xml_src=' . urlencode(PATH_FOTO . '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '.xml') . '&version=' . FF_VERSION_TEMPLATE . '&timestamp=' . NOW;*/
  $xml_orig   = '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '.xml';
  $xml_path   = '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '_' . NOW . '.xml';
  $blog_src   = 'http://' . FF_SERVER_NAME . '/swf/' . $flix_data['A_CONTAINER'] . '?fastflix=' . $fastflix . '&server_name=' . FF_SERVER_NAME . '&xml_src=' . urlencode('http://' . FF_SERVER_NAME . PATH_FOTO . $xml_path) . '&version=' . FF_VERSION_TEMPLATE . '&timestamp=' . NOW;
  $blog_code  = str_replace(array('&', '"'), array('&amp;', '&quot;'), '<embed src="' . $blog_src . '" menu="false" quality="high" bgcolor="#000000" width="' . $containerWidth . '" height="' . $containerHeight . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" swLiveConnect="true"><noembed>Sorry, your browser does not have the correct version of Flash installed.</noembed></embed>');
  
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
  <div class="bold" style="padding-top:5px; padding-bottom:5px;" align="center"><span style="padding-right:10px; margin-top:2px;"><img src="images/beta_small.gif" width="42" height="10" border="0" /></span><span>Post this Flix directly to your blog.<a href="/contactus/?beta_blogging=1" style="padding-left:10px;">Report problems.</a></span></div>
    <div style="width:545px; height:140px;" class="line_lite">
      <div style="float:left; padding-top:10px; padding-right:10px;" align="center">
        <div class="flix_border"><a href="/fastflix_popup?fastflix=<?php echo $fastflix; ?>" onclick="_open(this.href,<?php echo $containerWidth . ', ' . $containerHeight; ?>); return false;"><img src="<?php echo PATH_FOTO . $foto_data['P_THUMB_PATH']; ?>" border="0" /></a></div>
        <div style="padding-top:5px;" align="center">
          <div>
            <img src="images/offsite_logo_blogger.gif" width="68" height="18" hspace="5" align="absmiddle" border="0" />
            <img src="images/offsite_logo_wordpress.gif" width="83" height="15" hspace="5" align="absmiddle" border="0" />
          </div>
          <div style="padding-top:2px;">
            <img src="images/offsite_logo_movabletype.gif" width="91" height="18" hspace="5" align="absmiddle" border="0" />
            <img src="images/offsite_logo_typepad.gif" width="70" height="18" hspace="5" align="absmiddle" border="0" />
          </div>
        </div>
      </div>
      <div>
        <form name="_blogContent" style="display:inline;">
          <div>Title:</div>
          <div style="padding-bottom:4px;"><input type="text" name="title" class="formfield" style="width:250px;" /></div>
          <div>Post:</div>
          <div style="padding-bottom:5px;"><textarea name="post" class="formfield" style="width:250px; height:65px;"></textarea></div>
        </form>
      </div>
    </div>
    <div style="padding-bottom:5px;"></div>
    <?php
      $blogCnt = count($blogs);
      echo '<div id="_blog_exists" style="display:' . ($blogCnt > 0 ? 'block' : 'none') . ';">';
      if($blogCnt > 0)
      {
        echo '<form name="_blogExisting" action="/?action=flix.flix_blog_api.act" method="post" style="display:inline; padding-bottom:5px;">
              <input type="hidden" name="title" value=""/>
              <input type="hidden" name="post" value=""/>
              <input type="hidden" name="xml_path" value="' . $xml_path . '" />
              <input type="hidden" name="xml_orig" value="' . $xml_orig . '" />
              <input type="hidden" name="blog_code" value="' . $blog_code . '"/>
              <input type="hidden" name="fastflix" value="' . $fastflix . '" />
              <div><span class="bold" style="padding-right:5px;">Select which blog(s) to publish to.</span>(<a href="javascript:_toggle(\'_blog_new\'); _toggle(\'_blog_exists\');">Specify different blog</a>)</div>';
        foreach($blogs as $k => $v)
        {
          if(in_array($v['B_TYPE'], $supportedBlogs))
          {
            $class = $k % 2 == 1 ? 'bg_white' : 'bg_lite';
            echo '<div style="padding-top:5px; padding-bottom:5px;"  class="line_lite">
                    <div style="float:left; margin-top:-2px;"><input type="checkbox" name="ub_id[]" value="' . $v['B_ID'] . '" /></div>
                    <div style="float:left;">' . $v['B_URL'] . '</div>
                    <br/>
                  </div>';
          }
        }
        echo '  <div style="padding-top:10px;">
                  <div style="margin-top:-3px; float:left;"><input type="checkbox" value="1" name="make_fotos_public"/></div>
                  <div style="float:left;">Make these fotos in this Flix public</div>
                  <br clear="left"/>
                </div>
                </form>
                <div><a href="javascript:postToExisting();"><img src="images/buttons/blog_flix.gif" width="123" height="24" vspace="4" border="0" /></a></div>
                <div>*This feature is currently in Beta</div>';
      }
      
      echo '</div>';
    ?>
    <div id="_blog_new" style="display:<?php echo ($blogCnt == 0 ? 'block' : 'none'); ?>;">
      <form name="_blogNew" action="/?action=flix.flix_blog_api.act" method="post" style="display:inline;">
      <input type="hidden" name="title" value=""/>
      <input type="hidden" name="post" value=""/>
      <input type="hidden" name="xml_path" value="<?php echo $xml_path; ?>" />
      <input type="hidden" name="xml_orig" value="<?php echo $xml_orig; ?>" />
      <input type="hidden" name="blog_code" value="<?php echo $blog_code; ?>"/>
      <input type="hidden" name="fastflix" value="<?php echo $fastflix; ?>" />
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
        <div style="float:left;">Make these fotos in this Flix public</div>
        <br clear="left"/>
      </div>
      <div style="padding-bottom:3px;">
        <div style="float:left; padding-right:3px;"><input type="checkbox" name="save_blog" value="1" /></div>
        <div style="float:left; margin-top:2px;">Remember this blog</div>
        <br clear="left"/>
      </div>
      <div><a href="javascript:blogIdent(document.forms['_blogNew'].elements['ub_type'].options[document.forms['_blogNew'].elements['ub_type'].selectedIndex].value, document.forms['_blogNew'].elements['ub_username'].value, document.forms['_blogNew'].elements['ub_password'].value);"><img src="images/buttons/blog_flix.gif" width="123" height="24" border="0" /></a></div>
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
              <td class="f_dark_accent bold" align="left" colspan="2">Embed this Flix into your page!</td>
            </tr>
            <tr>
              <td align="center" valign="middle"><img src="images/blog_embed.gif" width="30" height="39" border="0" /></td>
              <td valign="middle" class="f_8 f_red bold" nowrap>Copy &amp; paste<sup>*</sup></td>
              <td valign="middle" align="right"><img src="images/offsite_logo_myspace.gif" width="68" height="17" hspace="5" align="absmiddle" border="0" /><img src="images/offsite_logo_livejournal.gif" width="69" height="15" hspace="5" align="absmiddle" border="0" /><img src="images/offsite_logo_friendster.gif" width="70" height="14" hspace="5" align="absmiddle" border="0" /></td>
            </tr>
            <tr>
              <td colspan="3" align="center">
                <div style="padding-top:20px;"></div>
                <textarea name="ff_embed_blogger" wrap="virtual" class="formfield" style="width:500px; height:95px;"><?php echo htmlentities($ff_code_embed); ?></textarea>
                <div style="padding-top:5px;" class="f_8 f_red" align="left"><sup>*</sup>If you change to a different size theme then you will need to repaste new HTML code.</div>
              </td>
            </tr>
          </table>
        </div>
        <div style="padding-bottom:20px;"></div>
        <div class="border_dark bg_lite">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td width="60">&nbsp;</td>
              <td class="f_dark_accent bold" align="left" colspan="2">Link to this Flix from your page!</td>
            </tr>
            <tr>
              <td align="center" valign="middle"><img src="images/blog_link.gif" width="45" height="40" border="0" /></td>
              <td valign="middle" class="f_8 f_red bold" nowrap>Copy &amp; paste</td>
              <td valign="middle" align="right"><img src="images/offsite_logo_myspace.gif" width="68" height="17" hspace="5" align="absmiddle" border="0" /><img src="images/offsite_logo_livejournal.gif" width="69" height="15" hspace="5" align="absmiddle" border="0" /><img src="images/offsite_logo_friendster.gif" width="70" height="14" hspace="5" align="absmiddle" border="0" /></td>
            </tr>
            <tr>
              <td colspan="3" align="center"><div style="padding-top:20px;"></div><textarea name="ff_embed_blogger" wrap="virtual" class="formfield" style="width:500px; height:30px;"><?php echo htmlentities($ff_code_link); ?></textarea></td>
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