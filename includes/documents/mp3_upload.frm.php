<?php
  $u =& CUser::getInstance();
  $fv =& new CFormValidator;
  
  $sess_id = md5($_USER_ID . NOW);
  
  if($args[0] == 'mp3_upload_form')
  {
    $update = false;
    $user_enc = $u->userIdEnc($_USER_ID);
    $formAction = FF_ENVIORNMENT == 'production' ? "/cgi-bin/upload_mp3.cgi?sessionid={$sess_id}&user_enc={$user_enc}" : '/?action=fotobox.mp3_upload_finalize.act';
  }
  else
  if($args[0] == 'mp3_update_form' && isset($args[1]))
  {
    $fb =& CFotobox::getInstance();
    $mp3_data = $fb->mp3($args[1], $_USER_ID);
    $update = true;
    $formAction = '/?action=fotobox.mp3_update.act';
  }
  
  $fv =  new CFormValidator;
  $fv -> setForm('_uploader');
  $fv -> addElement('um_name', 'Name', '  - Enter a name for your MP3.', 'length');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_mp3Submit');
  $fv -> setJavascriptSubmit(true);
?>

<script language="javascript">
  function uploaderCover()
  {
    _toggle('uploaderCover');
  }
</script>

<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td align="center">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left">
            <div id="uploaderHtml">
              <form name="_uploader" enctype="multipart/form-data" action="<?php echo $formAction; ?>" method="post">
                <span class="bold">Upload your own MP3 file.</span>
                <br /><br />
                <span class="bold">Name:</span><br />
                <input type="text" name="um_name" class="formfield" value="<?php if($update === true){ echo $mp3_data['M_NAME']; } ?>" style="width:175px;" /><br /><br />
                <?php
                  if($update === false)
                  {
                    $fv -> addElement('file[]', 'MP3 File', '  - Select a valid MP3 file.', 'regexp/.*\.mp3$/i');
                    //$fv -> setEval('upload()');
                    $fv -> setEval('postItMp3(\'' . $sess_id . '\'); opacity(\'uploaderHtml\', 100, 0, 750, true); setTimeout(\'uploaderCover()\', 1000);');
                    echo '<span class="bold">MP3*:</span><br />
                          <input type="file" name="file[]" class="formfield" />&nbsp;(Max 10MB)<br />
                          <a href="javascript:_mp3Submit();"><img src="images/buttons/upload.gif" width="87" height="27" vspace="5" border="0" /></a>
                          <div style="padding-top:5px;">* Please make sure that the MP3 is 44kHz</div>
                          <div style="margin-top:10px; width:250px; height:60px; display:none;" id="uploadProgressDiv"><img src="images/loading_bar.gif" width="100" height="12" border="0" style="padding-top:3px;" /></div>';
                  }
                  else
                  {
                    echo '<a href="javascript:_mp3Submit();"><img src="images/buttons/update.gif" width="87" height="27" vspace="5" border="0" /></a>
                          <input type="hidden" name="um_id" value="' . $mp3_data['M_ID'] . '" />';
                  }
                ?>
                <input type="hidden" name="um_u_id" value="<?php echo $_USER_ID; ?>" />
              </form>
            </div>
            
            <div id="uploaderCover" style="width:100%; margin-top:-100px; display:none;" align="center">
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
                </div>
                <a href="/popup/mp3_upload_form"><img src="images/buttons/cancel.gif" width="87" height="25" vspace="5" border="0" /></a>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<?php
  $fv -> validate();
?>