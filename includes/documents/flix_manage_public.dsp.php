<?php
  $fl =& CFlix::getInstance();
  
  $arrFlix = $fl->search(array('MODE' => 'USER', 'USER_ID' => $_USER_ID, 'PERMISSION' => PERM_SLIDESHOW_PUBLIC, 'ORDER_BY' => 'order'));
  $cntArrFlix = count($arrFlix);
  if($_SERVER['QUERY_STRING'] == 'back_refresh')
  {
    echo '<script language="javascript">
            top.frames["flix_configuration_private"].location.href = "/popup/flix_manage_private/";
          </script>';
  }
  
  echo '<div style="padding-top:5px;"></div>';
  if($cntArrFlix > 0)
  {
    foreach($arrFlix as $k => $v)
    {
      if($cntArrFlix > 1)
      {
        echo '<div style="padding-bottom:5px; padding-left:5px; float:none;">
                <div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_manage_privacy.act&fastflix=' . $v['US_KEY'] . '&privacy=111&redirect=' . urlencode('/popup/flix_manage_public/') . '"><img src="images/remove.gif" width="14" height="10" vspace="3" border="0" alt="click to make public" title="remove from personal page" /></a></div>';
        
        if($k > 1)
        {
          echo '<div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_reorder.act&fastflix=' . $v['US_KEY'] . '&move=top&redirect=' . urlencode('/popup/flix_manage_public/') . '"><img src="images/arrow_move_top.gif" width="15" height="15" border="0" alt="reorder list: move to top" title="reorder list: move to top" /></a></div>';
        }
        else
        {
          echo '<div style="float:left; padding-right:3px;"><img src="images/arrow_move_top_grey.gif" width="15" height="15" border="0" /></div>';
        }
        
        echo '  <div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_reorder.act&fastflix=' . $v['US_KEY'] . '&move=up&redirect=' . urlencode('/popup/flix_manage_public/') . '"><img src="images/arrow_move_up.gif" width="15" height="15" border="0" alt="reorder list: move up" title="reorder list: move up" /></a></div>
                <div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_reorder.act&fastflix=' . $v['US_KEY'] . '&move=down&redirect=' . urlencode('/popup/flix_manage_public/') . '"><img src="images/arrow_move_down.gif" width="15" height="15" border="0" alt="reorder list: move down" title="reorder list: move down" /></a></div>';
        
        if($k < ($cntArrFlix-2))
        {
          echo '<div style="float:left; padding-right:6px;"><a href="/?action=flix.flix_reorder.act&fastflix=' . $v['US_KEY'] . '&move=bottom&redirect=' . urlencode('/popup/flix_manage_public/') . '"><img src="images/arrow_move_bottom.gif" width="15" height="15" border="0" alt="reorder list: move to bottom" title="reorder list: move to bottom" /></a></div>';
        }
        else
        {
          echo '<div style="float:left; padding-right:6px;"><img src="images/arrow_move_bottom_grey.gif" width="15" height="15" border="0" /></div>';
        }
        
        echo '  <div style="float:left;">' . str_mid($v['US_NAME'], 30) . ' (' . $v['US_ORDER'] . ') </div>
              </div>
              <br clear="all" />';
      }
      else
      {
        echo '<div style="padding-bottom:5px; padding-left:5px; float:none;">
                <div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_manage_privacy.act&fastflix=' . $v['US_KEY'] . '&privacy=111&redirect=' . urlencode('/popup/flix_manage_public/') . '"><img src="images/remove.gif" width="14" height="10" vspace="3" border="0" alt="click to make public" title="remove from personal page" /></a></div>
                <div style="float:left; padding-right:3px;"><img src="images/arrow_move_top_grey.gif" width="15" height="15" border="0" /></div>
                <div style="float:left; padding-right:3px;"><img src="images/arrow_move_up_grey.gif" width="15" height="15" border="0" /></div>
                <div style="float:left; padding-right:3px;"><img src="images/arrow_move_down_grey.gif" width="15" height="15" border="0" /></div>
                <div style="float:left; padding-right:6px;"><img src="images/arrow_move_bottom_grey.gif" width="15" height="15" border="0" /></div>
                <div style="float:left;">' . str_mid($v['US_NAME'], 30) . ' (' . $v['US_ORDER'] . ')</div>
              </div>
              <br clear="all" />';
      }
    }
  }
  else
  {
    echo '<div class="bold italic" align="center">No slideshows have been selected to be on your personal page.</div>';
  }
?>