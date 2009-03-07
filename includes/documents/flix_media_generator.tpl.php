<!--
  This code provided by photagious.com
  Copy and paste this code to embed your photos/slideshows into your blog or website
  Generated on {DATE}
-->
<div id="ptgContainer">
  <div style="font-weight:bold;">Recent Slideshows</div>
  <div id="ptgSlideshows" style="padding:5px 0px 15px 0px; border-bottom:solid 1px #efefef;"></div>
  
  <div  style="font-weight:bold;">Random Tags</div>
  <div id="ptgTags" style="padding:5px 0px 15px 0px;"></div>
  
  <div style="font-weight:bold;"><a name="relatedPhotos"></a>Related Photos <span id="photoTagField"></span></div>
  <div id="ptgPhotosRelated" style="padding:5px 0px 15px 0px;"></div>
  
  <div style="font-weight:bold;">Related Slideshows <span id="slideshowTagField"></span></div>
  <div id="ptgSlideshowsRelated" style="padding:5px 0px 15px 0px;"></div>
  
  <div  style="font-weight:bold;"><a name="allTags"></a>All Tags</div>
  <div id="ptgAllTags" style="padding:5px 0px 15px 0px;"></div>
  
  <div align="center">
    <a href="http://www.photagious.com/" title="Upload photos and create slideshows using Photagious"><img src="http://www.photagious.com/images/photagious_pb.gif" width="94" height="20" border="0" vspace="5" alt="Upload photos and create slideshows using Photagious"/></a>
  </div>
  
  <script type="text/javascript" id="__PTG" src="http://{SERVER_NAME}/js/api.js"></script>
  <script type="text/javascript">
    var ptg = new PTG('{USER_KEY}');
    var slideshowPage = 1;
    var slideshowPageSize = 4;
    var photoPageSize = 9;
    var photoPage = 1;
    var tags = '';
    var lastTags = ' ';
    
    function __getSlideshows()
    {
      offset = (slideshowPage - 1) * slideshowPageSize;
      ptg.slideshow.search({'privacy':'1','limit':slideshowPageSize,'offset':offset}, '__getSlideshowsRsp');
    }
    
    function __getSlideshowsRsp(data)
    {
      var slideshow;
      var html = '';
      var pageCount = Math.ceil(ptg.result.totalRows(data) / slideshowPageSize);
      var pagesToDisplay = 6;
      var startPage = slideshowPage - (pagesToDisplay/2);
      if((startPage+pagesToDisplay) >= pageCount)
      {
        startPage = pageCount - pagesToDisplay;
      }
      
      if(startPage < 1)
      {
        startPage = 1;
      }
      var endPage = (startPage + pagesToDisplay) > pageCount ? pageCount : (startPage + pagesToDisplay);
      
      while(slideshow = ptg.result.next(data))
      {
        html += '<div style="width:230px; margin-bottom:10px; text-align:center; float:left;"><a href="javascript:void(0);" onclick="__popup(\''+ptg.slideshow.popup(slideshow['key']+'\', \''+slideshow['width']+'\', \''+slideshow['height']) + '\');">'+ptg.html.customImageTag(slideshow['thumbnail']['path'], slideshow['thumbnail']['key'], 180, 120, {'border':'0','width':'180','height':'120','style':'border:solid 4px #dddddd;'})+'</a><br/>'+slideshow['name']+'</div>';
      }
      html += '<br clear="all" />'
            + '<div style="text-align:right; margin-top:10px;">page&nbsp;';
      
      for(var i=startPage; i<=endPage; i++)
      {
        if(i != slideshowPage)
        {
          html += '<a href="javascript:void(0);" onclick="slideshowPage='+i+'; __getSlideshows();">'+i+'</a> ';
        }
        else
        {
          html += i + ' ';
        }
      }
      
      html += '</div>';
      
      __getTags();
      
      document.getElementById('ptgSlideshows').innerHTML = html;
    }
    
    function __getTags()
    {
      ptg.user.getTags({'limit':'25','order':'random'}, '__getTagsRsp');
    }
    
    function __getTagsRsp(data)
    {
      var tag;
      var html = '';
      while(tag = ptg.result.next(data))
      {
        html += '<a href="javascript:void(0);" onclick="photoPage=1; tags=\''+tag['tag']+'\'; __tagDisplay();">'+tag['tag']+'</a>&nbsp; ';
      }
      html += '<br clear="all"><div style="text-align:right; margin-top:10px;"><a href="'+location.href+'#allTags">View all tags</a></div>';
      
      document.getElementById('ptgTags').innerHTML = html;
    }
    
    function __getAllTags()
    {
      ptg.user.getTags({'order':'alpha'}, '__getAllTagsRsp');
    }
    
    function __getAllTagsRsp(data)
    {
      var tag;
      var html = '';
      var step = (data[0]['maximum'] - data[0]['minimum']) / 5;
      while(tag = ptg.result.next(data))
      {
        html += '<a href="'+location.href+'#relatedPhotos" onclick="photoPage=1; tags=\''+tag['tag']+'\'; __tagDisplay();" style="font-size:' + __tagSize(tag['tagWeight'], step) + 'px; line-height:20px;">' + tag['tag'] + '</a>&nbsp; ';
      }
      
      document.getElementById('ptgAllTags').innerHTML = html;
    }
    
    function __tagDisplay()
    {
      if(arguments.length>0)
      {
        tags = arguments[0];
      }
      
      if(tags != lastTags)
      {
        document.getElementById('photoTagField').innerHTML = 'Tagged with ' + tags;
        document.getElementById('slideshowTagField').innerHTML = 'Tagged with ' + tags;
        __getRelatedPhotos();
        __getRelatedSlideshows();
        __getTags();
      }
      
      lastTags = tags;
    }
    
    function __getRelatedPhotos()
    {
      offset = (photoPage - 1) * photoPageSize;
      ptg.image.search({'limit':photoPageSize,'offset':offset,'tags':tags}, '__getRelatedPhotosRsp');
    }
    
    function __getRelatedPhotosRsp(data)
    {
      var image;
      var html = '';
      
      if(ptg.result.totalRows(data) > 0)
      {
        var pageCount = Math.ceil(ptg.result.totalRows(data) / photoPageSize);
        var pagesToDisplay = 6;
        var startPage = photoPage - (pagesToDisplay/2);
        if((startPage+pagesToDisplay) >= pageCount)
        {
          startPage = pageCount - pagesToDisplay;
        }
        
        if(startPage < 1)
        {
          startPage = 1;
        }
        var endPage = (startPage + pagesToDisplay) > pageCount ? pageCount : (startPage + pagesToDisplay);
        
        html += '<div>';
        while(image = ptg.result.next(data))
        {
          html += '<div style="float:left; padding:10px;"><a href="javascript:void(0);" onclick="__popup(\''+ptg.html.customImageLockSrc(image['thumbnailPath'], image['key'], image['width'], image['height'], 640, 480)+'\', \''+640+'\', \''+480 + '\');">'+ptg.html.customImageTag(image['thumbnailPath'], image['key'], 115, 50, {'width':'115','height':'50','border':'0','style':'border:solid 1px #dddddd;'})+'</a></div>';
        }
        
        html += '<br clear="all" />'
              + '<div style="text-align:right; margin-top:10px;">page&nbsp;';
        
        for(var i=startPage; i<=endPage; i++)
        {
          if(i != photoPage)
          {
            html += '<a href="javascript:void(0);" onclick="photoPage='+i+'; __getRelatedPhotos();">'+i+'</a> ';
          }
          else
          {
            html += i + ' ';
          }
        }
        
        html += '</div>';
      }
      else
      {
        html = 'There are no photos tagged with ' + tags + '.';
      }
      
      document.getElementById('ptgPhotosRelated').innerHTML = html;
    }
    
    function __getRelatedSlideshows()
    {
      ptg.slideshow.search({'limit':'4','tags':tags}, '__getRelatedSlideshowsRsp');
    }
    
    function __getRelatedSlideshowsRsp(data)
    {
      var slideshow;
      var html = '';
      if(ptg.result.totalRows(data) > 0)
      {
        html += '<div>';
        while(slideshow = ptg.result.next(data))
        {
          html += '<div style="width:115px; text-align:center; float:left;"><a href="javascript:void(0);" onclick="__popup(\''+ptg.slideshow.popup(slideshow['key']+'\', \''+slideshow['width']+'\', \''+slideshow['height']) + '\');">'+ptg.html.imageTag(slideshow['thumbnail']['path'], {'border':'0','style':'border:solid 4px #dddddd;'})+'</a></div>';
        }
        
        html += '<br clear="all" /></div>';
      }
      else
      {
        html = 'There are no slideshows tagged with ' + tags + '.';
      }
      
      document.getElementById('ptgSlideshowsRelated').innerHTML = html;
    }
    
    function __popup(url, width, height)
    {
      var screenW = screen.width; // screen width
      var screenH = screen.height; // screen height
      var positionL = (screenW - width)/2;
      var positionT = (screenH - height)/2;
      var ptgWin = _ff_fastflix___popup = window.open(url, "ptgWin", "width="+width+",height="+height+",left="+positionL+",top="+positionT+",location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,fullscreen=no");
      ptgWin.focus();
    }
    
    function __tagSize(weight, step)
    {
      var sizes = [10,12,14,16,18];
      var __tagSize= 0;
      if(weight < step)
      {
        __tagSize = sizes[0];
      }
      else
      if(weight < (step * 2))
      {
        __tagSize = sizes[1];
      }
      else
      if(weight < (step * 3))
      {
        __tagSize = sizes[2];
      }
      else
      if(weight < (step * 4))
      {
        __tagSize = sizes[3];
      }
      else
      {
        __tagSize = sizes[4];
      }
      
      return __tagSize;
    }
    
    function __ptgInit()
    {
      __getSlideshows();
      __getRelatedPhotos();
      __getRelatedSlideshows();
      __getAllTags();
    }
    
    __ptgInit();
  </script>
</div>