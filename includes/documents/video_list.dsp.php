<?php
  $v =& CVideo::getInstance();
  $videos = $v->search(array('USER_ID' => $_USER_ID, 'ORDER' => 'CREATED'));
  
  switch($_GET['message'])
  {
    case 'deleted':
      echo '<div class="confirm"><img src="images/icons/delete_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="4" />Your video was deleted successfully.</div>';
      break;
    case 'updated':
      echo '<div class="confirm"><img src="images/icons/checkmark_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="4" />Your video was updated successfully.</div>';
      break;
    case 'uploaded':
      echo '<div class="confirm"><img src="images/icons/checkmark_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="4" />Your video was uploaded successfully.</div>';
      break;
  }
  
  if(count($videos) > 0)
  {
    foreach($videos as $v)
    {
      echo '<div style="float:left; margin:0px 20px 20px 0px; text-align:center;">
              <div class="video_border">
                <div class="video_inside"><a href="/video?' . $v['V_KEY'] . '"><img src="' . PATH_FOTO . $v['V_SCREEN_150x100'] . '" width="150" height="100" border="0" /></a></div>
              </div>
              <div><a href="/video?' . $v['V_KEY'] . '">' . str_mid($v['V_NAME'], 25) . '</a></div>
              <div style="margin-top:4px;">
                <a href="/?action=video.upload_form&videoId=' . $v['V_ID'] . '" class="plain"><img src="images/icons/edit_16x16.png" class="png" width="16" height="16" border="0" hspace="3" align="absmiddle" />Edit</a>
                &nbsp;&nbsp;
                <a href="/?action=video.delete.act&videoKey=' . $v['V_KEY'] . '" class="plain"><img src="images/icons/delete_16x16.png" class="png" width="16" height="16" border="0" hspace="3" align="absmiddle" />Delete</a>
              </div>
            </div>';
    }
  }
  else
  {
    echo '<div class="margin-auto">
            <div class="f_11 bold">You have not uploaded any videos.</div>
            <div style="margin-top:10px;" class="f_10 bold"><a href="/?action=video.upload_form" class="plain"><img src="images/icons/left_up_16x16.png" width="16" height="16" border="0" hspace="4" align="absmiddle" />Upload a video</a></div>
          </div>';
  }
?>