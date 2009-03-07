<?php
  $u  =& CUser::getInstance();
  $fb =& CFotobox::getInstance();
  $t  =& CTag::getInstance();
  $fv =& new CFormValidator;
  
  $user_data= $u->find($_USER_ID);
  
  $tags = $t->tags($_USER_ID, 'WEIGHT', 5);
  $tagsHtml = 'Examples:
            <div class="bullet">vacation</div>
            <div class="bullet">birthday</div>
            <div class="bullet">' . strtolower(date('F', NOW)) . '</div>
            <div class="bullet">vacation</div>
            <div class="bullet">julie</div>';
  
  if($tags[0]['COUNT'] > 0)
  {
    $tagsHtml = 'Most used tags:';
    foreach($tags as $v)
    {
      $tagsHtml .= '<div class="bullet"><a href="javascript:void(0);" onclick="addTagToForm(\'' . $v['TAG'] . '\');">' . $v['TAG'] . '</a></div>';
    }
  }
  
  $fotoPref = $u->pref($_USER_ID, 'FOTO_PRIVACY');
  if($fotoPref < PERM_PHOTO_DEFUALT)
  {
    $fotoPref = PERM_PHOTO_DEFUALT;
  }
  
  
  $sess_id = md5($_USER_ID . NOW);
  $user_enc = $u->userIdEnc($_USER_ID);
  
  $elements = 5;
  
  /*/cgi-bin/upload_fotos.cgi?sessionid=<?php echo $sess_id; ?>&user_enc=<?php echo $user_enc; ?>*/
  $formAction = FF_ENVIORNMENT != 'local' ? "/cgi-bin/upload_fotos.cgi?sessionid={$sess_id}&user_enc={$user_enc}" : '/upload';
  
  $fv -> setForm('_fotoUploader');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_upload');
  $fv -> setEval('postItFotos(\'' . $sess_id . '\'); effectUploader.toggle();');
  $fv -> setJavascriptSubmit(true);
?>

<script language="javascript">
  function uploaderCover()
  {
    _toggle('uploaderCover');
  }
</script>

<div style="padding-top:5px;"></div>

<table border="0" cellpadding="0" cellspacing="0" width="733">
  <tr>
    <td width="10"><img src="images/spacer.gif" width="10" height="1" border="0" /></td>
    <td align="left" valign="top" width="315">
      <script language="javascript">
        function goUploader()
        {
          if(navigator.userAgent.indexOf('Win') > -1 || navigator.userAgent.indexOf('Mac') > -1)
          {
            location.href = '/?action=fotobox.upload_form';
          }
          else
          {
            location.href = '/?action=fotobox.upload_form_compat';
          }
        }
      </script>
      <div style="height:575px;">
        <span class="f_10 f_off_accent bold"><img src="images/favorite.gif" width="11" height="11" border="0" style="padding-right:4px;" />Photagious recommends the bulk uploader</span>
        <div style="padding-left:17px;">
          <div><a href="javascript:goUploader();"><img src="images/fotouploader_screen.gif" width="125" height="91" border="0" vspace="5" class="border_complete" /></a></div>
          <div class="bold">
            <div class="bullet">Easier and faster to use</div>
            <div class="bullet">Drag and drop interface</div>
            <div class="bullet">Batch your upload to save time</div>
          </div>
        </div>
      </div>
    </td>
    <td width="13"><img src="images/spacer.gif" width="13" height="1" border="0" /></td>
    <!--/cgi-bin/upload_fotos.cgi?sessionid=<?php echo $sess_id; ?>&user_enc=<?php echo $user_enc; ?>-->
    
    <form name="_fotoUploader" method="post" action="<?php echo $formAction; ?>" enctype="multipart/form-data">
      <td align="left" valign="top" width="395">
        <div id="uploaderHtml" style="width:395px; height:575px;" class="bg_white">
          <div style="padding-bottom:5px;">
            <div style="padding-top:5px;"></div>
            Select up to <?php echo $elements; ?> photos to upload.<br />Click the "upload" button once you have selected your photos.
            <br /><br />
            <?php
              for($i=1; $i<=$elements; $i++)
              {
                echo    '<div class="bold">Select photo ' . $i . ($i == 1 ? ' (required)' : '') . ':</div>'
                     .  '<input type="file" name="Image' . $i . '" id="Image' . $i . '" class="formfield" style="width:250px" /><br /><br />';
                if($i == 1)
                {
                  $fv -> addElement('Image' . $i, ' Photo ' . $i, '  - Select a valid Jpeg photo file.', 'regexp/.*\.(jpg|jpeg)$/i');
                }
              }
              
              $fv->validate();
            ?>
            
            <div style="padding-bottom:5px;">
              <div class="bold">Add these tags:</div>
              <input autocomplete="off" type="text" id="autoCompleter" name="tagsToAdd" value="" class="formfield" style="width:150px;" /><br/>
              <div class="auto_complete" id="autoCompleter_auto_complete" style="width:150px;"></div>
              <script type="text/javascript"> new Autocompleter.Local('autoCompleter', 'autoCompleter_auto_complete', userTags, {tokens: ","}); </script>
              <div>
                <?php echo $tagsHtml; ?>
              </div>
            </div>
            <div style="padding-bottom:5px;">
              <div class="bold">Who can view these photos?</div>
              <div>
                <script> document.write(getPrivacyForm(<?php echo intval($fotoPref); ?>)); </script>
              </div>
            </div>
            <a href="javascript:_val_upload();"><img src="images/buttons/upload.gif" width="87" height="27" border="0" /></a>
          </div>
        </div>
        
        <div id="uploaderCover" style="width:395px; height:430px; display:none;" align="center">
          <div style="padding-top:160px;">
            <a name="progressBar"></a>
            <div style="margin-top:10px; width:250px; height:60px;" id="uploadProgressDiv">
              <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                  <td align="center" valign="middle">
                    <table border="0" cellpadding="0" cellspacing="0" width="214">
                      <tr><td align="left" colspan="4" style="font-size:10px; font-weight:bold;">Total Progress...<span id="uploadProgressPercent">0</span>% complete</td></tr>
                      <tr height="1">
                        <td width="5"><img src="/images/spacer.gif" width="5" height="1" border="0" /></td>
                        <td width="204" ><img src="/images/spacer.gif" height="1" width="204" border="0" /></td>
                        <td width="5"><img src="/images/spacer.gif" width="5" height="1" border="0" /></td>
                      </tr>
                      <tr>
                        <td width="5"><img src="/images/upload_bar_left.gif" width="5" height="21" border="0" /></td>
                        <td width="204" align="left" background="/images/upload_bar_bg.gif" colspan="2"><img src="/images/upload_bar_end_left.gif" height="21" width="2" border="0" /><img src="/images/upload_bar_middle.gif" id="uploadProgressWidth" height="21" width="0" border="0" /><img src="/images/upload_bar_end_right.gif" height="21" width="2" border="0" /></td>
                        <td width="5"><img src="/images/upload_bar_right.gif" width="5" height="21" border="0" /></td>
                      </tr>
                    </table> 
                  </td>
                 </tr>
               </table>
              <!--<div class="f_10 bold center"><img src="images/ajax_loader_snake.gif" width="16" height="16" border="0" hspace="4" align="absmiddle" />Please wait...</div>-->
            </div>
            <a href="/?action=fotobox.upload_form_html"><img src="images/buttons/cancel.gif" width="87" height="25" vspace="5" border="0" /></a>
          </div>
        </div>
        
        <script type="text/javascript">
          var effectUploader = new fx.Opacity('uploaderHtml', {onComplete:function(){ $('uploaderHtml').style.display="none"; $('uploaderCover').style.display="block"; } });
        </script>
      </td>
    </form>
  </tr>
</table>