<?php
  $fl =& CFlix::getInstance();
  
  //$arrFlix = $fl->flixByStatus($_USER_ID, false, false, false);
  $arrFlix = $fl->search(array('USER_ID' => $_USER_ID, 'PERMISSION' => PERM_SLIDESHOW_PRIVATE, 'ORDER_BY' => 'title'));
  
  if($_SERVER['QUERY_STRING'] == 'back_refresh')
  {
    echo '<script language="javascript">
            top.frames["flix_configuration_public"].location.href = "/popup/mypage_flix_config_public/";
          </script>';
  }
  
  echo '<div style="padding-top:5px;"></div>';
  if(count($arrFlix) > 0)
  {
    foreach($arrFlix as $v)
    {
      echo '<div style="padding-bottom:5px; padding-left:5px; float:none;">
              <div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_privacy.act&fastflix=' . $v['US_KEY'] . '&privacy=331&redirect=' . urlencode('/popup/mypage_flix_config_public/') . '" target="flix_configuration_public" title="add to my fotopage"><img src="images/add.gif" width="14" height="14" border="0" alt="add to my fotopage" /></a></div>
              <div style="float:left;">' . $v['US_NAME'] . '</div>
            </div>
            <br clear="all" />';
    }
  }
  else
  {
    echo '<div class="bold italic" align="center">You have no private Flix.</div>';
  }
?>