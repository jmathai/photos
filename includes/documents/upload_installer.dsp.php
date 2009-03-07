<?php
  $u = &CUser::getInstance();
  
  $desktopUploader = $u->pref($_USER_ID, 'DESKTOP_UPLOAD');
  
  // they have uploaded with the desktop uploader
  // show graphic alerting them that they have the desktop uploader and give the web-based links
  if(!empty($desktopUploader))
  {
    echo '<div>
            <div class="bold">Desktop uploader detected</div>
            <div>
              It appears that you have successfully uploaded photos using the desktop uploader.  You can find the desktop uploader in the Start menu under All Programs.  As always, you can use our web based uploaders.
              <ul>
                <li><a href="/?action=member.uploader_download.act">Download the desktop uploader</a></li>
                <li><a href="/?action=fotobox.upload_form_compat">Web based bulk uploader (Requires Java)</a></li>
                <li><a href="/?action=fotobox.upload_form_html">Web based basic uploader</a></li>
                <li><a href="/?action=home.samples&subaction=net">Click here if you have had problems installing the desktop uploader</a></li>
              </ul>
            </div>
            <div><img src="/images/uploader_locate.gif" width="532" height="273" border="0" /></div>
          </div>';
  }
  else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') === false) // else see if they don't have windows send them to the java uploader
  {
    echo '<script type="text/javascript">window.location = "/?action=fotobox.upload_auto_choose.act";</script>';
  }
  else // else give them the uploader and web-based links
  {
?>

<div style="width:500px; margin:auto;">
  <div>
    <div style="float:left; padding-left:50px; padding-right:20px;">
      <img src="images/uploader_logo.gif" width="68" height="54" border="0" />
    </div>
    <div style="float:left; padding-top:5px;">
      <div class="f_12 bold">Takes less than a minute to install!</div>
      <div class="f_10">Makes uploading photos fast and easy</div>
      <div style="padding-top:3px;">You can also use our <a href="/?action=fotobox.upload_auto_choose.act">web-based uploader</a>.</div>
      <div style="padding-top:3px;"><a href="/?action=home.samples&subaction=net">Having problems installing?</a></div>
    </div>
    <br clear="all" />
  </div>
  
  <div style="padding-top:20px;">
    <div style="float:left; margin-top:4px;">
      <div class="f_12 f_off_accent bold">STEP 1 - - - - &gt;</div>
    </div>
    <div style="float:left; padding-left:5px;">
      <a href="/?action=member.uploader_download.act"><img src="images/buttons/install.gif" width="123" height="28" border="0" /></a>
      <br/>
      Click to begin installation
    </div>
    <br clear="all" />
  </div>
  
  <div style="padding-top:20px;">
    <div style="float:left; margin-top:75px;">
      <div class="f_12 f_off_accent bold">STEP 2 - - - - &gt;</div>
    </div>
    <div style="float:left; padding-left:5px;">
      <img src="images/uploader_run_file.gif" width="309" height="189" border="0" />
    </div>
    <br clear="all" />
  </div>
  
  <div style="padding-top:20px;">
    <div style="float:left; margin-top:75px;">
      <div class="f_12 f_off_accent bold">STEP 3 - - - - &gt;</div>
    </div>
    <div style="float:left; padding-left:5px;">
      <img src="images/uploader_publisher_verification.gif" width="353" height="162" border="0" />
    </div>
    <br clear="all" />
  </div>
</div>

<?php
  }
?>