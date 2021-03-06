<?php
  include_once '../../../../init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CSession.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_DOCROOT . '/init_session.php';
  
  include_once PATH_CLASS . '/CUser.php';
  include_once PATH_CLASS . '/CUserManage.php';
  $us =& CUser::getInstance();
  $usm=& CUserManage::getInstance();
  
  $userData = $us->find($_USER_ID);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <title>Photagious</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta content="noindex, nofollow" name="robots">
    <link rel="stylesheet" type="text/css" href="/css/basic.css">
    <script src="common/fck_dialog_common.js" type="text/javascript"></script>
    <script src="fck_ffms/fck_ffms.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/prototype/prototype.js"></script>
    <script type="text/javascript" src="/js/javascript.js"></script>
    <script type="text/javascript" id="__PTG" src="http://<?php echo FF_SERVER_NAME; ?>/js/api.js"></script>
    <script type="text/javascript">
      <?php
        echo 'var ptg = new PTG("' . $userData['U_KEY'] . '");';
      ?>
      
      srcArray = new Array();
      sSrc = '';
      mSrc = '';
      lSrc = '';
      section = 'photos';
      tags = '';
      
      function loadPhotos()
      {
        tags = arguments.length > 0 ? arguments[0] : '';
        var offset = arguments.length > 1 ? arguments[1] : 0;
        var params = {'tags':tags,'order':'dateTaken','offset':offset,'limit':15};
        $('loader').show();
        ptg.image.search(params, 'loadPhotosRsp');
      }
      
      function loadPhotosRsp(data)
      {
        var lastPhoto, _htmlPhotos, _html, cnt, previousOffset, nextOffset;
        
        cnt = 0;
        _htmlPhotos = '';
        while(image = ptg.result.next(data))
        {
          if(i != 'misc')
          {
            srcArray[cnt] = '<a href="/handler/photo/' + image.key + '/">'+ptg.html.customImageTag(image.thumbnailPath, image.key, 75, 75, {'border':'0','width':'75','height':'75','hspace':'0','vspace':'0'})+'</a>';
            _htmlPhotos += '<a href="javascript:void(0);" onclick="embedDialog(\'photo\', \''+image.key+'\', \''+image.thumbnailPath+'\', '+image.width+', '+image.height+', '+image.rotation+'); window.focus();"><img src="/photos'+image.thumbnailPath+'" vspace="5" hspace="8" border="0" /></a>';
            cnt++;
          }
        }
        
        lastPhoto = data.misc.offset + cnt;
        
        _html = '<div style="margin-bottom:3px;">Showing ' + cnt + ' photos ';
        if(tags != '')
        {
          _html += ' tagged with ' + tags + ' ';
        }
        
        // previous
        if(data.misc.offset > 0)
        {
          previousOffset = data.misc.offset - data.misc.limit;
          _html += ' &nbsp;&nbsp;-&nbsp;&nbsp; <a href="javascript:void(0);" onclick="loadPhotos(\''+tags.replace("'", '')+'\', \''+previousOffset+'\');">Previous</a>';
        }
        
        // next
        if(data.misc.totalRows > lastPhoto)
        {
          if(data.misc.offset > 0)
          {
            _html += ' or ';
          }
          else
          {
            _html += ' &nbsp;&nbsp;-&nbsp;&nbsp; ';
          }
          nextOffset = data.misc.offset + data.misc.limit;
          _html += '<a href="javascript:void(0);" onclick="loadPhotos(\''+tags.replace("'", '')+'\', \''+nextOffset+'\');">Next</a>';
        }
        
        _html += '</div>' + _htmlPhotos;
        $('ePreviewCell').update(_html);
        $('loader').hide();
      }
      
      function loadSlideshows()
      {
        var tags = arguments.length > 0 ? arguments[0] : '';
        var params = {'tags':tags};
        section = 'slideshows';
        
        if(tags.length == 0)
        {
          params.limit = 15;
        }
        ptg.slideshow.search(params, 'loadSlideshowsRsp');
      }
      
      function loadSlideshowsRsp(data)
      {
        var _html = '<div style="margin-bottom:3px;">Showing {NUM} slideshows</div>';
        var cnt = 0;
        var lastPhoto, _htmlSlideshows, _html, previousOffset, nextOffset;
        
        _htmlSlideshows = '';
        while(slideshow = ptg.result.next(data))
        {
          if(i != 'misc')
          {
            srcArray[cnt] = '<div class="flix_border"><a href="/slideshow?' + slideshow.thumbnail.key + '">'+ptg.html.imageTag(slideshow.thumbnail.path, {'border':'0','hspace':'0','vspace':'0'})+'</a></div>';
            _htmlSlideshows += '<div style="float:left; margin:5px;"><div class="flix_border"><a href="javascript:FCK.InsertHtml(srcArray['+parseInt(cnt)+']); window.focus();" title="Embed a link to ' + safe(slideshow.name) + '"><img src="/photos'+slideshow.thumbnail.path+'" vspace="0" hspace="0" border="0" /></a></div></div>';
            cnt++;
          }
        }
        
        lastPhoto = data.misc.offset + cnt;
        
        _html = '<div style="margin-bottom:3px;">Showing ' + cnt + ' slideshows ';
        if(tags != '')
        {
          _html += ' tagged with ' + tags + ' ';
        }
        
        // previous
        if(data.misc.offset > 0)
        {
          previousOffset = data.misc.offset - data.misc.limit;
          _html += ' &nbsp;&nbsp;-&nbsp;&nbsp; <a href="javascript:void(0);" onclick="loadSlideshows(\''+tags.replace("'", '')+'\', \''+previousOffset+'\');">Previous</a>';
        }
        
        // next
        if(data.misc.totalRows > lastPhoto)
        {
          if(data.misc.offset > 0)
          {
            _html += ' or ';
          }
          else
          {
            _html += ' &nbsp;&nbsp;-&nbsp;&nbsp; ';
          }
          nextOffset = data.misc.offset + data.misc.limit;
          _html += '<a href="javascript:void(0);" onclick="loadSlideshows(\''+tags.replace("'", '')+'\', \''+nextOffset+'\');">Next</a>';
        }
        
        _html += '</div>' + _htmlSlideshows;
        $('ePreviewCell').update(_html);
        $('loader').hide();
      }
      
      function embedDialog(type)
      {
        var _html     = '';
        
        switch(type)
        {
          case 'photo':
            var dWidth    = 250;
            var dHeight   = 150;          
            var key     = arguments[1];
            var tPath   = arguments[2];
            var oWidth  = arguments[3];
            var oHeight = arguments[4];
            var oRotation= arguments[5];
            //FCK.InsertHtml(srcArray['+parseInt(cnt)+']);
            sSrc = '<a href="/handler/photo/' + key + '/">'+ptg.html.customImageLockTag(tPath, key, oWidth, oHeight, 150, 150, oRotation, {'border':'0','hspace':'0','vspace':'0'})+'</a>'
            mSrc = '<a href="/handler/photo/' + key + '/">'+ptg.html.customImageLockTag(tPath, key, oWidth, oHeight, 350, 350, oRotation, {'border':'0','hspace':'0','vspace':'0'})+'</a>'
            lSrc = '<a href="/handler/photo/' + key + '/">'+ptg.html.customImageLockTag(tPath, key, oWidth, oHeight, 525, 525, oRotation, {'border':'0','hspace':'0','vspace':'0'})+'</a>'
            _html = '<div class="bold">What size photo would you like to embed?</div><br/>'+ptg.html.imageTag(tPath, {width:'75',height:'75',hspace:'5',align:'left'}) + '<a href="javascript:void(0);" onclick="FCK.InsertHtml(sSrc);">small</a><br/><a href="javascript:void(0);" onclick="FCK.InsertHtml(mSrc);">medium</a><br/><a href="javascript:void(0);" onclick="FCK.InsertHtml(lSrc);">large</a><br/><br/><a href="javascript:void(0);" onclick="closeEmbedDialog();">Back to photos</a>';
            break;
          case 'slideshow':
            var dWidth    = 250;
            var dHeight   = 200;
            _html = 'slideshow';
            break;
        }
        
        var dOffsetX  = parseInt(($('divInfo').getWidth() / 2) - (dWidth / 2));
        var dOffsetY  = parseInt(($('divInfo').getHeight() / 2) - (dHeight / 2));
        
        $('dialog').setStyle({display:'block',width:dWidth+'px',height:dHeight+'px',left:dOffsetX+'px',top:dOffsetY+'px',padding:'5px'});
        $('dialog').update(_html);
      }
      
      function closeEmbedDialog()
      {
        $('dialog').setStyle({display:'none'});
      }
      
      function doLoad()
      {
        switch(section)
        {
          case 'slideshows':
            loadSlideshows(document.getElementById('txtUrl').value);
            break;
          default:
            loadPhotos(document.getElementById('txtUrl').value);
            break;
        }
      }
    </script>
    <style type="text/css">
      body{ margin:3px; }
      
      #dialog{
        position:absolute;
        background-color: white;
        border:solid 1px black;
        display:none;
        top:0px;
        left:0px;
      }
    </style>
    <link href="/css/basic.css" type="text/css" rel="stylesheet">
    <link href="common/fck_dialog_common.css" type="text/css" rel="stylesheet">
    <link href="../css/fck_editorarea.css" type="text/css" rel="stylesheet">
  </head>
  <body scroll="no" style="OVERFLOW: hidden" onload="doLoad(); window.focus();">
    <div id="divInfo">
      <div style="margin:0px; padding:5px; font-weight:bold;">
        <div style="height:30px;">
          <a href="javascript:void(0);" onclick="loadPhotos();">Photos</a> | <a href="javascript:void(0);" onclick="loadSlideshows();">Slideshows</a>
          <form name="ffmsTagSearch" id="ffmsTagSearch" action="#" style="display:inline;" onsubmit="doLoad(); return false;">
            <input id="txtUrl" style="width:100px;" type="text" class="formfield">
            <a href="javascript:doLoad();"><img src="fck_ffms/search.gif" width="16" height="16" align="absmiddle" hspace="3" border="0" /></a>
          </form>
          <img src="/images/ajax_loader_snake.gif" width="16" height="16" hspace="10" align="absmiddle" id="loader" />
          <hr size="1" />
        </div>
      </div>
      <div style="margin-top:10px;">
        <div  id="ePreviewCell" style="overflow:auto; height:300px; width:100%;" class="bold"></div>
      </div>
    </div>
    <div id="dialog"></div>
    
    <script>
      // put this at the end because it screws up syntax highlighting
      function safe(str)
      {
        return str.replace(/"/g, '&quot;');
      }
    </script>
    
  </body>
</html>
