<?php
  include_once './../init_constants.php';
  $userKey = htmlspecialchars($_GET['userKey']);
  $pageSize= intval($_GET['pageSize']);
  $divId   = isset($_GET['divId']) ? htmlspecialchars($_GET['divId']) : 'ptgSlideshowDiv';
?>
/*
* This code provided by photagious.com
* Copy and paste this code to embed your photos/slideshows into your blog or website
* Generated on <?php echo date(DATE_RFC850, time()); ?>
*/

if(document.getElementById('__PTG') == undefined)
{
  // insertion via dom does not work in safari 2.0
  document.write('<script id="__PTG" src="http://<?php echo FF_SERVER_NAME; ?>/js/api.js"></script>');
}

var ptg = new PTG('<?php echo $userKey; ?>');
var ptgPage = 0;
var ptgPageSize = <?php echo $pageSize; ?>;
var ptgImageWidth = 120;
var ptgImageHeight= 70;

function __getSlideshows(page)
{
  var offset = (page * ptgPageSize) - ptgPageSize;
  ptgPage = page;
  ptg.slideshow.search({'order':'order','limit':ptgPageSize,'offset':offset}, 'showSlideshows');
}

function __showSlideshows(data)
{
  var html = '';
  var pages = Math.ceil(data[0].totalRows / ptgPageSize);

  while(slideshow = ptg.result.next(data))
  {
    html += '<div style="float:left; width:' + (ptgImageWidth + 20) + 'px; text-align:center;"><a href="javascript:void(window.open(\'' + ptg.slideshow.popup(slideshow.key) + '\',\'ptg\',\'width='+slideshow.width+',height='+slideshow.height+',scrollbars=no\'));">' + ptg.html.customImageTag(slideshow.thumbnail.path, slideshow.thumbnail.key, ptgImageWidth, ptgImageHeight, {'width':ptgImageWidth,'height':ptgImageHeight,'vspace':'10','hspace':'10','border':'0'}) + '<br/>' + slideshow.name + '</a></div>';
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
        html += '<a href="javascript:getSlideshows(' + (i+1) + ');">' + (i+1) + '</a> ';
      }
    }
   html += '</div>';
      
  }
  
  html +=  '<div>'
       +  '  <a href="' + ptg.host + '"><img src="' + ptg.host + '/images/photagious_pb.gif" width="94" height="20" border="0" vspace="5" alt="Upload photos and create slideshows using Photagious"/></a>'
       +  '</div>';

  document.getElementById('<?php echo $divId; ?>').innerHTML = html;
}

__getSlideshows(1);