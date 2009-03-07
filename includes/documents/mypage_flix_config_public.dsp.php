<?php
  $fl =& CFlix::getInstance();
  
  $arrFlix = $fl->search(array('USER_ID' => $_USER_ID, 'PERMISSION' => PERM_SLIDESHOW_PUBLIC, 'ORDER_BY' => 'order'));
  $cntArrFlix = count($arrFlix);
  if($_SERVER['QUERY_STRING'] == 'back_refresh')
  {
    echo '<script language="javascript">
            top.frames["flix_configuration_private"].location.href = "/popup/mypage_flix_config_private/";
          </script>';
  }
  
  echo '<script>
          function flixReorder(list)
          {
            var myAjax = new Ajax.Request(
            "/xml_result", 
            {
              method: "post", 
              parameters: "action=slideshow_reorder&" + Sortable.serialize("flixList")
            });
          }
        </script>
        <div style="padding-top:5px;"></div>';
  if($cntArrFlix > 0)
  {
    echo '<div id="flixList" style="padding-left:3px;">';
    
    foreach($arrFlix as $k => $v)
    {
      echo '
              <div class="dragElement" id="flix_' . $v['US_ID'] . '">
                <div style="float:left; padding-right:3px;"><a href="/?action=flix.flix_manage_privacy.act&fastflix=' . $v['US_KEY'] . '&privacy=111&redirect=' . urlencode('/popup/flix_manage_public/') . '"><img src="images/remove.gif" width="14" height="10" vspace="5" border="0" alt="click to make public" title="remove from fotopage" /></a></div>
                <div style="float:left; padding-right:3px; cursor: move;" class="dragHandle"><img src="/images/icons/move_16x16.png" class="png dragHandle" width="16" height="16" vspace="3" border="0" alt="click and drag to rearrange" title="click and drag to rearrange" /></a></div>
                <div style="float:left; padding-top:3px;">' . $v['US_NAME'] . '</div>
                <br clear="all" />
              </div>
          ';
    }
    
    echo '
          </div>
          <script type="text/javascript">
           // <![CDATA[
             Sortable.create("flixList", {tag:"div",only:"dragElement",constraint:"vertical",overlap:"vertical",handle:"dragHandle",onUpdate:function(request){ flixReorder(); } });
           // ]]>
           </script>
          ';
  }
  else
  {
    echo '<div class="bold italic" align="center">No Flix have been selected to be on your FotoPage.</div>';
  }
?>