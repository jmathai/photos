<?php
  $u  =& CUser::getInstance();
  $t  =& CTag::getInstance();
  $fb =& CFotobox::getInstance();
  
  //$last_uploaded = $fb->lastUploaded($_USER_ID);
  $last_uploaded = date('Y-n-d H:i:s', NOW);
  
  $user_data= $u->find($_USER_ID);
  $user_enc = $u->userIdEnc($_USER_ID);
  
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
?>

<script src="/js/iuembed.js"></script>

<div style="width:733px; padding-bottom:10px;" align="center" id="uploaderNotLoaded">
  <div style="padding-bottom:3px;"><a href="/?action=fotobox.upload_form_html"><img src="images/exclamation.gif" align="absmiddle" border="0" style="padding-right:4px;" /></a><a href="/?action=fotobox.upload_form_html">Not seeing anything below?  Click here.</a></div>
  <div>(This may take up to 10 seconds to appear)</div>
</div>

<div id="_uploader_view">
  <div id="uploaderExtras" style="padding-bottom:10px; height:125px; display:none;">
    <form id="tagsToAddForm" name="tagsToAddForm" style="display:inline;" onsubmit="return false;">
      <div style="margin-left:25px; width:350px; float:left;">
        <div class="bold">Add these tags:</div>
        <div>
          <input autocomplete="off" type="text" id="autoCompleter" name="tagsToAdd" value="" class="formfield" style="width:150px;" /><br/>
          <div class="auto_complete" id="autoCompleter_auto_complete" style="width:150px; z-index:75;"></div>
          <script>new Autocompleter.Local('autoCompleter', 'autoCompleter_auto_complete', userTags, {tokens: ","})</script>
        </div>
        <div>
          <?php echo $tagsHtml; ?>
        </div>
      </div>
      <div style="width:33px; float:left;">&nbsp;</div>
      <div style="width:350px; float:left;">
        <div class="bold">Add these photos to your Personal Page?</div>
        <div>
          <script> document.write(getPrivacyForm(<?php echo intval($fotoPref); ?>)); </script>
        </div>
      </div>
    </form>
    <br clear="left" />
  </div>
  
  <div style="padding-top:10px;" class="line_lite"></div>
  <div style="padding-top:10px;"></div>
  
<script>
  function ImageUploader_InitComplete()
  {
    document.getElementById('uploaderExtras').style.display = 'block';
    document.getElementById('uploaderNotLoaded').style.display = 'none';
  }

  var iu = new ImageUploaderWriter("ImageUploader", 733, 500);
  
  //For ActiveX control we specify full path for CAB file
  iu.activeXControlEnabled=false;
  //iu.activeXControlCodeBase = "/bin/ImageUploader2.cab";
  //iu.activeXControlVersion = "";
  //For Java applet we specify only directory with JAR files
  iu.javaAppletCodeBase = "/";
  iu.javaAppletCached = false;
  iu.javaAppletVersion = "2.0.35.0";
  
  iu.addParam("Layout", "ThreePanes");
  iu.addParam("FolderView", "Thumbnails");					
  iu.addParam("UploadView", "Thumbnails");				
  
  iu.addParam("UploadThumbnail1FitMode", "fit");
  iu.addParam("UploadThumbnail1Width", "120");
  iu.addParam("UploadThumbnail1Hieght", "120");
  iu.addParam("UploadThumbnail1JpegQuality", "60");
  
  iu.addParam("BackgroundColor", "#FFFFFF");
  iu.addParam("PaneBackgroundColor", "#FFFFFF");
  
  iu.addParam("ButtonSendText", "Upload");
  iu.addParam("ButtonStopText", "Cancel");
  iu.addParam("TreePaneHeight", "200");
  iu.addParam("ButtonSelectAllText", "Select all");
  iu.addParam("ButtonDeselectAllText", "Deselect all");
  
  iu.addParam("PreviewThumbnailSize", "75");
  iu.addParam("FolderPaneHeight", "325");
  iu.addParam("TreePaneHeight", "200");
  iu.addParam("DropFilesHereText", "Drag and drop photos here");
  iu.addParam("ProgressDialogTitleText", "Photagious Uploader");
  
  iu.addParam("FilesPerOnePackageCount", "1");
  iu.addParam("MaxConnectionCount", "5");
  iu.addParam("AutoRecoverMaxTriesCount", "5");
  iu.addParam("AutoRecoverTimeOut", "10000");
  
  iu.addParam("KilobytesText", "KB");
  iu.addParam("MegabytesText", "MB");
  iu.addParam("ShowDescriptions", "false");
  iu.addParam("MessageBoxTitleText", "Photagious Uploader");
  iu.addParam("MessageUploadCancelledText", "The Photagious Uploader was cancelled.");
  iu.addParam("MessageUploadCompleteText", "");
  iu.addParam("MessageNoFilesToSendText", "Please drag and drop the photos you want to upload into the pane below the thumbnails.");
  iu.addParam("MessageUnexpectedErrorText", "The Photagious Uploader was interupted.");
  iu.addParam("ProgressDialogWaitingForResponseFromServerText", "Waiting for a response from Photagious...");
  iu.addParam("ProgressDialogSentText", "Amount sent: [Current] of [Total]");
  //iu.addParam("MessageMaxTotalFileSizeExceededText", "The photo [Name] cannot be selected because it exceeds your available limit of [Limit] Kb.");
  
  iu.addParam("ShowButtons", "True");
  iu.addParam("ShowStatusPane", "True");
  
  iu.addParam("AdditionalFormName", "tagsToAddForm");
  iu.addParam("ShowDebugWindow", "True");
  iu.addParam("Action", "/upload_single?user_enc=<?php echo $user_enc; ?>&no_redirect=1&java=1");
  iu.addParam("RedirectUrl", "/?action=fotobox.upload_preference_set.act&uploader_preference=2&redirect=<?php echo urlencode('/?action=fotobox.upload_successful&foto_date=' . $last_uploaded); ?>");
  iu.addParam("LicenseKey", "<?php echo FF_AURIGMA_KEY; ?>");
  iu.addParam("FileMask", "*.jpg;*.jpeg;*.jpe;");
  
  iu.addEventListener("InitComplete", "ImageUploader_InitComplete");
  
  iu.writeHtml();
</script>

</div> <!-- _uploader_view -->
  
<script>
  var oProgressUploader = document.getElementById('BoxUploader');
  var oProgressDialog = document.getElementById('BoxWaiting');
  var oProgressDialogContent = document.getElementById('BoxWaitingContent');
  var iMaxUploadRetry = 5;  //Mac retry count after which we stop uploading
  var iUploadRetry = 1;  //The current retry count
  var iRetryWait = 5;  //The time after which we try to reconnect
  var iRetryLeft = iRetryWait;  //the time left for retrying upload
  var bStopped = true;  //the time left for retrying upload
  var _total_files;
  var _finished_files = -1;
  var _notified = false;
  
  function ImageUploader_InitComplete()
  {
    document.getElementById('uploaderExtras').style.display = 'block';
    document.getElementById('uploaderNotLoaded').style.display = 'none';
  }
  
  function showUploader()
  {
    oProgressUploader.style.display = 'block';
    oProgressDialog.style.display = 'none';
  }
  
  function showWaiting()
  {
    oProgressUploader.style.display = 'none';
    oProgressDialog.style.display = 'block';
  }
  
  //show dialog with progress bar
  function showProgressDialog()
  {
    if ((oProgressDialog==null) || (oProgressDialog.style.display == 'none'))
    { 
      //oProgressDialog = window.open('#','preview','toolbar=no,width=313,height=240,directories=no,status=no,scrollbars=no,menubar=no,location=no,resizable=no');
      showWaiting();
      //on focus lost we set focus again, so way we simulate modal dialog
      oProgressDialogContent.innerHTML =  '<div align="center"><img src="images/upload_header.gif" width="313" height="41" border="0" /><br /><br />' + 
                                   '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="250" height="60" id="upload_animation" align="middle">' + 
                                   '<param name="allowScriptAccess" value="sameDomain" />' +
                                   '<param name="movie" value="/swf/upload_animation_small.swf" />' +
                                   '<param name="quality" value="high" />' +
                                   '<param name="bgcolor" value="#ffffff" />' +
                                   '<embed src="/swf/upload_animation_small.swf" quality="high" bgcolor="#ffffff" width="250" height="60" name="upload_animation" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />' +
                                   '</object>' + 
                                   '<table border="0" cellpadding="0" cellspacing="0" width="214" align="center">' +
                                   '<tr><td align="left" colspan="4"><span id="ProgressBarText"></span></td></tr>' + 
                                   '<tr>' + 
                                   '<td width="5"><img src="images/upload_bar_left.gif" width="5" height="21" border="0" /></td>' + 
                                   '<td width="2" background="images/upload_bar_bg.gif"><img src="images/upload_bar_end_left.gif" height="21" width="2" border="0" /></td>' + 
                                   '<td align="left" width="202" background="images/upload_bar_bg.gif"><table border="0" cellpadding="0" cellspacing="0"><tr><td><img src="images/upload_bar_middle_blue.gif" id="ProgressBar" height="21" width="1" border="0" /></td><td><img src="images/upload_bar_end_right.gif" height="21" width="2" border="0" /></td></tr></table></td>' + 
                                   '<td width="5"><img src="images/upload_bar_right.gif" width="5" height="21" border="0" /></td>' +
                                   '</tr>' + 
                                   '<tr><td colspan="4">&nbsp;</td></tr>' +
                                   '<tr><td align="left" colspan="4">Total Progress...<span id="ProgressLeftTotal"></span>% remaining.</td></tr>' + 
                                   '<tr>' + 
                                   '<td width="5"><img src="images/upload_bar_left.gif" width="5" height="21" border="0" /></td>' + 
                                   '<td align="left" width="204" background="images/upload_bar_bg.gif" colspan="2"><img src="images/upload_bar_end_left.gif" height="21" width="2" border="0" /><img src="images/upload_bar_middle.gif" id="ProgressBarTotal" height="21" width="1" border="0" /><img src="images/upload_bar_end_right.gif" height="21" width="2" border="0" /></td>' + 
                                   '<td width="5"><img src="images/upload_bar_right.gif" width="5" height="21" border="0" /></td>' +
                                   '</tr>' + 
                                   '</table>' + 
                                   '<a href="/?<?php echo $_SERVER['QUERY_STRING']; ?>" onClick="document.getElementById(\'ImageUploader\').Stop();"><img src="images/buttons/cancel_uploader.gif" width="70" height="22" border="0" vspace="5" /></a></div>';
    }
  }
  
  function retryUpload(){
  	if (iRetryLeft==0){
  		ImageUploader.Send();
  	}
  	else{
  		showProgressDialog();
  		try{
        var oProgressBarText = oProgressDialog.document.getElementById("ProgressBarText");
  			oProgressBarText.innerText = "Upload failed.  Attempting to retry in in " + iRetryLeft + " seconds.";
  		}
  		catch(e){}
  		iRetryLeft--;
  		window.setTimeout("retryUpload();", 1000);
  	}
  }
</script>

<script for="ImageUploader" event="UploadFileCountChange()">
  _total_trying = parseInt(document.getElementById('ImageUploader').TotalFileSize / 1024);
  _total_avail = <?php echo $available; ?>;
  ImageUploader.StatusSelectedFilesText = 'Available: ' + _total_avail + 'KB :: Selected: ' + _total_trying + 'KB';
  if(_total_trying > _total_avail && _notified == false)
  {
    alert("Your account only has <?php echo $available; ?>KB of space left.  Please remove some photos before trying to upload.");
    _notified = true;
    return;
  }
</script>

<script for="ImageUploader" event="Progress(Status, Progress, ValueMax, Value, StatusText)">
  //Get current file being uploaded
  var _tmp = StatusText.split('\\');
  var _filename = _tmp[_tmp.length - 1].substring(0,15);
  
  if(_filename == 'Waiting for res')
  {
    return;
  }
  //Max width of progress bar 
  var iProgressBarWidth = 198;
  showProgressDialog();
  
  //Progress dialog can be closed, so we need to handle this case
  try
  {
    var oProgressBarText = oProgressDialog.document.getElementById("ProgressBarText");
    var oProgressBar = oProgressDialog.document.getElementById("ProgressBar");
    var oProgressLeft = oProgressDialog.document.getElementById("ProgressLeft");
    var oProgressBarTotal = oProgressDialog.document.getElementById("ProgressBarTotal");
    var oProgressLeftTotal = oProgressDialog.document.getElementById("ProgressLeftTotal");
  }
  catch(e){}
  

  switch(Status)
  {
    case "START":
      //Show progress bar        
      break;
    case "PREPARE":
      //Progress dialog can be closed, so we need to handle this case
      try
      {  
        _finished_files++;
        //Show preparing progress
        //oProgresBarText.innerText = Status + " " + Math.round(Value/ValueMax*100) + "%";
        //Set width of progress bar 
        //oProgresBar.style.width = Math.round(Value/ValueMax*iProgresBarWidth) + "px";
        //oPrepareBar.width = Math.floor((Value/ValueMax)*iProgressBarWidth);
      }
      catch(e){}
      break;
    case "UPLOAD":
      //Progress dialog can be closed, so we need to handle this case
      try
      {
        //Show uploading progress
        oProgressBarText.innerText = "Uploading " + _filename + "...";
        /*oProgressBytesUpload.innerText = Math.floor(Value/1024);
        oProgressFilesUpload.innerText = _file_count_upload;*/
        //Set width of progress bar
        oProgressBarTotal.width = parseInt(Math.round((_finished_files/_total_files)*iProgressBarWidth) + 1);
        oProgressLeftTotal.innerText = (99 - Math.round((_finished_files/_total_files)*100));
        oProgressBar.width = parseInt(Math.round((Value/ValueMax)*iProgressBarWidth) + 1);
        oProgressLeft.innerText = document.getElementById('ImageUploader').UploadFileCount - 1;
      }
      catch(e){}
      break;
    case "COMPLETE":
      //Progress dialog can be closed, so we need to handle this case
      try
      {
        //Hide progress bar
        showWaiting();
        //Show custom message
      }
      catch(e){}
      //alert("All fotos were successfully uploaded.");
      //Redirect to PictureGalery.asp page when upload process is completed
      window.location.replace("/?action=fotobox.upload_preference_set.act&uploader_preference=1&redirect=<?php echo urlencode('/?action=fotobox.upload_successful&foto_date=' . $last_uploaded); ?>");
      //window.location.replace("/?action=fotobox.upload_successful&foto_date=<?php echo urlencode($last_uploaded); ?>");
      break;
    case "CANCEL":
      //Progress dialog can be closed, so we need to handle this case
      try
      {
        //Hide progress bar
        showWaiting();
      }
      catch(e){}
      //Show custom message
      alert("Photo Uploader cancelled.");
      showUploader();
      return;
      break;
    case "ERROR":
      //Progress dialog can be closed, so we need to handle this case
      try
      {
        //Hide progress bar      
        //oProgressDialog.close();  
      }
      catch(e){}
      //Show custom message
  		iUploadRetry++;		
  		if (iUploadRetry>iMaxUploadRetry)
  		{
  			//Stop uploading
  			//Progress dialog can be closed, so we need to handle this case
  			try{
  				//Hide progress bar			
  				oProgressDialog.close();	
  			}
  			catch(e){}
  			//Show custom message
  			alert("An error has occurred while uploading your photo(s).\n\nIf you continue to have problems please contact us at support" + "@" + "photagious.com.");	
        iUploadRetry = 1;
        showUploader();
  		}
  		else{
  			//Retry uploading in iRetryWait second
  			iRetryLeft = iRetryWait;
  			retryUpload();
  		}
  
      return;
      break;
  }
</script>
  
<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>