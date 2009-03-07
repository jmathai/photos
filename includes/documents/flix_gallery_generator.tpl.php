<!--
  This code provided by photagious.com
  Copy and paste this code to embed your photos/slideshows into your blog or website
  Generated on {DATE}
-->
<script type="text/javascript" id="__PTG" src="http://{SERVER_NAME}/js/api.js"></script>
<script type="text/javascript">
  var ptg = new PTG('{USER_KEY}');
  var ptgPage = 0;
  var ptgPageSize = {PAGE_SIZE};
  var ptgImageWidth = 150;
  var ptgImageHeight= 90;
  
  function __getSlideshows(page)
  {
    var offset = (page * ptgPageSize) - ptgPageSize;
    ptgPage = page;
    ptg.slideshow.search({'order':'order','limit':ptgPageSize,'offset':offset}, '__showSlideshows');
  }
  
  function __showSlideshows(data)
  {
    var html = '';
    var pages = Math.ceil(data[0].totalRows / ptgPageSize);

    while(slideshow = ptg.result.next(data))
    {
      html += '<div style="float:left; width:' + (ptgImageWidth + 20) + 'px; height:' + (ptgImageHeight + 55) + 'px; text-align:center;"><a href="javascript:void(window.open(\'' + ptg.slideshow.popup(slideshow.key) + '\',\'ptg\',\'width='+slideshow.width+',height='+slideshow.height+',scrollbars=no\'));">' + ptg.html.customImageTag(slideshow.thumbnail.path, slideshow.thumbnail.key, ptgImageWidth, ptgImageHeight, {'width':ptgImageWidth,'height':ptgImageHeight,'vspace':'10','hspace':'10','border':'0','style':'border:solid 1px #000'}) + '</a><br/><a href="javascript:void(window.open(\'' + ptg.slideshow.popup(slideshow.key) + '\',\'ptg\',\'width='+slideshow.width+',height='+slideshow.height+',scrollbars=no\'));">' + slideshow.name + '</a></div>';
    }
    
    html += '<br clear="all" />';
    
    if(pages > 1)
    {
      html += '<div>You are viewing page ' + ptgPage + ' of ' + pages + '&nbsp;&nbsp;&nbsp;&nbsp;';
      for(i=0; i<pages; i++)
      {
        if(ptgPage == (i+1))
        {
          html += (i+1) + ' ';
        }
        else
        {
          html += '<a href="javascript:__getSlideshows(' + (i+1) + ');">' + (i+1) + '</a> ';
        }
      }
     html += '</div>';
        
    }
    
    document.getElementById('ptgSlideshowDiv').innerHTML = html;
  }
  
  __getSlideshows(1);
</script>
<div id="ptgSlideshowDiv"></div>
<div align="center">
  <a href="http://www.photagious.com/" title="Upload photos and create slideshows using Photagious"><img src="http://www.photagious.com/images/photagious_pb.gif" width="94" height="20" border="0" vspace="5" alt="Upload photos and create slideshows using Photagious"/></a>
</div>
