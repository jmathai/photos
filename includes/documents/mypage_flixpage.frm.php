<?php
  $p =& CMyPage::getInstance();
  $us=& CUser::getInstance();
  $pageData = $p->page($_USER_ID);
  $userData = $us->find($_USER_ID);
?>

<div style="width:545px; padding-top:5px; padding-bottom:15px;" align="left">
  <div style="padding-bottom:10px;">
    <div class="bold">Configure how your Flix will appear on your FotoPage</div>
  </div>
  
  <div>
    <div style="float:left;" class="bold"><span class="f_off_accent">Step 1</span> How many Flix do you want to appear at one time?</div>
    <div style="margin-top:-2px; float:left;">
      &nbsp;
      <select name="flix_per_page" class="formfield" onChange="location.href='/?action=mypage.flixpage_qty.act&qty='+this.value;" style="width:45px;">
        <?php
          for($i=1; $i<=5; $i++)
          {
            $val = $i * 3;
            $selected = $pageData['P_FLIXQUANTITY'] == $val ? 'SELECTED="true"' : '';
            echo '<option value="' . $val . '" ' . $selected . '>' . $val . '</option>';
          }
        ?>
      </select>
    </div>
    <br />
  </div>
  
  <div class="line_lite" style="padding-top:15px;"></div>
  <div style="padding-top:15px;"></div>
  
  <div>
    <div style="padding-bottom:5px;" class="bold"><span class="f_off_accent">Step 2</span> Update which Flix should show up on your FotoPage and in which order?</div>
    <div style="padding-left:12px; float:left;">
      <div style="width:250px; float:left;" class="f_dark_accent">
        <div><img src="images/tab_private_page.gif" width="112" height="25" border="0" /></div>
        <div style="border:1px solid #39485b; width:250px; height:170px;">
          <iframe name="flix_configuration_private" id="flix_configuration_private" src="/popup/mypage_flix_config_private/" frameborder="0" style="width:248px; height:170px;"></iframe>
        </div>
        <div class="f_8 f_lite" style="padding-top:4px; border:1px solid #39485b; background-color:#39485b; width:250px; height:20px;" align="center">
          <!--<div style="padding-left:3px;"><img src="images/make_flix_public.jpg" width="161" height="24" border="0" /></div>-->
          <div style="padding-left:3px;">&nbsp;</div>
        </div>
      </div>
      <div style="width:20px; float:left;">&nbsp;</div>
      <div style="width:250px; float:left;">
        <div><img src="images/tab_public_page.gif" width="112" height="25" border="0" /></div>
        <div style="border:1px solid #39485b; width:250px; height:170px;">
          <iframe name="flix_configuration_public" id="flix_configuration_public" src="/popup/mypage_flix_config_public/" frameborder="0" style="width:248px; height:170px;"></iframe>
        </div>
        <div class="f_8 f_lite" style="padding-top:4px; border:1px solid #39485b; background-color:#39485b; width:250px; height:20px;" align="center">
          <!--<div style="padding-right:3px;"><img src="images/make_flix_private.jpg" width="181" height="24" border="0" /></div>-->
          <div style="padding-right:3px;">(These Flix will be on your FotoPage)</div>
        </div>
      </div>
    </div>
  </div>
  <br />
</div>

<div style="height:275px"></div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>