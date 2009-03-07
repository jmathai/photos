<!-- slideshow page -->

<div>
  <div style="width:300px; padding-top:25px; float:left;">
    <div class="bold">
      <img src="images/gallery_slideshow_icon.gif" width="25" height="31" border="0" align="absmiddle" />
      Gallery of Slideshows Page
    </div>
    <div style="margin-left:25px; margin-top:15px;">
      The gallery of slideshows page will pull public slideshows that you've create on Photagious into your website.  
      You can use the <a href="/?action=flix.manage">slideshow manager</a> to add, remove or reorder slideshows on your page.  
      The slideshow can be viewed in a popup so that the user never leaves your site.
    </div>
  </div>
  <div style="float:left; margin-left:25px;">
    <img src="images/gallery_slideshow_preview.gif" width="223" height="169" border="0" />
  </div>
  <br clear="all" />
  <br/>
  <div style="margin-left:325px;">
    <img src="images/point_up.gif" width="20" height="12" border="0" />
  </div>
  <div style="width:600px; height:35px; background-color:#a1a1a1;">
    <div style="padding-left:330px; padding-top:7px;">
      <a href="javascript:void(0);" onclick="_open('/popup/gallery_of_slideshows/',600, 450, 'ptgPreview', 1);"><img src="images/buttons/preview_green.gif" width="116" height="23" border="0" hspace="2" align="absmiddle" /></a>
      <a href="/?<?php echo $_SERVER['QUERY_STRING']; ?>#codeDialogSlideshowAnchor" onclick="showGalleryCode(6);"><img src="images/buttons/get_code.gif" width="116" height="23" border="0" hspace="1" align="absmiddle" /></a>
    </div>
  </div>
</div>
<a name="codeDialogSlideshowAnchor"></a>
<div id="codeDialogSlideshow">
  <div id="codeContentSlideshow" style="height:400px; margin-top:20px; padding:5px; border:dashed #dddddd 2px; overflow:auto; white-space:nowrap;"></div>
</div>
<script>
  var effSlideshow = new fx.Height('codeDialogSlideshow');
  effSlideshow.hide();
</script>

<br/><br/>

<div>
  <div style="width:300px; padding-top:25px; float:left;">
    <div class="bold">
      <img src="images/gallery_media_icon.gif" width="25" height="31" border="0" align="absmiddle" />
      Media Page
    </div>
    <div style="margin-left:25px; margin-top:15px;">
      The media page will pull public slideshows, tags, photos and videos that you've created on Photagious into your website. 
      You can use the <a href="/?action=flix.manage">slideshow manager</a> to add, remove or reorder slideshows on your page.
    </div>
  </div>
  <div style="float:left; margin-left:25px;">
    <img src="images/gallery_media_preview.gif" width="224" height="265" border="0" />
  </div>
  <br clear="all" />
  <br/>
  <div style="margin-left:325px;">
    <img src="images/point_up.gif" width="20" height="12" border="0" />
  </div>
  <div style="width:600px; height:35px; background-color:#a1a1a1;">
    <div style="padding-left:330px; padding-top:7px;">
      <a href="javascript:void(0);" onclick="_open('/popup/media_page/',600, 450, 'ptgPreview', 1);"><img src="images/buttons/preview_green.gif" width="116" height="23" border="0" hspace="2" align="absmiddle" /></a>
      <a href="/?<?php echo $_SERVER['QUERY_STRING']; ?>#codeDialogMediaAnchor" onclick="showMediaCode(6);"><img src="images/buttons/get_code.gif" width="116" height="23" border="0" hspace="1" align="absmiddle" /></a>
    </div>
  </div>
</div>
<a name="codeDialogMediaAnchor"></a>
<div id="codeDialogMedia">
  <div id="codeContentMedia" style="height:400px; margin-top:20px; padding:5px; border:dashed #dddddd 2px; overflow:auto; white-space:nowrap;"></div>
</div>
<script>
  var effMedia = new fx.Height('codeDialogMedia');
  effMedia.hide();
</script>