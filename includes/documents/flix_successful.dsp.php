<?php
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();
  $fastflix = $_GET['fastflix'];
  
  $flixData = $fl->fastflix($fastflix, $_USER_ID);
  $flixId   = $flixData['A_ID'];
  $fotoId   = $flixData['A_DATA'][0]['D_UP_ID'];
  $fotoData = $fb->fotoData($fotoId);
  
  $sizeArr    = explode('x', $flixData['A_SIZE']);
  $containerWidth = $sizeArr[0];
  $containerHeight= $sizeArr[1];
?>
  <div style="padding-left:15px;">
    <div class="f_11 f_off_accent bold" style="padding-bottom:10px;">Your Slideshow has been created!</div>
    
    <div>
      <div style="float:left;">
        <div class="flix_border"><a href="/fastflix?<?php echo $flixData['A_FASTFLIX']; ?>" target="_blank" onclick="_open(this.href, <?php echo $containerWidth; ?>, <?php echo $containerHeight; ?>); return false;"><img src="<?php echo PATH_FOTO . $fotoData['P_THUMB_PATH']; ?>" border="0" /></a></div>
        <div style="padding-left:7px;">(<a href="/fastflix?<?php echo $flixData['A_FASTFLIX']; ?>" target="_blank" onclick="_open(this.href, <?php echo $containerWidth; ?>, <?php echo $containerHeight; ?>); return false;">Click to view</a>)</div>
      </div>
      <div class="f_11 f_off_accent_bright bold" style="padding-top:60px;">Would you like to share "<?php echo $flixData['A_NAME']; ?>"?</div>
      <br clear="left" />
    </div>
    
    <div style="padding-bottom:5px; padding-top:10px;">
      <div style="float:left;">
        <div>
          <div style="float:left;"><img src="images/fb_actions_fotopage.gif" width="16" height="16" style="padding-right:10px;" /></div>
          <div id="flixPrivacyLink<?php echo $flixId; ?>"><a href="javascript:void(showFlixPrivacy('<?php echo $flixId; ?>', '331'));" class="f_9 f_dark bold">Add to My FotoPage?</a></div>
          <br/>
        </div>
        <div>
          <div style="float:left;"><img src="images/fb_actions_blog.gif" width="16" height="16" style="padding-right:10px;" /></div>
          <div><a href="javascript:void(showFlixBlogs('<?php echo $flixId; ?>','<?php echo $flixData['A_FASTFLIX']; ?>'));" class="f_9 f_dark bold">Send to my blog</a></div>
          <br/>
        </div>
        <div>
          <div style="float:left;"><img src="images/embed_flix.gif" width="14" height="13" style="padding-right:10px;" /></div>
          <div><a href="javascript:void(showCodeFlix('embed', '<?php echo $flixId; ?>', '<?php echo $flixData['A_FASTFLIX']; ?>'));" class="f_9 f_dark bold">Embed in a website</a></div>
          <br/>
        </div>
        <div>
          <div style="float:left;"><img src="images/email.gif" width="13" height="12" style="padding-right:10px;" /></div>
          <div><a href="javascript:void(showCodeFlix('link', '<?php echo $flixId; ?>', '<?php echo $flixData['A_FASTFLIX']; ?>'));" class="f_9 f_dark bold">Email/IM a link</a></div>
          <br/>
        </div>
        <div>
          <div style="float:left;"><img src="images/group.gif" width="14" height="16" style="padding-right:10px;" /></div>
          <div><a href="javascript:void(showFlixGroupList('<?php echo $flixId; ?>'));" class="f_9 f_dark bold">Send to a Group</a></div>
          <br/>
        </div>
      </div>
      <div style="width:20px; height:25px; float:left;"></div>
      <div>
        <div>
          <div style="float:left; width:150px;">&nbsp;</div><div id="flix_dialog_<?php echo $flixId; ?>" style="display:none; text-align:left; position:absolute;"></div>
        </div>
      </div>
      <br clear="left" />
    </div>
    
    <div class="line_lite"></div>
    
    <div style="padding-top:15px;"><img src="images/flix_medium.gif" width="20" height="23" align="absmiddle" style="padding-right:5px;" /><a href="/?action=flix.flix_list" class="f_11 f_dark bold">Or continue on to Slideshows</a></div>
  </div>
  
  <div style="padding-bottom:275px;"></div>
<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>