<?php
  if(permission($_FF_SESSION->value('account_perm'), PERM_USER_1) === true)
  {
    $u =& CUser::getInstance();
    $v =& CVideo::getInstance();
    
    $fv =  new CFormValidator;
    $fv -> setForm('_videoUpload');
    $fv -> addElement('name', 'Name', '  - Please enter a name for your video.', 'length');
    $fv -> setMaxElementsToDisplay(5);
    $fv -> setDebugOutput(false);
    $fv -> setFunctionName('_val_video');
    // in conditional below $fv -> setEval('submitDivEff1.toggle();'); // postItVideo(\'' . $sess_id . '\'); 
    // below conditional $fv -> validate();
    
    $sess_id = md5($_USER_ID . NOW);
    $user_enc = $u->userIdEnc($_USER_ID);
    
    if(isset($_GET['videoId']))
    {
      $videoData = $v->find($_GET['videoId']);
      $frmAct = '/?action=video.update.act';
      $mode = 'update';
    }
    else
    {
      $fv -> setEval('submitDivEff1.toggle();'); // postItVideo(\'' . $sess_id . '\'); 
      $frmAct = "/cgi-bin/upload_video.cgi?sessionid={$sess_id}&user_enc={$user_enc}"; //'/upload_video'; //
      $mode = 'insert';
      $videoData['V_PRIVACY'] = PERM_VIDEO_DEFAULT;
    }
    
    $fv -> validate();
    
    if($_GET['message'] == 'fileTooLarge')
    {
      echo '<div class="confirm"><img src="images/icons/warning_alt_2_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="4" />The file you tried to upload was too large.</div>';
    }
?>

<div class="bold">
  <img src="images/icons/movie_24x24.png" class="png" width="24" height="24" border="0" hspace="4" align="absmiddle" />Upload a video
</div>

<form name="_videoUpload" method="post" action="<?php echo $frmAct; ?>" enctype="multipart/form-data" onsubmit="return _val_video();">
  <div class="formRow">
    <div class="formLabel">Name</div>
    <div class="formField"><input type="text" name="name" class="formfield" style="width:200px;" value="<?php echo $videoData['V_NAME']; ?>" /></div>
  </div>
  
  <div class="formRow" style="margin-top:10px;">
    <div class="formLabel">Tags</div>
    <div class="formField">
      <input autocomplete="off" type="text" id="autoCompleter" name="tags" class="formfield" style="width:150px;" value="<?php echo $videoData['V_TAGS']; ?>" /><br/>
      <div class="auto_complete" id="autoCompleter_auto_complete" style="width:150px;"></div>
    </div>
  </div>
  
  <div class="formRow" style="margin-top:10px;">
    <div class="formLabel">Description</div>
    <div class="formField"><textarea name="description" class="formfield" style="width:300px; height:50px;"><?php echo $videoData['V_DESCRIPTION']; ?></textarea></div>
  </div>
  
  <?php
    if($mode == 'update')
    {
      echo '<input type="hidden" name="v_id" value="' . intval($_GET['videoId']) . '" />';
    }
    else
    {
      echo '<div class="formRow" style="margin-top:10px;">
              <div class="formLabel">Video File</div>
              <div class="formField"><input type="file" name="video" class="formfield" />&nbsp;(up to 200MB)</div>
            </div>';
    }
  ?>
  
  <div class="formRow">
    <div style="margin-left:160px; margin-top:20px;">
      <div>
        <div class="bold">Who can view this?</div>
        <div>
          <div style="float:left;"><input type="checkbox" id="vPerm0" value="<?php echo PERM_VIDEO_PRIVATE; ?>" <?php if(($videoData['V_PRIVACY'] | PERM_VIDEO_PRIVATE) == PERM_VIDEO_PRIVATE){ echo 'checked="true"'; } ?> onclick="$('vPerm1').checked=false; $('vPerm2').checked=false; videoSetPermission();" /></div>
          <div style="float:left; margin-top:3px;">Only those I send a link to</div>
          <br clear="all" />
        </div>
        <div>
          <div style="float:left;"><input type="checkbox" id="vPerm1" value="<?php echo PERM_VIDEO_PUBLIC; ?>" <?php if(permission($videoData['V_PRIVACY'], PERM_VIDEO_PUBLIC)){ echo 'checked="true"'; } ?> onclick="$('vPerm0').checked=false; videoSetPermission();" /></div>
          <div style="float:left; margin-top:3px;">Anyone can view it</div>
          <br clear="all" />
        </div>
        <div>
          <div style="float:left;"><input type="checkbox" id="vPerm2" value="<?php echo PERM_VIDEO_COMMENT; ?>" <?php if(permission($videoData['V_PRIVACY'], PERM_VIDEO_COMMENT)){ echo 'checked="true"'; } ?> onclick="$('vPerm0').checked=false; $('vPerm1').checked=true; videoSetPermission();" /></div>
          <div style="float:left; margin-top:3px;">Allow comments</div>
          <br clear="all" />
        </div>
      </div>
    </div>
    <input type="hidden" name="privacy" id="privacy" value="<?php echo $videoData['V_PRIVACY']; ?>" />
  </div>
  
  <div class="formRow" style="margin-top:10px;">
    <div class="formIndent">
      <div id="submitDiv"><input type="image" src="images/buttons/<?php echo ($mode == 'update' ? 'update.gif' : 'upload.gif'); ?>" width="87" height="27" border="0" /></div>
      <div id="submitDivProgress">
        <table border="0" cellpadding="0" cellspacing="0">
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
                <tr>
                  <td colspan="3" align="center">
                    <a href="/?action=video.upload_form"><img src="images/buttons/cancel.gif" width="87" height="25" vspace="5" border="0" /></a>
                  </td>
                </tr>
              </table> 
            </td>
           </tr>
         </table>
      </div>
    </div>
  </div>
</form>

<script type="text/javascript">
  function videoSetPermission()
  {
    var privacy = 0;
    if($('vPerm0').checked)
    {
      privacy = privacy | $('vPerm0').value;
    }
    
    if($('vPerm1').checked)
    {
      privacy = privacy | $('vPerm1').value;
    }
    
    if($('vPerm2').checked)
    {
      privacy = privacy | $('vPerm2').value;
    }
    
    $('privacy').value = privacy;
  }

  new Autocompleter.Local('autoCompleter', 'autoCompleter_auto_complete', userTags, {tokens: ","});
  var submitDivEff1 = new fx.Height('submitDiv', {duration:200, onComplete:function(){ submitDivEff2.toggle(); postItVideo('<?php echo $sess_id; ?>'); } });
  
  var submitDivEff2 = new fx.Opacity('submitDivProgress');
  submitDivEff2.hide();
</script>

<?php
  }
  else
  {
    include_once PATH_DOCROOT . '/upgrade_coming_soon.dsp.php';
  }
?>