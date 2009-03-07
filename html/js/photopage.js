function addFriend(friendId, message)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'post', 
    parameters: 'action=fotopage_add_friend&friendId='+friendId+'&message='+escape(message), 
    onComplete: addFriendRsp
    });
}

function addFriendRsp(response)
{
  var data = response.responseText.parseJSON();
  if(data == true)
  {
    $('friendStatus').update('Request sent');
    effRequest.toggle();
  }
}

function addPhotoToCart(photoId)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'get', 
    parameters: 'action=fotopage_add_to_cart&photoId='+photoId, 
    onComplete: function(response){ addPhotoToCartRsp(response); }
    });
}

function addPhotoToCartRsp(response)
{
  $('cartDivContents').innerHTML = '<div class="bold" align="center">This photo was added to your cart<br/><a href="/?action=printing.redirect.act"><img src="images/buttons/btn_order_prints.gif" width="95" height="18" vspace="3" border="0" /></a></div>';
  effectCart.toggle();
}

function blogEntryDelete(entryId)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'post', 
    parameters: 'action=blog_entry_delete&entryId='+entryId, 
    onComplete: blogEntryDeleteRsp
    });
}

function blogEntryDeleteRsp(response)
{
  var data = response.responseText.parseJSON();
  var eff = new fx.Height('blog_entry_' + data);
  eff.toggle();
}

function blogEntryForm()
{
  if($('ube_subject').value == '')
  {
    alert('Please enter a subject for your post.');
    $('ube_subject').focus();
    return false;
  }
  else
  {
    return true;
  }
  
}

function blogEntryCommentForm()
{
  if($('c_comment').value == '')
  {
    alert('Please enter a comment.');
    $('c_comment').focus();
    return false;
  }
  else
  {
    return true;
  }
  
}

function changePhoto(tags)
{
  _tags = tags; // this variable is accessed from outside this function
  var _offset = arguments.length > 2 ? arguments[1] : 0;
  var _limit = arguments.length > 3 ? arguments[2] : 12;
  var _privacy = 1;
  
  var element = $('photoBlank');
  element.style.position = 'absolute';
  element.style.border = 'solid 1px #dddddd';
  element.style.width = '350px';
  element.style.height = '310px';
  element.style.paddingTop = element.style.paddingLeft = element.style.paddingRight = element.style.paddingBottom = '10px';
  element.style.backgroundColor = '#f5f5f5';
  element.innerHTML = '<div style="float:left;" class="f_8 bold">Change Photo</div>'
                    + '<div style="float:right;"><a href="javascript:void(0);" onclick="photoBlankEffect.toggle();" title="close this dialog"><img src="images/icons/close_16x16.png" class="png" width="16" height="16" border="0" /></a></div>'
                    + '<br clear="all" />'
                    + '<form action="" style="display:inline;" id="searchByTagForm" onsubmit="return changePhoto( $(\'searchBox\').value);">'
                    + '<div style="float:left; padding-left:5px; padding-top:8px;">'
                    + '<img src="images/tag_search_icon.gif" width="11" height="16" border="0" style="float:left; padding-right:5px;">'
                    + '<input id="searchBox" class="formfield" type="text" style="float:left; display:block; width:80px;" value="'+_tags+'" />'
                    + '</div>'
                    + '<div id="auto_complete_searchBox" class="auto_complete" style="float:left; width:80px; z-index:75;"></div>'
                    + '<div style="float:left; padding-left:3px; padding-top:10px;"><a href="javascript:void($(\'searchByTagForm\').onsubmit());"><img src="images/icons/search_16x16.png" class="png" width="16" height="16" border="0" hspace="2" /></a></div>'
                    + '</form>'
                    + '<br clear="all" />'
                    + '<div id="photoResults">'
                    + ' <div style="width:85px; margin:auto; padding-top:100px;">'
                    + '   <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>'
                    + '   <div style="float:left;">Loading...</div>'
                    + '   <br/>'
                    + ' </div>'
                    + '</div>';
  

  if(photoBlankEffect.current() == 0)
  {
    photoBlankEffect.toggle();
  }
  
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'get', 
    parameters: 'action=fotos_search&TAGS='+tags+'&OFFSET='+_offset+'&LIMIT='+_limit+'&privacy='+_privacy, 
    onComplete: function(response){ changePhotoRsp(response); }
    });
  return false;
}

function changePhotoRsp(response)
{
  var data = response.responseText.parseJSON();
  var hasFotos = false;
  var element = $('photoResults');
  var _html = '';
    
  _html += '<div style="min-height:232px; _height:232px;">';
  
  var _k = 0;
  for( i in data )
  {
    if(i != 'MISC' && data[i].P_THUMB_PATH != '')
    {
      i = parseInt(i);
      // id, key, thumbnail src
      _html += '<div style="float:left; padding-right:5px; padding-top:5px;"><a href="javascript:void(0);" onclick="updatePhoto('+data[i].P_ID+');" title="Click to change photo"><img src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></a></div>';
      hasFotos = true;
    }
  }
  
  _html += '</div>';
    
  if( hasFotos == false )
  {
    _html += '<div style="padding-top:15px;" class="f_8">No photos available to set as your photo.  Please refine your tag search or upload photos.</div>';
  }
  
  _html += '<br clear="all" />';
  
  var _offset = parseInt(data.MISC.OFFSET);
  var _limit = parseInt(data.MISC.LIMIT);
  var _lastFoto = parseInt(_offset + _limit);
  
  _html += '<div style="width:340px; height:15px; padding-top:7px; text-align:right;">'
        +  '  <div style="float:left; text-align:left; width:170px;">';
  // Previous Link
  if(_offset > 0)
  {
    _html += '<a href="javascript:void(0);" onclick="changePhoto( $(\'searchBox\').value,' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLink">Previous</a>';
  }
  
  _html += '&nbsp;</div>'
        +  '<div style="float:left; text-align:right; width:170px;">&nbsp;';
  
  // Next Link
  if(data.MISC.TOTAL_ROWS > _lastFoto)
  {
    _html += '<a href="javascript:void(0);" onclick="changePhoto( $(\'searchBox\').value,' + (_offset+_limit) + ',' + _limit + ');" id="PageNextLink">Next</a>';
  }
  
  _html += '  </div>'
        +  '</div>';
  
  element.innerHTML = _html;
  new Autocompleter.Local("searchBox", "auto_complete_searchBox", userTags, {tokens: ","});
}

function deleteComment(c_id)
{
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=delete_comment&c_id=' + c_id, 
    onComplete: function(response){ deleteCommentRsp(response, c_id); }
  });
}

function deleteCommentRsp(response, c_id)
{
  var data = response.responseText.parseJSON();
  
  var commentWipe = new fx.Height('_comment_' + c_id);
  commentWipe.toggle();
}

function flagFoto(foto_id, u_id, session_id)
{ 
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=flag_foto&p_id=' + foto_id + '&u_id=' + u_id + '&session_id=' + session_id, 
    onComplete: flagFotoRsp()
  });
  
  return false;
}

function flagFotoRsp()
{
  $('flaggedIcon').src = 'images/icons/event_red_24x24.png';
  $('flaggedText').innerHTML = 'This photo was flagged';
}

function pageShareGetPhotos()
{
  // pageOpts is var'ed in header.dsp.php
  tags = pageOpts['TAGS'] == undefined ? '' : pageOpts['TAGS'];
  offset = pageOpts['OFFSET'] == undefined ? 0 : pageOpts['OFFSET'];
  limit = pageOpts['LIMIT'] == undefined ? 12 : pageOpts['LIMIT'];
  
  _layer = $('_pageShareDataPhotos');
  _layer.innerHTML  = '<div style="width:85px; margin:auto; padding-top:125px;">'
                    + ' <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>'
                    + ' <div style="float:left;">Loading...</div>'
                    + ' <br/>'
                    + '</div>';
  //alert('action=fotos_search&TAGS=' + tags + '&OFFSET=' + offset + '&LIMIT=' + limit);
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=fotos_search&TAGS=' + tags + '&OFFSET=' + offset + '&LIMIT=' + limit + '&ORDER=P_TAKEN', 
      onComplete: function(response){ pageShareGetPhotosRsp(response); refreshTagsRsp(); }
    });
}

function pageShareGetPhotosRsp(response)
{
  var data = response.responseText.parseJSON();
  var hasFotos = false;
  var tags = data.MISC.TAGS;
  var radioName = '';
  
  var _html = '<div style="width:580px; margin:auto;">';
  _k = 1;
  
  var opac = [];
  
    var pg = new Pager({current:parseInt((pageOpts['OFFSET']/pageOpts['LIMIT'])+1),total:Math.ceil(data.MISC.TOTAL_ROWS/data.MISC.LIMIT),pagesDisplay:6,itemsPerPage:pageOpts['LIMIT'], varStart:'pageOpts[\'OFFSET\']',varLimit:'pageOpts[\'LIMIT\']',jsFunc:'pageShareGetPhotos',opts:'pageOpts'});
    
    if(pg.total > pg.pagesDisplay)
    {
      $('_pageSharePagingPhotos').innerHTML = '<div>'
          + '   <span style="padding-right:4px;">Page ' + pg.current + ' of ' + pg.total + ' |</span>'
          + '   <span style="padding-right:4px;">' + pg.first() + '</span>'
          + '   <span style="padding-right:4px;">' + pg.previous() + '</span>'
          + '   <span style="padding-right:4px;">' + pg.generate() + '</span>'
          + '   <span style="padding-right:4px;">' + pg.next() + '</span>'
          + '   <span>' + pg.last() + '</span>'
          + ' </div>';
    }
    else
    if(true)
    {
      $('_pageSharePagingPhotos').innerHTML = '<div>'
          + '   <span style="padding-right:4px;">Page ' + pg.current + ' of ' + pg.total + ' |</span>'
          + '   <span style="padding-right:4px;">' + pg.generate() + '</span>'
          + ' </div>';
    }
  
  for( i in data )
  {
    radioName = 'radioName_' + data[i].P_ID;
    if(i != 'MISC')
    {
      i = parseInt(i);
      if(data[i].P_PRIVACY & 1 == 1) // public
      {
        _html += '<div id="_photo_' + data[i].P_ID + '" style="float:left; width:135px; height:115px; padding:5px; margin:auto;">';
        _html += '  <div id="_photo_' + data[i].P_ID + '_inner">';
        _html += '    <div style="width:77px; margin:auto;"><img id="opac_' + data[i].P_ID + '" src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></div>';
        _html += '    <div><input type="checkbox" id="' + radioName + '" value="1" checked="true" onclick="pageTogglePhoto(' + data[i].P_ID + ');" /> Share this photo</div>';
        _html += '  </div>';
        _html += '</div>';
      }
      else
      {
        _html += '<div id="_photo_' + data[i].P_ID + '" style="float:left; width:135px; height:115px; padding:5px; margin:auto;">';
        _html += '  <div id="_photo_' + data[i].P_ID + '_inner">';
        _html += '    <div style="width:77px; margin:auto;"><img id="opac_' + data[i].P_ID + '" src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></div>';
        _html += '    <div><input type="checkbox" id="' + radioName + '" value="1" onclick="pageTogglePhoto(' + data[i].P_ID + ');" /> Share this photo</div>';
        _html += '  </div>';
        _html += '</div>';
      }
      _k++;
      hasFotos = true;
    }
  }
  _html += '</div>';
    
  _layer = $('_pageShareDataPhotos');
  
  if(hasFotos == true)
  {
    _layer.innerHTML = _html;
    _offset = parseInt(data.MISC.OFFSET);
    _limit = parseInt(data.MISC.LIMIT);
    _lastFoto = parseInt(_offset + _limit);
  }
  else
  {
    if(tags ==- '') // no tags so no photos in account
    {
      _layer.innerHTML = '<div style="width:500px; padding:10px; margin:auto;" class="bold">Before you add photos to your photo page you need to upload some photos.</div>'
                       + '<div style="width:280px; margin:auto;"><a href="/?action=fotobox.upload_installer"><img src="images/start_uploading_fotos.gif" width="280" height=""30" border="0" /></a></div>';
    }
    else // tags so display no results for search
    {
      _layer.innerHTML = '<div style="width:180px; padding:10px; margin:auto;" class="bold">There are no photos tagged with <span class="italic">' + tags + '</span>.</div>';
    }
  }
}

function pageShareGetSlideshows()
{
  var tags = arguments.length == 1 ? arguments[0] : '';
  var _offset = arguments.length > 1 ? arguments[1] : 0;
  var _limit = arguments.length > 2 ? arguments[2] : 8;
  
  
  _layer = $('_pageShareDataSlideshows');
  _layer.innerHTML  = '<div style="width:85px; margin:auto; padding-top:125px;">'
                    + ' <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>'
                    + ' <div style="float:left;">Loading...</div>'
                    + ' <br/>'
                    + '</div>';
  
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=flix_search&TAGS=' + tags + '&OFFSET=' + _offset + '&LIMIT=' + _limit, 
    onComplete: function(response){ pageShareGetSlideshowsRsp(response); refreshTagsRsp(); }
  });
}

function pageShareGetSlideshowsRsp(response)
{
  var data = response.responseText.parseJSON();
  var _proceed = false;
  var opac = [];
   
  var _html = '';
  for( i = 0; i < data['FLIX_DATA'].length; i++ )
  {
    if(data['FLIX_DATA'][i] != null)
    { 
      _link = 'pageToggleSlideshow(' + data['FLIX_DATA'][i]['US_ID'] + ')';
      _title = data['FLIX_DATA'][i]['US_NAME'];
      if(_title.length > 10)
      {
        _title = _title.substring(0, 10) + '...';
      }
      
      if(data['FLIX_DATA'][i]['US_PRIVACY'] & 1 == 1) // public
      {
        _html += '<div id="_slideshow_' + data['FLIX_DATA'][i]['US_ID'] + '" style="width:145px; height:170px; padding:5px; float:left;">';
        _html +=  '<div id="_slideshow_' + data['FLIX_DATA'][i]['US_ID'] + '_inner">'
              +   ' <div>'
              +   '   <div class="flix_border" style="margin:auto;">'
              +   '     <img id="opac_slideshow_'+data['FLIX_DATA'][i]['US_ID']+'" src="/photos' + data['FLIX_DATA'][i]['P_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" />'
              +   '   </div>'
              +   ' </div>'
              +   ' <div>'
              +   '   <div class="bold">'
              +   '     <a href="javascript:void(0);" onclick="' + _link + '" title="Click to remove slideshow">' + _title + '</a>'
              +   '   </div>'
              +   '   <div></div>'
              +   ' </div>'
              +   ' <br clear="all" />'
              +   ' <div><input type="checkbox" checked="true" id="radioName_' + data['FLIX_DATA'][i]['US_ID'] + '" value="1" onclick="' + _link + '" /> Share this slideshow</div>'
              +   '</div>';
        _html += '</div>';
      }
      else
      {
        _html += '<div id="_slideshow_' + data['FLIX_DATA'][i]['US_ID'] + '" style="width:145px; height:170px; padding:5px; float:left;">';
        _html +=  '<div id="_slideshow_' + data['FLIX_DATA'][i]['US_ID'] + '_inner">'
              +   ' <div>'
              +   '   <div class="flix_border" style="margin:auto;">'
              +   '     <img id="opac_slideshow_'+data['FLIX_DATA'][i]['US_ID']+'" src="/photos' + data['FLIX_DATA'][i]['P_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" />'
              +   '   </div>'
              +   ' </div>'
              +   ' <div>'
              +   '   <div class="bold">'
              +   '     <a href="javascript:void(0);" onclick="' + _link + '" title="Click to share slideshow">' + _title + '</a>'
              +   '   </div>'
              +   ' </div>'
              +   ' <br clear="all" />'
              +   ' <div><input type="checkbox" id="radioName_' + data['FLIX_DATA'][i]['US_ID'] + '" value="1" onclick="' + _link + '" /> Share this slideshow</div>'
              +   '</div>';
        _html += '</div>';
      }
      
      _proceed = true;
    }
  }
  
  _layer = $('_pageShareDataSlideshows');
  
  if(_proceed)
  { 
    _layer.innerHTML = _html;
    _offset = parseInt(data['MISC']['OFFSET']);
    _limit = parseInt(data['MISC']['LIMIT']);
    _lastFlix = parseInt(_offset + _limit);
    
    // Previous Link
    if(_offset > 0)
    {
      $('_pageSharePagingSlideshows').innerHTML = '<a href="javascript:void(0);" onclick="pageShareGetSlideshows( $(\'_pageShareSearchBoxSlideshows\').value,' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLinkFlix">Previous</a>&nbsp;&nbsp;';
    }
    else
    {
      $('_pageSharePagingSlideshows').innerHTML = '&nbsp;&nbsp;';
    }
    
    // Next Link
    if(data['MISC']['TOTAL_FLIX'] > _lastFlix)
    {
      $('_pageSharePagingSlideshows').innerHTML += '<a href="javascript:void(0);" onclick="pageShareGetSlideshows( $(\'_pageShareSearchBoxSlideshows\').value,' +  (_offset+_limit) + ',' + _limit + ');" id="PageNextLinkFlix">Next</a>';
    }
    else
    {
      $('_pageSharePagingSlideshows').innerHTML += '';
    }
  }
  else
  {
    if($('_pageShareSearchBoxSlideshows').value == '') // no tags so no slideshows created
    {
      _layer.innerHTML = '<div style="width:250px; padding:10px; margin:auto;" class="bold">You have not created any slideshows.</div>';
    }
    else // tags so display no search results
    {
      _layer.innerHTML = '<div style="width:300px; padding:10px; margin:auto;" class="bold">There are no slideshows tagged with <span class="italic">' + $('_pageShareSearchBoxSlideshows').value + '</span>.</div>';
    }
    
  }
}

function pageShareGetVideos()
{
  var tags = arguments.length == 1 ? arguments[0] : '';
  var _offset = arguments.length > 1 ? arguments[1] : 0;
  var _limit = arguments.length > 2 ? arguments[2] : 8;
  
  
  _layer = $('_pageShareDataVideos');
  _layer.innerHTML  = '<div style="width:85px; margin:auto; padding-top:125px;">'
                    + ' <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>'
                    + ' <div style="float:left;">Loading...</div>'
                    + ' <br/>'
                    + '</div>';
  
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=video_search&TAGS=' + tags + '&OFFSET=' + _offset + '&LIMIT=' + _limit, 
    onComplete: function(response){ pageShareGetVideosRsp(response); refreshTagsRsp(); }
  });
}

function pageShareGetVideosRsp(response)
{
  var data = response.responseText.parseJSON();
  var _proceed = false;
  var opac = [];
   
  var _html = '';
  for( i = 0; i < data['VIDEO_DATA'].length; i++ )
  {
    if(data['VIDEO_DATA'][i] != null)
    { 
      _link = 'pageToggleVideo(' + data['VIDEO_DATA'][i]['V_ID'] + ')';
      _title = data['VIDEO_DATA'][i]['V_NAME'];
      if(_title.length > 10)
      {
        _title = _title.substring(0, 10) + '...';
      }
      
      if(data['VIDEO_DATA'][i]['V_PRIVACY'] & 1 == 1) // public
      {
        _html += '<div id="_video_' + data['VIDEO_DATA'][i]['V_ID'] + '" style="width:145px; height:170px; padding:5px; float:left;">';
        _html +=  '<div id="_video_' + data['VIDEO_DATA'][i]['V_ID'] + '_inner">'
              +   ' <div>'
              +   '   <div class="flix_border" style="margin:auto;">'
              +   '     <img id="opac_video_'+data['VIDEO_DATA'][i]['V_ID']+'" src="' + data['VIDEO_DATA'][i]['V_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" />'
              +   '   </div>'
              +   ' </div>'
              +   ' <div>'
              +   '   <div class="bold">'
              +   '     <a href="javascript:void(0);" onclick="' + _link + '" title="Click to remove video">' + _title + '</a>'
              +   '   </div>'
              +   '   <div></div>'
              +   ' </div>'
              +   ' <br clear="all" />'
              +   ' <div><input type="checkbox" checked="true" id="radioName_' + data['VIDEO_DATA'][i]['V_ID'] + '" value="1" onclick="' + _link + '" /> Share this video</div>'
              +   '</div>';
        _html += '</div>';
      }
      else
      {
        _html += '<div id="_video_' + data['VIDEO_DATA'][i]['V_ID'] + '" style="width:145px; height:170px; padding:5px; float:left;">';
        _html +=  '<div id="_video_' + data['VIDEO_DATA'][i]['V_ID'] + '_inner">'
              +   ' <div>'
              +   '   <div class="flix_border" style="margin:auto;">'
              +   '     <img id="opac_video_'+data['VIDEO_DATA'][i]['V_ID']+'" src="' + data['VIDEO_DATA'][i]['V_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" />'
              +   '   </div>'
              +   ' </div>'
              +   ' <div>'
              +   '   <div class="bold">'
              +   '     <a href="javascript:void(0);" onclick="' + _link + '" title="Click to share video">' + _title + '</a>'
              +   '   </div>'
              +   ' </div>'
              +   ' <br clear="all" />'
              +   ' <div><input type="checkbox" id="radioName_' + data['VIDEO_DATA'][i]['V_ID'] + '" value="1" onclick="' + _link + '" /> Share this video</div>'
              +   '</div>';
        _html += '</div>';
      }
      
      _proceed = true;
    }
  }
  
  _layer = $('_pageShareDataVideos');
  
  if(_proceed)
  { 
    _layer.innerHTML = _html;
    _offset = parseInt(data['MISC']['OFFSET']);
    _limit = parseInt(data['MISC']['LIMIT']);
    _lastFlix = parseInt(_offset + _limit);
    
    // Previous Link
    if(_offset > 0)
    {
      $('_pageSharePagingVideos').innerHTML = '<a href="javascript:void(0);" onclick="pageShareGetVideos( $(\'_pageShareSearchBoxVideos\').value,' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLinkVideo">Previous</a>&nbsp;&nbsp;';
    }
    else
    {
      $('_pageSharePagingVideos').innerHTML = '&nbsp;&nbsp;';
    }
    
    // Next Link
    if(data['MISC']['TOTAL_FLIX'] > _lastFlix)
    {
      $('_pageSharePagingVideos').innerHTML += '<a href="javascript:void(0);" onclick="pageShareGetVideos( $(\'_pageShareSearchBoxVideos\').value,' +  (_offset+_limit) + ',' + _limit + ');" id="PageNextLinkVideo">Next</a>';
    }
    else
    {
      $('_pageSharePagingVideos').innerHTML += '';
    }
  }
  else
  {
    if($('_pageShareSearchBoxVideos').value == '') // no tags so no videos created
    {
      _layer.innerHTML = '<div style="width:250px; padding:10px; margin:auto;" class="bold">You have not created any videos.</div>';
    }
    else // tags so display no search results
    {
      _layer.innerHTML = '<div style="width:300px; padding:10px; margin:auto;" class="bold">There are no videos tagged with <span class="italic">' + $('_pageShareSearchBoxVideos').value + '</span>.</div>';
    }
    
  }
}

function pageToggleVideo( video_id )
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=fotopage_video_toggle&video_id=' + video_id, 
      onComplete: function(){ pageToggleVideoRsp(video_id); }
    });
}

function pageToggleVideoRsp(video_id)
{
  new Effect.Highlight('_video_'+video_id, {restorecolor:"#ffffff"});
  if($('_video_'+video_id+'_confirmation') == undefined)
  {
    new Insertion.After('_video_'+video_id+'_inner', '<div id="_video_'+video_id+'_confirmation" class="center">Personal Page Updated</div>');
    setTimeout("Element.remove('_video_"+video_id+"_confirmation');", 2500);
  }
}

function pageToggleSlideshow( slideshow_id )
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=fotopage_flix_toggle&slideshow_id=' + slideshow_id, 
      onComplete: function(){ pageToggleSlideshowRsp(slideshow_id); }
    });
}

function pageToggleSlideshowRsp(slideshow_id)
{
  new Effect.Highlight('_slideshow_'+slideshow_id, {restorecolor:"#ffffff"});
  if($('_slideshow_'+slideshow_id+'_confirmation') == undefined)
  {
    new Insertion.After('_slideshow_'+slideshow_id+'_inner', '<div id="_slideshow_'+slideshow_id+'_confirmation" class="center">Personal Page Updated</div>');
    setTimeout("Element.remove('_slideshow_"+slideshow_id+"_confirmation');", 2500);
  }
}

function pageTogglePhoto( photo_id )
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=fotopage_foto_toggle&photo_id=' + photo_id, 
      onComplete: function(){ pageTogglePhotoRsp(photo_id); }
    });
}

function pageTogglePhotoRsp(photo_id)
{
  new Effect.Highlight('_photo_'+photo_id, {restorecolor:"#ffffff"});
  if($('_photo_'+photo_id+'_confirmation') == undefined)
  {
    new Insertion.After('_photo_'+photo_id+'_inner', '<div id="_photo_'+photo_id+'_confirmation" class="center">Personal Page Updated</div>');
    setTimeout("Element.remove('_photo_"+photo_id+"_confirmation');", 2500);
  }
}

function pageShareSlideshow(slideshow_id, privacy)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=share_slideshow&slideshow_id=' + slideshow_id + '&privacy=' + parseInt(privacy), 
      onComplete: function(response){ pageShareSlideshowRsp(response, slideshow_id); refreshTagsRsp(); }
    });
}

function pageShareSlideshowRsp(response, slideshow_id)
{
  var data = response.responseText.parseJSON();
  
  _title = data['FLIX_DATA'][0]['A_NAME'];
  if(_title.length > 10)
      {
        _title = _title.substring(0, 10) + '...';
      }
  _theme = data['FLIX_DATA'][0]['T_NAME'];
      
  var _html = '';
  _html +=  '<div style="background:url(images/board_flix_bg.gif) repeat-x; margin-bottom:3px; height:85px;">'
      +   ' <div style="float:left; padding-top:5px; padding-bottom:5px; padding-left:1px;">'
      +   '   <div style="padding-right:4px;"><img src="/fotos' + data['FLIX_DATA'][0]['P_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" /></div>'
      +   ' </div>'
      +   ' <div style="float:left; padding-top:10px;">'
      +   '   <div class="bold">'
      +   _title
      +   '   </div>'
      +   '   <div>' + _theme + '</div>'
      +   '   <div>' + data['FLIX_DATA'][0]['A_FOTO_COUNT'] + ' photos</div>'
      +   '   <div>' + data['FLIX_DATA'][0]['A_VIEWS'] + ' views</div>'
      +   '   <div class="f_red">Shared</div>'
      +   ' </div>'
      +   ' <br clear="all" />'
      +   '</div>';
        
  $('_slideshow_' + slideshow_id).innerHTML = _html;
  $('_slideshow_' + slideshow_id).style.opacity = .5;
}

function photoPagePermissionSet(photo_id, type, setting)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=fotopage_foto_permission_set&type='+type+'&setting='+setting+'&photo_id=' + photo_id, 
      onComplete:function(response){ photoPagePermissionSetRsp(setting, type); }
    });
}

function photoPagePermissionSetRsp(setting, type)
{
  switch(type)
  {
    case 'download':
      if(setting == 1)
      {
        location.href = location.href + "downloadable/";
      }
      else
      {
        location.href = location.href.replace("downloadable/", "");
      }
      break;
    case 'comment':
      if(setting == 1)
      {
        location.href = location.href+"?comment#fotoComment";
      }
      else
      {
        location.href = location.href.replace("#fotoComment", "");
      }
  
      break;
  }
}

function removePhotoFromCart(toolboxId)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'post', 
    parameters: 'action=fotopage_remove_from_cart&toolboxId='+toolboxId, 
    onComplete: function(response){ removePhotoFromCartRsp(response, toolboxId); }
    });
}

function removePhotoFromCartRsp(response, photoId)
{
  var toolboxItem = response.responseText.parseJSON();
  $('cartLink').innerHTML = '<a href="javascript:void(0);" onclick="addPhotoToCart('+toolboxItem.P_ID+');" class="bold plain"><img src="images/icons/add_alt_2_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" /> Add to cart</a>';
}

function removePhotoFromPage(photo_id)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=fotopage_foto_remove&photo_id=' + photo_id, 
      onComplete:function(response){ removePhotoFromPageRsp(response); }
    });
}

function removePhotoFromPageRsp(response)
{
  data = response.responseText.parseJSON();
  var effect = new fx.Height('removeLinkConfirm');
  effect.hide();
  $('removeLinkConfirmText').innerHTML = '<div style="padding:10px;">This photo has been removed from your page</div>';
  effect.toggle();
}

function removePhoto(id)
{
  $('photoIcon').src = 'images/icons/refresh_24x24.png';
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=personal_photo_update&id=0', onComplete:function(response){ removePhotoRsp(response); } }
  );
}

function removePhotoRsp(response)
{
  var _pieces = response.responseText.split(',');
  if(_pieces[0] == 'true')
  {
    if(_pieces[1] != 'null')
    {
      $('personalHome1').src = _pieces[1];
      $('personalHome1Link').href= '/users/' + _pieces[2] + '/photo/' + _pieces[3] + '/?offset=0';
      $('personalHome1').onload = function(){ $('photoIcon').src = 'images/icons/pencil_24x24.png'; $('photoIconRemoveDisplay').style.display = 'none';};
    }
  }
}

function updatePhoto(id)
{
  $('photoIcon').src = 'images/icons/refresh_24x24.png';
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=personal_photo_update&id='+id, onComplete:function(response){ updatePhotoRsp(response, id); } }
  );
}

function updatePhotoRsp(response, id)
{
  var _pieces = response.responseText.split(',');
  if(_pieces[0] == 'true')
  {
    if(_pieces[1] != 'null')
    {
      mTop = _pieces[5];
      $('personalHome1Div').style.width = _pieces[4] + 'px';
      $('personalHome1Div').style.height = _pieces[5] + 'px';
      $('personalHome1').style.width = _pieces[4] + 'px';
      $('personalHome1').style.height = _pieces[5] + 'px';
      $('personalHome1').src = _pieces[1];
      
      $('personalHome1Link').href= '/users/' + _pieces[2] + '/photo/' + id + '/?offset=0';
      $('personalHome1').onload = function(){ $('photoIcon').src = 'images/icons/pencil_24x24.png'; $('photoIconRemoveDisplay').style.display = 'block'; };
      $('photoIconDiv').style.marginTop = '-' + mTop + 'px';
      $('photoBlank').style.marginTop = '-' + mTop + 'px';
      
      photoBlankEffect.toggle(); // defined in header.dsp.php
    }
  }
  $('personalHome1Controls').style.display = 'block';
}