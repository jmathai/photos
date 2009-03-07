<div style="height:20px;">
  <div style="width:224px; height:19px; background-color:#ebebeb; border-left:solid 1px #a7a7a7; border-top:solid 1px #a7a7a7; float:left;">
    <div style="padding-top:2px; padding-left:5px;">
      <div style="float:left;"><img src="images/toolbox_red.gif" width="17" height="13" hspace="4" border="0" /></div>
      <div style="float:left;" class="f_8 f_dark">Tool Box &nbsp;&nbsp; (<span id="toolboxCount">0</span> items</a>)</div>
    </div>
  </div>
  <div style="width:1px; float:left;"><img src="images/toolbox_spacer_1.gif" height="20" width="1" border="0" /></div>
  <div style="width:593px; height:20px; background-image:url(images/toolbox_spacer_2.gif); float:left;"></div>
  <div style="width:1px; float:left;"><img src="images/toolbox_spacer_3.gif" height="20" width="1" border="0" /></div>
</div>

<div id="myToolbox">
  <div id="myToolboxStart"></div>
  <div id="myToolboxEnd"></div>
</div>

<script type="text/javascript">
  tb = new Toolbox('foto');
  tb.load();
</script>

<div style="height:24px; border-left:solid 1px #3b3a3b; border-right:solid 1px #3b3a3b;" id="toolboxOverlayOptions">
  <div style="width:450px; height:24px; float:left; background-color:#959595; border-bottom:solid 1px #3b3a3b;" id="toolbarOverlayCeiling">
    <div style="float:left; width:101px; height:100%; background-color:#b5b5b5;">
      <a href="javascript:tb.deleteAll('foto');" class="plain">
        <img src="images/tb_remove_all.gif" width="81" height="22" hspace="10" border="0" />
      </a>
    </div>
    <div style="float:left; width:348px; height:24px; border-top:solid 1px #3b3a3b; border-left:solid 1px #3b3a3b;">
      &nbsp;
      <a href="javascript:void(0);" onclick="selectFotos(tb.itemsT.length, 'tagFotosForm');" class="plain">
        <img src="images/tb_tag.gif" width="38" height="22" border="0" hspace="5"  />
      </a>
      <a href="javascript:void(selectFotos(tb.itemsT.length, 'unTagFotosForm'));" class="plain">
        <img src="images/tb_untag.gif" width="48" height="22" border="0" hspace="5"  />
      </a>
      <a href="javascript:void(selectFotos(tb.itemsT.length, 'privacyFotosForm'));" class="plain">
        <img src="images/tb_privacy.gif" width="56" height="22" border="0" hspace="5"  />
      </a>
      <a href="javascript:void(selectFotos(tb.itemsT.length, 'deleteFotosForm'));" class="plain">
        <img src="images/tb_delete.gif" width="48" height="22" border="0" hspace="5"  />
      </a>
      <a href="javascript:void(selectFotos(tb.itemsT.length, 'sendToFacebookForm'));" class="plain">
        <img src="images/tb_facebook.png" width="62" height="22" border="0" hspace="5"  />
      </a>
    </div>
  </div>
  <div style="width:367px; height:24px; float:left; background-color:#565659; border-left:solid 1px #3b3a3b; border-bottom:solid 1px #3b3a3b;" id="toolbarShareOverlayCeiling">
    <div class="center">
      <a href="javascript:void(selectFotos(tb.itemsT.length, 'slideshowFromToolbox'));" title="create a slideshow" class="plain">
        <img src="images/tb_create_slideshow.gif" width="81" height="22" border="0" hspace="10" vspace="0" />
      </a>
      <a href="/?action=printing.redirect.act&opts=live||" title="order prints" class="plain">
        <img src="images/tb_order_prints.gif" width="85" height="22" border="0" hspace="10" vspace="0" />
      </a>
    </div>
  </div>
</div>
<div>
  <div id="fotoOverlayForm" style="display:none; padding:10px; margin-bottom:15px; width:430px; height:140px; background-color:#959595; border-left:solid 1px #3b3a3b; border-bottom:solid 1px #3b3a3b; border-right:solid 1px #3b3a3b; float:left;" class="f_white"></div>
  <div id="fotoShareOverlayForm" style="display:none; padding:10px; margin-bottom:15px; width:507px; height:140px; background-color:#565659; border-left:solid 1px #3b3a3b; border-bottom:solid 1px #3b3a3b; border-right:solid 1px #3b3a3b; float:right;" class="f_white">test</div>
  <br clear="all" />
</div>
