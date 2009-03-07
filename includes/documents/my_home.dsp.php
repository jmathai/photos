<?php
  $u = &CUser::getInstance();
  $f  = &CFotobox::getInstance();
  
  $photoData = $f->fotosSearch(array('USER_ID' => $user_id, 'PERMISSION' => PERM_PHOTO_PUBLIC, 'LIMIT' => 5, 'ORDER' => 'P_TAKEN_BY_DAY'));

  
  $photoKey = $u->pref($user_id, 'PP_PHOTO_KEY');
  $isPhotoSet = false;
  if(!empty($photoKey))
  {
    $photo = $f->fotoData($photoKey);
    $photoData[0] = $photo;
    $isPhotoSet = true;
  }
  
  $fotoLink = '/users/' . $username . '/photo/' . $photoData[0]['P_ID'] . '/?offset=0';
?>

<div>
  <div id="blogContent">
    <?php
      $photoCount = count($photoData);
      
      if($photoCount > 0)
      {
        $width = $photoData[0]['P_WIDTH'];
        $height= $photoData[0]['P_HEIGHT'];
        
        
        if($photoData[0]['P_ROTATION'] == '90' || $photoData[0]['P_ROTATION'] == '270')
        {
          $_tmp = $width;
          $width = $height;
          $height= $_tmp;
        }
        
        if($width >= $height) // crop top/bottom
        {
          $photoWidth = 550;
          $photoHeight = 300;
          $marginTop = 300;
        }
        else // crop left/right
        {
          $photoWidth = 400;
          $photoHeight = 533;
          $marginTop = 533;
        }
        
        $fotoURL = dynamicImage($photoData[0]['P_THUMB_PATH'], $photoData[0]['P_KEY'], $photoWidth, $photoHeight);
        
        echo '<div class="bold" style="margin-bottom:5px;">' . $displayName . '\'s recent photos...</div>';
        
        if($logged_in === true && $user_id == $_USER_ID)
        {
          echo '<div id="personalHome1Div" style="width:' . $photoWidth . 'px; height:' . $photoHeight . 'px; margin:auto;" class="border_dark" onmouseover="$(\'personalHome1Controls\').style.display = \'block\';" onmouseout="$(\'personalHome1Controls\').style.display = \'none\';"><a id="personalHome1Link" href="' . $fotoLink . '"><img id="personalHome1" src="' . $fotoURL . '" width="' . $photoWidth . '" height="' . $photoHeight . '"  border="0" /></a>';
          echo '<span id="personalHome1Controls" style="display:none;">';
          if($isPhotoSet == false)
          {
            echo '<a href="javascript:void(0);" onclick="changePhoto(\'\');" title="click to change photo"><div id="photoIconDiv" style="position:relative; margin-top:-' . $marginTop . 'px;"><img id="photoIcon" src="images/icons/pencil_24x24.png" class="png" width="24" height="24" border="0" /></div></a><a href="javascript:void(0);" onclick="removePhoto(\'\');" title="click to remove photo"><div id="photoIconRemoveDisplay" style="display:none; position:relative; margin-top:-23px; margin-left:30px;"><img id="photoIconRemove" src="images/icons/delete_24x24.png" class="png" width="24" height="24" border="0" /></div></a></div>';
          }
          else 
          {
            echo '<a href="javascript:void(0);" onclick="changePhoto(\'\');" title="click to change photo"><div id="photoIconDiv" style="position:relative; margin-top:-' . $marginTop . 'px;"><img id="photoIcon" src="images/icons/pencil_24x24.png" class="png" width="24" height="24" border="0" /></div></a><a href="javascript:void(0);" onclick="removePhoto(\'\');" title="click to remove photo"><div id="photoIconRemoveDisplay" style="display:block; position:relative; margin-top:-23px; margin-left:30px;"><img id="photoIconRemove" src="images/icons/delete_24x24.png" class="png" width="24" height="24" border="0" /></div></a></div>';
          }
          echo '</span>';
        }
        else 
        {
          echo '<div id="personalHome1Div" style="width:' . $photoWidth . 'px; height:' . $photoHeight . 'px; margin:auto;" class="border_dark"><a id="personalHome1Link" href="' . $fotoLink . '"><img id="personalHome1" src="' . $fotoURL . '" width="' . $photoWidth . '" height="' . $photoHeight . '"  border="0" /></a></div>';
        }
        
        echo '<div style="text-align:left; margin-top:-' . $marginTop . 'px; margin-left:0px; position:absolute;" id="photoBlank"></div>
              <script type="text/javascript"> var photoBlankEffect = new fx.Opacity("photoBlank"); photoBlankEffect.hide(); </script>';
        for($i = 1; $i < $photoCount; $i++)
        {
          $url = dynamicImage($photoData[$i]['P_THUMB_PATH'], $photoData[$i]['P_KEY'], 115, 50);
          echo '<div style="float:left; margin-bottom:10px;"><a href="users/' . $username . '/photo/' . $photoData[$i]['P_ID'] . '/?offset=' . $i . '"><img src="' . $url . '" width="115" height="50" border="0" hspace="10" vspace="25" class="border_dark" /></a></div>';
        }
      }
      else
      {
        if($user_id != $_USER_ID)
        {
          echo '<div class="bold italic">' . $displayName . ' has not added any photos to their personal page.</div>';
        }
        else
        {
          echo '<div class="bold">
                  <div>You have not added any photos to your personal page.</div>
                  <div style="margin:5px 0px 0px 25px;">
                    <div><a href="/?action=fotobox.upload_installer" class="plain"><img src="images/icons/left_up_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="4" align="absmiddle" />Start by uploading some photos</a></div>
                    <div><a href="/xml_result?action=fotopage_list_fotos&subaction=' . $subaction . '" class="plain lbOn"><img src="images/icons/add_alt_2_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="4" align="absmiddle" />Then add your photos or slideshows to your personal page</a></div>
                  </div>
                </div>';
        }
      }
    ?>
  </div>
  <div id="blogSideBar">
    <?php include_once PATH_DOCROOT . '/my_sidebar.dsp.php'; ?>
  </div>
</div>
