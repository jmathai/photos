<?php
  $u =& CUser::getInstance();
  
  $profileData = $u->profile($user_id);
  
  if($_USER_ID != $user_id)
  {
    if(trim($profileData['P_PROFILE']) != '')
    {
      echo $profileData['P_PROFILE'];
    }
    else
    {
      echo '<div class="bold italic" style="width:300px; margin:auto;">' . $displayName . ' has not filled out a profile.</div>';
    }
  }
  else
  {
    if(isset($_GET['updated']))
    {
      echo '<div id="updatedProfile" style="margin-bottom:10px;" class="f_10 bold f_white"><img src="images/icons/checkmark_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" hspace="3" />Your profile has been updated</div>';
      echo '<script type="text/javascript">
              var effect = new fx.Height("updatedProfile", {duration:400});
              setTimeout("effect.toggle()", 4000);
            </script>';
    }
    
    if($profileData['P_PROFILE'] != '')
    {
      $profileContent = str_replace(array("\n","\r"), '', $profileData['P_PROFILE']);
    }
    else
    {
      $profileContent = '<strong>Enter your profile here.</strong><br/><br/>You can easily add your photos by clicking on the photo icon in the toolbar.';
    }
?>

    <form name="profile" action="/?action=my.profile.act" method="post">
      <div style="margin-bottom:10px;">
        <a href="javascript:void(0);" onclick="oEditor.SwitchEditMode();" class="f_white plain"><img src="images/icons/write_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" />Edit HTML</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:void(0);" onclick="previewProfile();" class="f_white plain"><img src="images/icons/preview_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" />Preview Profile</a>
      </div>
      <script type="text/javascript">
        <!--
        var sBasePath = '/js/fck/';
        
        var oFCKeditor = new FCKeditor( 'fck_instance' ) ;
        oFCKeditor.BasePath	= sBasePath ;
        oFCKeditor.Height	= 300;
        oFCKeditor.Width	= 800;
        oFCKeditor.Value	= '<?php echo $profileContent; ?>' ;
        oFCKeditor.Create() ;
        
        function FCKeditor_OnComplete( editorInstance )
        {
          oEditor = FCKeditorAPI.GetInstance('fck_instance');
        }
        
        function previewProfile()
        {
          var previewProfile = window.open("", "previewProfile", "width=800,height=300,scrollbars=1");
          previewProfile.document.open("text/html", "");
          previewProfile.document.write('<html><head><title>Profile Preview</title></head><body>' + oEditor.GetXHTML() + '</body></html>');
          previewProfile.document.close();
        }
        //-->
    	</script>
    	<input type="hidden" name="redirect" value="/users/<?php echo $username; ?>/profile/" />
      <!--<input type="image" src="images/buttons/update.gif" width="87" height="27" vspace="5" border="0" />-->
      <div style="margin-top:10px;" class="">
        <a href="javascript:document.forms['profile'].submit();" class="f_white plain f_11 bold"><img src="images/icons/save_24x24.png" class="png" width="24" height="24" hspace="3" border="0" align="absmiddle" />Save Profile</a>
      </div>
    </form>
<?php
  }
?>