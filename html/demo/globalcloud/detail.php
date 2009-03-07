<html lang="en">

  <head>
    <link href="extra/styles.css" media="screen" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="extra/prototype.lite.js"></script>
    <script type="text/javascript" src="extra/moo.fx.js"></script>
    
    <!-- PTG -->
    <script type="text/javascript" id="__PTG" src="http://www.photagious.com/js/api.js"></script>
    <script type="text/javascript">
      var ptg = new PTG("10fbbb7d87826e9301c7323cb9c5ddbc");
    </script>
    
    <script type="text/javascript">
      function transformPhoto(action, photoId)
      {
        $('photo-preview-loader').style.display = 'block';
        switch(action)
        {
          case 'rotate':
            ptg.photo.transform.rotate(photoId, arguments[2], 'transformPhotoRsp');
            break;
          case 'greyscale':
            ptg.photo.transform.greyscale(photoId, 'transformPhotoRsp');
            break;
          case 'restore':
            ptg.photo.transform.restore(photoId, 'transformPhotoRsp');
            break;
        }
      }
      
      function transformPhotoRsp(data)
      {
        $('photo-preview-loader').style.display = 'none';
        $('photo-tag').src = ptg.html.customImageLockSrc(data.P_THUMB_PATH, data.P_KEY, data.P_WIDTH, data.P_HEIGHT, 640, 480, data.P_ROTATION) + '-' + parseInt(Math.random()*100000);
      }
      
      function loadPhotoRsp(data)
      {
        $('photo-preview-loader').style.display = 'none';
        html = ptg.html.customImageLockTag(data.P_THUMB_PATH, data.P_KEY, data.P_WIDTH, data.P_HEIGHT, 640, 480, data.P_ROTATION, {'id':'photo-tag','hspace':'5','vspace':'5','border':'0'})
             + data.P_TAGS.replace(/\,/g, '<br/>');
        
        $('photo-preview-content').innerHTML = html;
      }
    </script>
    
  </head>
  
  <body onload="ffms.photo.getPhoto(photoId, 'loadPhotoRsp');">
    <div id="container">
      <div class="f_12 bold"><a href="./">Main</a> / Photo Details</div>
      <br/>
      <div class="photo">
        <div>
          <div class="toolbar-element"><a href="javascript:void(0);" onclick="transformPhoto('rotate', photoId, 270);" class="plain"><img src="extra/rotate_left_16x16.png" width="16" height="16" border="0" hspace="3" align="absmiddle" /> Rotate Left</a></div>
          <div class="toolbar-element"><a href="javascript:void(0);" onclick="transformPhoto('rotate', photoId, 90);" class="plain"><img src="extra/rotate_right_16x16.png" width="16" height="16" border="0" hspace="3" align="absmiddle" /> Rotate Right</a></div>
          <div class="toolbar-element"><a href="javascript:void(0);" onclick="transformPhoto('greyscale', photoId);" class="plain"><img src="extra/tv_16x16.png" width="16" height="16" border="0" hspace="3" align="absmiddle" /> Greyscale</a></div>
          <div class="toolbar-element"><a href="javascript:void(0);" onclick="transformPhoto('restore', photoId);" class="plain"><img src="extra/loop_16x16.png" width="16" height="16" border="0" hspace="3" align="absmiddle" /> Restore</a></div>
          <br clear="all" />
        </div>
        <div id="photo-preview-loader" class="loader photo-loader">Please wait...</div>
        <div id="photo-preview-content"></div>
      </div>
    </div>
  </body>
</html>