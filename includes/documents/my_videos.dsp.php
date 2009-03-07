<?php
  $v =& CVideo::getInstance();
  
  $videos = $v->search(array('USER_ID' => $user_id,'PERMISSION' => PERM_VIDEO_PUBLIC));
  
  $cnt_videos_array = count($videos);
  
  if($cnt_videos_array > 0)
  {
    foreach($videos as $v)
    {
      echo '<div style="float:left; margin:0px 0px 20px 0px; text-align:center;">
              <div><a href="/video?' . $v['V_KEY'] . '"><img src="' . PATH_FOTO . $v['V_SCREEN_150x100'] . '" hspace="20" vspace="2" width="150" height="100" border="0" class="border_medium" /></a></div>
              <div><a href="/video?' . $v['V_KEY'] . '">' . $v['V_NAME'] . '</a></div>
            </div>';
    }
  }
  else
  {
    if($user_id != $_USER_ID) // not logged in user
    {
      echo '<div style="width:425px; margin:auto;">
              <div class="bold italic">' . $displayName . ' has not added any videos to their personal page.</div>
              <div style="padding-left:20px; padding-top:5px;" class="bold">
                <div class="bullet"><a href="' . $my_fotos_url . '/">View all of this ' . $displayName . '\'s photos</a></div>
              </div>
            </div>';
    }
    else // logged in user
    {
      echo '<div class="bold">
              <div>You have not added any videos to your personal page.</div>
              <div style="margin:5px 0px 0px 25px;">
                <div><a href="/xml_result?action=fotopage_list_fotos&subsction=' . $subaction . '" class="plain lbOn"><img src="images/icons/add_alt_2_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="4" align="absmiddle" />Add your photos or slideshows to your personal page</a></div>
              </div>
            </div>';
    }
  }
?>