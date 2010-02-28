<?php
  $u =& CUser::getInstance();
  $um=& CUserManage::getInstance();

  $setPrefs['LAST_UPLOADED'] = date('Y-m-d H:i:s', NOW);
  $um->setPrefs($_USER_ID, $setPrefs);

  $flashUploaded = $u->pref($_USER_ID, 'FLASH_UPLOADED');
  $userIdEnc = $u->userIdEnc($_USER_ID);
  
  $tipKey = 'uploader_start';
  if($flashUploaded !== false)
  {
    $flashUploadedMultiple = $u->pref($_USER_ID, 'FLASH_UPLOADED_MULTIPLE');
    if($flashUploadedMultiple === false)
    {
      $tipKey = 'uploader_browse';
    }
    else
    {
      $tipKey = 'why_tag';
    }
  }
?>


<div style="width:540px; float:left;">
  <ol class="f_12 bold" id="uploadSteps">
    <li><button id="spanSWFUploadButton"></button></li>
    <li><input type="text" name="tagsToAdd" value="" onblur="uploaderInst.addPostParam('tagsToAdd', this.value);" /></li>
    <li><button id="spanSWFUploadAction" onclick="uploaderInst.startUpload();">Upload photos</button></li>
  </ol>

  <div class="f_12 bold">Files selected for upload (<em id="uploadCount">0</em> selected so far)</div>
  <ul id="uploadList">
  </ul>
</div>
<div id="userTip" style="padding:15px 5px 0px 0px; float:left;"></div>

<br clear="all" />

<script>
  var counter = 0;
  var upEffect = new fx.Opacity('uploadList');
  upEffect.hide();
  
  swfStartUploadHandler = function(file){ /*console.info(file);*/ }
  
  var uploaderInst = new SWFUpload(
    {
      upload_url: 'http://<?php echo FF_SERVER_NAME; ?>/upload_single?fieldName=Filedata&user_enc=<?php echo $userIdEnc; ?>',
      flash_url: '/swf/site/swfupload.swf',
      file_post_name: '',
      button_placeholder_id: 'spanSWFUploadButton',
      button_width : 24,
      button_height : 24,
      button_image_url : '/images/upload_sprite.png',
      //button_text : '<img src="/images/icons/add_alt_2_24x24.png" align="absmiddle" height="24" width="24" /> Select Photos',
      swfupload_loaded_handler: function(){
        upEffect.toggle();
      },
      file_queued_handler : function(file){
        var li = document.createElement('li');
        li.innerHTML = file.name;
        li.id = 'upload-'+file.id;
        $('uploadList').appendChild(li);
        $('uploadCount').innerHTML = ++counter;
        //console.info(file);
      }, 
      upload_start_handler: function(file){ 
        //console.info('starting');
        $('upload-'+file.id).style.backgroundColor='yellow';
      },
      upload_complete_handler: function(file){
        $('upload-'+file.id).style.backgroundColor='green';
        this.startUpload();
      }
    }
  );

  //embedSwf({SRC:'/swf/slideshow/uploader.swf',BGCOLOR:'#ffffff',WIDTH:'540',HEIGHT:'430'}, 'flashUploader');
  var t = new Tips();
  t.setStyle('box-left');
  t.setWidth(245);
  t.setKey('<?php echo $tipKey; ?>');
  t.displayTip($('userTip'));
</script>

<a href="javascript:uploaderInst.SelectFiles();">select</a>
<a href="javascript:uploaderInst.startUpload();">start</a>
