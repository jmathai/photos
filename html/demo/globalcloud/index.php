<html lang="en">

  <head>
    <link href="extra/styles.css" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="extra/prototype.lite.js"></script>
    <script type="text/javascript" src="extra/moo.fx.js"></script>
    
    <!-- PTG -->
    <!-- PTG -->
    <script type="text/javascript" id="__PTG" src="http://www.photagious.com/js/api.js"></script>
    <script type="text/javascript">
      var ptg = new PTG("10fbbb7d87826e9301c7323cb9c5ddbc");
      
      var hash, pContentEff, sContentEff;
      
      function init()
      {
        pContentEff  = new fx.Opacity('photo-preview-content');
        sContentEff = new fx.Opacity('slideshow-preview-content');
      
        showPhotos({'order':'dateCreated'});
        showSlideshows({});
      }
      
      function showPhotos(params)
      {
        $('photo-preview-loader').style.display = 'block';
        
        pContentEff.hide();
        
        if(params['limit'] == undefined){ params['limit'] = 10; }
        ptg.image.search(params, 'showPhotosRsp');
      }
      
      function showPhotosRsp(data)
      {
        var html = '';
        x = 0;
        while(image = ptg.result.next(data))
        {
          html += '<a href="detail.php?photoId=' + image['id'] + '">' + ptg.html.customImageTag(image['thumbnailPath'], image['key'], 150, 100, {'width':'150','height':'100','hspace':'5','vspace':'5','border':'0'}) + '</a>';
        }
        
        if($('photo-search-field').value != ''){ html += '<div class="right bold plain"><a href="presentation.php?tags=' + $('photo-search-field').value + '"><img src="extra/images_24x24.png" width="24" height="24" hspace="3" border="0" align="absmiddle" />Create a presentation with these photos (' + data['misc']['totalRows'] + ')</a></div>'; }
        
        $('photo-preview-content').innerHTML = html;
        $('photo-preview-loader').style.display = 'none';
        
        pContentEff.toggle();
      }
      
      function showSlideshows(params)
      {
        $('slideshow-preview-loader').style.display = 'block';
        sContentEff.hide();
        
        if(params['limit'] == undefined){ params['limit'] = 2; }
        ptg.slideshow.search(params, 'showSlideshowsRsp');
      }
      
      function showSlideshowsRsp(data)
      {
        var html = '';
        while(show = ptg.result.next(data))
        {
          html += '<div><a href="' + ptg.slideshow.link(show.key) + '">' + ptg.html.customImageTag(show.thumbnail.path, show.thumbnail.key, 300, 200, {'width':'300','height':'200','hspace':'5','vspace':'5','border':'0'}) + '</a></div>';
        }
        
        $('slideshow-preview-content').innerHTML = html;
        $('slideshow-preview-loader').style.display = 'none';
        
        sContentEff.toggle();
      }
    </script>
  </head>
  
  <body onload="init();">
    <div id="container">
      <div class="bold f_12">Most Recent Photos &nbsp;&nbsp;&nbsp; <input type="text" id="photo-search-field" class="search-field" /> <a href="javascript:void(0);" onclick="showPhotos({'tags':$('photo-search-field').value});"><img src="extra/search_16x16.png" width="16" height="16" border="0" hspace="3" align="absmiddle" /></a></div>
      <div class="preview">
        <div id="photo-preview-loader" class="loader">Please wait...</div>
        <div id="photo-preview-content" class="content"></div>
      </div>
      <br/><br/>
      <div class="bold f_12">Most Recent Slideshows &nbsp;&nbsp;&nbsp; <input type="text" id="slideshow-search-field" class="search-field" /> <a href="javascript:void(0);" onclick="showSlideshows({'TAGS':$('slideshow-search-field').value});"><img src="extra/search_16x16.png" width="16" height="16" border="0" hspace="3" align="absmiddle" /></a></div>
      <div class="preview">
        <div id="slideshow-preview-loader" class="loader photo-loader">Please wait...</div>
        <div id="slideshow-preview-content" class="content"></div>
      </div>
    </div>
  </body>
</html>