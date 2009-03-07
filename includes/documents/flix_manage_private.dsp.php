<?php
  $fl =& CFlix::getInstance();
  
  $arrFlix = $fl->search(array('MODE' => 'USER', 'USER_ID' => $_USER_ID, 'PERMISSION' => PERM_SLIDESHOW_PRIVATE, 'ORDER_BY' => 'title'));

  if($_SERVER['QUERY_STRING'] == 'back_refresh')
  {
    echo '<script language="javascript">
            top.frames["flix_configuration_public"].location.href = "/popup/flix_manage_public/";
          </script>';
  }
  
  echo '<div style="padding-top:5px;"></div>';
  if(count($arrFlix) > 0)
  {
    foreach($arrFlix as $k => $v)
    {
      echo '<div style="padding-bottom:5px; padding-left:5px; float:none;">
              <div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_manage_privacy.act&fastflix=' . $v['US_KEY'] . '&privacy=' . ($v['US_PRIVACY'] & PERM_SLIDESHOW_PUBLIC) . '&redirect=' . urlencode('/popup/flix_manage_public/') . '" target="flix_configuration_public" title="add to my personal page"><img src="images/add.gif" width="14" height="14" border="0" alt="add to my personal page" /></a></div>
              <div style="float:left;">' . str_mid($v['US_NAME'], 50) . '</div>
            </div>
            <br clear="all" />';
    }
  }
  else
  {
    echo '<div class="bold italic" align="center">You have no private slideshows.</div>';
  }
?>