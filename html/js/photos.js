function addQuickSet(tags, name, parent)
{
  var _params = 'action=user_quick_set_add&tags='+tags+'&name='+name+'&parent='+parent+'&timestamp='+parseInt(Math.random()*100000);
  getXmlHttp();
  xmlHttpSend('genericQuickSetRsp()', '/xml_result', _params);
  return false;
}
// addQuickSet calls genericQuickSetRsp

function addQuickSet(tags, name, parent)
{
  var _params = 'action=user_quick_set_add&tags='+tags+'&name='+name+'&parent='+parent+'&timestamp='+parseInt(Math.random()*100000);
  getXmlHttp();
  xmlHttpSend('genericQuickSetRsp()', '/xml_result', _params);
  return false;
}
// addQuickSet calls genericQuickSetRsp

function addTag(foto_id, tags)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
    method: 'get',
    parameters: 'action=foto_tags_add&foto_id='+foto_id+'&tags='+tags+'&timestamp='+parseInt(Math.random()*100000),
    onComplete: function(response){ genericShowTagsRsp(foto_id, 19, response); refreshTagsRsp(); }
  });
}
// adTagg calls genericShowTagsRsp and refreshTagsRsp

function addTags(tags)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
    method: 'get',
    parameters: 'action=foto_tags_add&toolbox=1&tags='+tags,
    onComplete: addTagsRsp
    });
}

function addTagsRsp()
{
  $('overlayConfirm').innerHTML = 'Your photos were tagged.';
  var effect = new fx.Opacity('overlayConfirm', {duration:2000});
  effect.toggle();
  loadFotos(fbOpts);
}

function deleteFotos(opts)
{
  var myAjax = new Ajax.Request(
  '/xml_result',
  {
    method: 'get',
    parameters: 'action=fotos_delete&toolbox=1&timestamp='+parseInt(Math.random()*100000),
    onComplete: function(response){ deleteFotosRsp(response, opts); }
  });
}

function deleteFotosRsp(response, opts)
{
  var data = response.responseText.parseJSON();
  if(data == true)
  {
    tb.clear();
    loadFotos(opts);
    hideOverlayForm();
  }
}

function deleteQuickSet(id)
{
  var _params = 'action=user_quick_set_delete&set_id='+id+'&timestamp='+parseInt(Math.random()*100000);
  getXmlHttp();
  xmlHttpSend('genericQuickSetRsp()', '/xml_result', _params);
}
// deleteQuickSet calls genericQuickSetRsp

function duplicatePhoto(photoId)
{
  $('photoConfirmMessage').innerHTML = 'Please wait...';
  editOptsConfirm.toggle();
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get',
      parameters: 'action=foto_duplicate&photoId='+photoId,
      onComplete: function(response){ duplicatePhotoRsp(response); }
    });
}

function duplicatePhotoRsp(response)
{
  var data = response.responseText.parseJSON();
  if(data > 0)
  {
    $('photoConfirmMessage').innerHTML = 'Your photo was duplicated.';
    setTimeout("editOptsConfirm.toggle();", 3000);
    loadFotos(fbOpts);
  }
}

function fotoNameUpdate(_id, _name, _desc)
{
  getXmlHttp();
  xmlHttpSend('fotoNameUpdateRsp("'+_name+'","'+_desc+'")', '/xml_result', 'action=foto_name_update&foto_id='+_id+'&name='+escape(_name)+'&desc='+escape(_desc)+'&timestamp='+parseInt(Math.random()*100000));
}

function fotoNameUpdateRsp(name, desc)
{
  top.$('fotoNameDesc').innerHTML = '<div style="width:200px; height:70px; overflow:auto; padding-bottom:2px;">Description: ' + (desc != '' ? desc : '<span class="italic">None entered</span>') + '</div>';
  _toggle("fotoNameUpdateDiv");
  $('fotoUpdateFormSub').value = 'Update';
}

// genericQuickSetRsp is called by numerous functions
function genericQuickSetRsp()
{
  if(xmlHttpResult == 'true')
  {
    setTimeout("quickSetRefresh('display')", 100);
    setTimeout("quickSetDialogHide()", 150);
  }
}

// genericShowTagsRsp is called by numerous functions
function genericShowTagsRsp(foto_id, limit, response)
{
  var data = response.responseText.parseJSON();
  var _element = $('tags');
  var tags = data.TAGS;
  var _html = '';

  if(typeof data == 'object')
  {
    for(i=0; i<tags.length; i++)
    {
      tag = tags[i];
      if(tag.length > 0)
      {
        _html += '<div id="tag_'+foto_id+tag+'" onmouseover="this.style.backgroundColor=\'#ffcdab\';" onmouseout="this.style.backgroundColor=\'\';">(<a href="javascript:removeTag('+foto_id+', \''+tag+'\');" onclick="$(\'tag_'+foto_id+tag+'\').style.backgroundColor=\'yellow\';" title="Untag from foto ('+tag+')">x</a>) ' + tag + '</div>';
      }
    }
  }
  else
  {
    _html = '<div class="bold italics">Tag error</div><div style="padding-top:3px;"><a href="javascript:void(getTags('+foto_id+', 19));">Refresh tags</a></div>';
  }

  _element.innerHTML = _html.length > 0 ? _html : '<div class="bold">No tags</div>';
}

function getFotoName(_id)
{
  _toggle("_fotoName");
  $('fotoUpdateFormName').value = 'Loading...';
  $('fotoUpdateFormDesc').value = 'Loading...';
  getXmlHttp();
  xmlHttpSend('getFotoNameRsp()', '/xml_result', 'action=foto_name&foto_id='+_id+'&timestamp='+parseInt(Math.random()*100000));
}

function getFotoNameRsp()
{
  var parts = xmlHttpResult.split('`');

  $('fotoUpdateFormName').value = parts[0];
  $('fotoUpdateFormDesc').value = parts[1];
}

function getTags()
{
  var foto_id = arguments[0];
  var limit = arguments.length > 1 ? arguments[1] : 100;
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
    method: 'get',
    parameters: 'action=foto_tags&foto_id='+foto_id+'&timestamp='+parseInt(Math.random()*100000),
    onComplete: function(response){ genericShowTagsRsp(foto_id, limit, response); }
  });
}
// getTags calls genericShowTagsRsp

function loadFotos()
{
  var params= arguments[0];
  var lbExtra = arguments[1];
  var addHeight = 0;
  var showCriteria = false;

  var effect = new fx.Opacity('myFotosContent', {duration:100, onComplete: function()
                        {
                          $('myFotosContent').style.display = 'none';
                          $('myFotosLoading').style.display = 'block';
                          var onCompleteEffect = new fx.Opacity('myFotosLoading', {duration:100}); }
                        }
                      );
  var sendParams = '';
  for(i in params)
  {
    switch(i)
    {
      case 'DATE_TAKEN_START':
        sendParams += '&DATE_TAKEN_START=' + params[i];
        showCriteria = true;
        break;
      case 'DATE_TAKEN_END':
        sendParams += '&DATE_TAKEN_END=' + params[i];
        showCriteria = true;
        break;
      case 'LIMIT':
        sendParams += '&LIMIT=' + params[i]+'&saveLimit=' + params[i];
        break;
      case 'OFFSET':
        sendParams += '&OFFSET=' + params[i];
        break;
      case 'TAGS':
        sendParams += '&TAGS=' + params[i];
        showCriteria = true;
        break;
      case 'UNTAGGED':
        sendParams += '&UNTAGGED=' + params[i];
        showCriteria = true;
        break;
      case 'ORDER':
        sendParams += '&ORDER=' + params[i];
        showCriteria = true;
        break;
    }
  }

  if(showCriteria == true)
  {
    addHeight += 40;
  }

  effect.toggle();

  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get',
      parameters: 'action=fotos_search'+sendParams+'&timestamp='+parseInt(Math.random()*100000),
      onComplete:function(response){ loadFotosRsp(response, params, lbExtra); }
    });
}

function loadFotosRsp(response, params, lbExtra)
{
  var data = response.responseText.parseJSON();
  var _html = '';
  var tags = data.MISC.TAGS;
  var totalRows = 0;
  var addHeight = 0;
  var borderStyle = 'solid 1px #dddddd';
  var colTrack = 1;
  var counter = 0;
  var items = [];
  var showCriteria = [];
  var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  var isUser = true;
  var _opts, cols, rowWidth, rowHeight, thumbWidth, thumbHeight;

  _opts = [12, 18, 24];
  cols = 6;
  rowWidth  = 133;
  rowHeight = 125;
  thumbWidth = 75;
  thumbHeight = 75;

  if(data.MISC.TOTAL_ROWS > 0)
  {
    var fbHeight =  function()
                    {
                      var rows = arguments.length > 0 ? arguments[0] : Math.floor(params['LIMIT'] / 6);
                      //alert('rows are ' + rows);
                      return (rows * 127 + 50 + addHeight);
                    }
    var adjustFotoboxHeight = function()
                              {
                                var rows = arguments.length > 0 ? arguments[0] : (params['LIMIT'] / 6);
                                var _fbHeight = fbHeight(rows);
                                //alert('height is ' + _fbHeight);
                                $('myFotosContent').style.height = _fbHeight + 'px';
                                $('myFotosLoading').style.height = parseInt(_fbHeight / 2) + 'px';
                                $('myFotosLoading').style.marginTop = parseInt(_fbHeight / 2) + 'px';
                              }
    var _select = '<select onfocus="formFieldActive(this);" onblur="formFieldInactive(this);" onchange="fbOpts[\'OFFSET\'] = 0; fbOpts[\'LIMIT\'] = this.value; loadFotos(fbOpts);" class="formfield_inactive">';
    for(i=0; i<_opts.length; i++)
    {
      _selected = _opts[i] == fbOpts['LIMIT'] ? ' selected="true"' : '';
      _select += '<option value="'+_opts[i]+'"'+_selected+'>'+_opts[i]+'</option>';
    }
    _select += '</select>';

    var pg = new Pager({current:parseInt((fbOpts['OFFSET']/fbOpts['LIMIT'])+1),total:Math.ceil(data.MISC.TOTAL_ROWS/data.MISC.LIMIT),pagesDisplay:6,itemsPerPage:fbOpts['LIMIT'], varStart:'fbOpts[\'OFFSET\']',varLimit:'fbOpts[\'LIMIT\']',jsFunc:'loadFotos',opts:'fbOpts'});
    _html +='<div style="margin-bottom:10px;">'
          + ' <form style="display:inline;" onsubmit="fbOpts[\'TAGS\'] = $F(\'fbTagSearch\'); fbOpts[\'OFFSET\'] = 0; loadFotos(fbOpts); return false;">'
          + '   <div style="width:114px; float:left; padding-right:4px;"><input autocomplete="off" type="text" value="'+(tags == '' ? 'tag search' : tags)+'" onfocus="formFieldActive(this);" onblur="if($(\'fbTagSearch\').value == \'\') { formFieldInactive(this, \'tag search\'); }" id="fbTagSearch" class="formfield_inactive" style="width:100px;"></div><div class="auto_complete" id="fbTagSearch_auto_complete" style="width:100px;"></div>'
          + '   <div style="width:29px; margin-top:1px; float:left; padding-right:4px;"><a href="javascript:fbOpts[\'TAGS\'] = $F(\'fbTagSearch\'); fbOpts[\'OFFSET\'] = 0; loadFotos(fbOpts);"><img src="images/buttons/go.gif" width="25" height="17" border="0" /></a></div>'
          + ' </form>'
          + ' <div style="margin-top:3px; float:left;">&nbsp;&nbsp;|&nbsp;&nbsp;</div>'
          + ' <div style="margin-top:3px; float:left;">Viewing&nbsp;</div>'
          + ' <div style="float:left;">'+_select+'&nbsp;</div>'
          + ' <div style="margin-top:3px; float:left;">of '+data.MISC.TOTAL_ROWS+' photos</div>'
          + ' <div style="float:right; padding-right:15px;">'
          + (pg.total > 0 ? '   <div style="float:left; padding-right:4px;">Pages </div>' : '')
          + '   <div style="float:left; padding-right:4px;">' + pg.first() + '</div>'
          + '   <div style="float:left; padding-right:4px;">' + pg.previous() + '</div>'
          + '   <div style="float:left; padding-right:4px;">' + pg.generate() + '</div>'
          + '   <div style="float:left; padding-right:4px;">' + pg.next() + '</div>'
          + '   <div style="float:left;">' + pg.last() + '</div>'
          + ' </div>'
          + ' <br clear="left" />'
          + '</div>';

    var urlAppend = '';
    var doAppend = false;
    for(i in params)
    {
      doAppend = false;
      if(params[i].length > 0)
      {
        switch(i)
        {
          case 'DATE_TAKEN_START':
            objDate = new Date();
            objDate.setTime(params[i] * 1000);
            if(params['DATE_TAKEN_END'] == undefined) // no end date specified
            {
              showCriteria.push('taken after <i>' + days[parseInt(objDate.getDay())] + ', ' + months[parseInt(objDate.getMonth())] + ' ' + objDate.getDate() + ', ' + objDate.getFullYear() + '</i>');
            }
            else // and end date was specified
            {
              thisTime = parseInt(params[i]);
              checkTime= parseInt(params['DATE_TAKEN_END']);
              if((thisTime + 86400) >= checkTime) // time span is one day
              {
                showCriteria.push('taken on <i>' + days[parseInt(objDate.getDay())] + ', ' + months[parseInt(objDate.getMonth())] + ' ' + objDate.getDate() + ', ' + objDate.getFullYear() + '</i>');
              }
              else // end date is specified but more than one day away
              {
                showCriteria.push('taken after <i>' + days[parseInt(objDate.getDay())] + ', ' + months[parseInt(objDate.getMonth())] + ' ' + objDate.getDate() + ', ' + objDate.getFullYear() + '</i>');
              }
            }
            doAppend = true;
            break;
          case 'DATE_TAKEN_END':
            objDate = new Date();
            objDate.setTime(params[i] * 1000);
            if(params['DATE_TAKEN_START'] == undefined) // no start date specified
            {
              showCriteria.push('taken before <i>' + days[parseInt(objDate.getDay())] + ', ' + months[parseInt(objDate.getMonth())] + ' ' + objDate.getDate() + ', ' + objDate.getFullYear() + '</i>');
            }
            else // a start date was specified
            {
              thisTime = parseInt(params[i]);
              checkTime= parseInt(params['DATE_TAKEN_START']);
              if(thisTime > (checkTime + 86400)) // time span is one day
              {
                showCriteria.push('and before <i>' + days[parseInt(objDate.getDay())] + ', ' + months[parseInt(objDate.getMonth())] + ' ' + objDate.getDate() + ', ' + objDate.getFullYear() + '</i>');
              }
            }
            doAppend = true;
            break;
          case 'TAGS':
            showCriteria.push('tagged with <i>' + params[i] + '</i>');
            doAppend = true;
            break;
          case 'UNTAGGED':
            showCriteria.push('without any tags');
            doAppend = true;
            break;
          case 'ORDER':
            if(params[i] == 'VIEWS')
            {
              showCriteria.push('ordered by <i>most viewed</i>');
            }
            doAppend = true;
            break;
          case 'LIMIT':
            doAppend = true;
            break;
        }

        if(doAppend ==  true)
        {
          urlAppend += '&' + i + '=' + params[i];
        }
      }
    }

    if(showCriteria.length > 0)
    {
      clrUrl = isUser ? '/?action=fotobox.fotobox_myfotos' : '?action=group.photos&group_id='+params.GROUP_ID;
      _html += '<div style="text-align:center; padding:5px 0px 10px 0px;" class=" bold">Viewing photos ' + showCriteria.join(' ') + ' | <a href="'+clrUrl+'">clear filter</a></div>';
      addHeight += 40;
    }

    _html += '<div style="border-bottom:'+borderStyle+'; width:'+(cols*rowWidth+cols)+'px;"></div>'; // add cols to product because of border-left w/ 1px

    for(i in data)
    {
      if(i != 'MISC')
      {
        i = parseInt(i);
        if(i % cols == 0)
        {
          _html += '<div style="border-left:'+borderStyle+'; height:'+rowHeight+'px; float:left;"></div>';
          totalRows++;
        }

        _html +='<div style="width:'+rowWidth+'px; height:'+rowHeight+'px; border-right:'+borderStyle+'; border-bottom:'+borderStyle+'; float:left;">';

        if(i < data.MISC.TOTAL_ROWS)
        {
          foto = data[i];
          offset = fbOpts['OFFSET'] + i;
          _html +=' <div style="padding-top:8px; padding-left:8px;">'
                + '   <div style="padding-top:5px; padding-left:15px;">'
                + '     <div class="foto_border"><div class="foto_inside"><a href="/xml_result?action=foto_popup'+urlAppend+'&OFFSET='+(fbOpts['OFFSET']+i)+'" class="lbOn" title="edit and view larger photo"><img src="/photos'+foto.P_THUMB_PATH+'?timestamp='+parseInt(Math.random()*100000)+'" width="'+thumbWidth+'" height="'+thumbHeight+'" border="0" /></a></div></div>'
                + '   </div>'
                + '   <div style="padding-top:8px; padding-left:75px;">'
                + '     <a href="javascript:tb.add('+foto.P_ID+');" class="plain" title="add to Tool Box">'
                + '       <img src="images/toolbox.gif" width="14" height="11" border="0" align="absmiddle" />'
                + '       add'
                + '     </a>'
                + '     <br/>'
                + '   </div>'
                + ' </div>';
        }

        _html += '</div>';

        items[items.length] = foto.P_ID;

        if((i+1) % cols == 0 && i > 0)
        {
          _html += '<div style="height:'+(rowHeight+1)+'px; width:1px;"></div>'; // add one to row height since there's a border-bottom w/ 1px
          colTrack = 1; // set coltrack to 0 since it's going to be incremented below.
        }

        // this potentially breaks out of the loop and the code below this might not be run if it exits on that element
        if(i >= (data.MISC.TOTAL_ROWS - 1) && (colTrack == 1 || colTrack == (cols+1))) // check if any fotos are left (use i) and if we're at the first or last column
        {
          counter++;
          break;
        }

        colTrack++;
        counter++;
      }
    }

    // this fills the bottom row with empty boxes if there are less than {cols} columns
    var extra = cols - (counter % cols);
    if(extra < cols)
    {
      for(i=0; i<extra; i++)
      {
        _html += '<div style="height:'+rowHeight+'px; width:'+rowWidth+'px; border-right:'+borderStyle+'; border-bottom:'+borderStyle+'; float:left;"></div>';
      }
    }

    _html +='<div style="width:100%; height:10px;"></div>'
          + '<div style="margin-right:10px; float:right;" class="bg_white"><a href="javascript:tb.addAll('+items.join(',')+');" class="plain"><img src="images/toolbox.gif" width="14" height="11" border="0" hspace="3" align="absmiddle" />add all</a></div>';

    adjustFotoboxHeight(totalRows);
  }
  else
  {
    resultMessage = tags.length > 0 ? 'tagged with <span class="italic">' + tags + '</span>' : '';
    _html +='<div style="width:450px; padding:50px; margin:auto;" class="bold">'
          + '<div style="padding-bottom:10px;">Your search for photos ' + resultMessage + ' had 0 results.</div>'
          + '<ul style="margin:auto;">'
          + '<li style="padding-bottom:5px;"><a href="/?action=fotobox.fotobox_myfotos">View all of your photos</a></li>'
          + '<li style="padding-bottom:5px;"><a href="/?action=fotobox.view_all_tags">View all of your tags</a></li>'
          + '<li style="padding-bottom:5px;"><a href="/?action=fotobox.calendar">View your calendar</a></li>'
          + '</ul>'
          + '<div style="margin-top:30px;"><a href="/?action=fotobox.upload_installer"><img src="images/start_uploading_fotos.gif" width="280" height="30" border="0" /></a></div>'
          + '</div>';
  }

  var effect = new fx.Opacity('myFotosLoading', {duration:100, onComplete: function()
                       {
                          $('myFotosContent').style.display = 'block';
                          $('myFotosLoading').style.display = 'none';
                          var onCompleteEffect = new fx.Opacity('myFotosContent', {duration:100}); }
                        }
                       );
  effect.toggle();

  _html = '<div class="bg_white">' + _html + '</div>'; // fix anti alias bug in ie

  $('myFotosContent').innerHTML = _html;
  if($('fbTagSearch') && isUser)
  {
    new Autocompleter.Local("fbTagSearch", "fbTagSearch_auto_complete", userTags, {tokens: ','});
  }

  if(lbExtra == 'lightbox')
  {
    selects = document.getElementsByTagName('select');
    for(i = 0; i < selects.length; i++)
    {
      selects[i].style.visibility = 'hidden';
    }
  }

  initializeLB();
}

function photoEdit(action, photo_id)
{
  $('photoConfirmMessage').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" align="absmiddle" />Please wait...';
  editOptsConfirm.toggle();
  switch(action)
  {
    case 'blur':
    case 'greyscale':
    case 'restore':
    case 'sepia':
    case 'sharpen':
      var myAjax = new Ajax.Request(
        '/xml_result',
        {
          method: 'get',
          parameters: 'action=foto_transform&subaction='+action+'&photoId=' + photo_id,
          onComplete:function(response){ photoEditRsp(response); }
        });
        break;
    case 'rotate':
      var myAjax = new Ajax.Request(
        '/xml_result',
        {
          method: 'get',
          parameters: 'action=foto_transform&subaction='+action+'&photoId=' + photo_id + '&degrees=' + arguments[2] + '&timestamp='+parseInt(Math.random()*100000),
          onComplete:function(response){ photoEditRsp(response); }
        });
      break;
  }
}

function photoEditRsp(response)
{
  if(response.responseText != undefined) // called from photoEdit function
  {
    var data = response.responseText.parseJSON();
    $('photoMainPopup').src = customImageLock(data.P_THUMB_PATH, data.P_KEY, data.P_HASH, data.P_WIDTH, data.P_HEIGHT, 500, 375, data.P_ROTATION) + '-' + '-'+parseInt(Math.random()*100000);
    loadFotos(fbOpts, 'lightbox');
    setTimeout("editOptsConfirm.toggle()", 1000);
  }
  else // called from croping popup
  {
    var data = response;
    $('photoMainPopup').src = customImageLock(data.P_THUMB_PATH, data.P_KEY, data.P_HASH, data.P_WIDTH, data.P_HEIGHT, 500, 375, data.P_ROTATION) + '-' + '-'+parseInt(Math.random()*100000);
    editOptsConfirm.toggle();
    setTimeout("editOptsConfirm.toggle()", 1000);
  }
}

function publishToFacebook(albumName)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=publish_to_facebook&toolbox=1&albumName='+escape(albumName),
      onComplete: publishToFacebookRsp
    }
  );
}

function publishToFacebookRsp(response)
{
  var data = response.responseText.parseJSON();
  console.info(response.responseText);
  $('overlayConfirm').innerHTML = 'Your photos were published to Facebook'; // <br/><a href="http://www.facebook.com/photos.php" target="_blank">Click here to view your album.</a>';
}

function quickSetDialog()
{
  var mode = arguments.length > 0 ? arguments[0] : 1;

  getXmlHttp();

  if(arguments.length > 1)
  {
    extra = arguments[1];
    if(extra.toString().search(/edit/) != -1) // edit quickset
    {
      editId = extra.replace(/edit/,'');
      xmlHttpSend('quickSetDialogRsp('+mode+',extra)', '/xml_result', 'action=user_quick_set_specific&set_id='+editId+'&timestamp='+parseInt(Math.random()*100000));
    }
    else // add child where extra is parent_id
    {
      xmlHttpSend('quickSetDialogRsp('+mode+',extra)', '/xml_result', 'action=user_quick_set_count'+'&timestamp='+parseInt(Math.random()*100000));
    }
  }
  else
  {
    xmlHttpSend('quickSetDialogRsp('+mode+')', '/xml_result', 'action=user_quick_set_count'+'&timestamp='+parseInt(Math.random()*100000));
  }
}

function quickSetDialogRsp()
{
  var pieces= xmlHttpResult.split(',');
  mode  = arguments.length > 0 ? arguments[0] : 1; // this is a non local variable and can't be var'ed
  extra = arguments.length > 1 ? arguments[1] : false; // this is a non local variable and can't be var'ed

  if(pieces[0] == 'true')
  {
    _edit = false;

    if(mode == 1 && pieces[1] == 0)
    {
      mode = 3;
      extra = 0;
    }
    else
    if(mode == 3 && extra != false)
    {
      if(pieces.length > 3) // edit
      {
        _name = pieces[4].replace(/\&comma\;/g, ',');
        _tags = pieces[5].replace(/\&comma\;/g, ',');
        _edit = true;
      }
    }

    element = $('quickSetDialog');
    element.style.border = '1px solid #404040';
    element.style.backgroundColor = '#fefef6';
    element.style.paddingLeft = '5px';
    element.style.paddingTop = '10px';
    element.style.marginLeft = '-5px';
    element.style.marginTop  = '3px';
    element.style.width   = '210px';
    element.style.position   = 'absolute';

    switch(mode)
    {
      case 1: // entry point pick top or sub level quickset
        element.style.height  = '75px';
        element.innerHTML = '<div class="bold" style="padding-bottom:5px;">Create a Tag Folder&nbsp;<span class="f_red">(<a href="javascript:quickSetDialogHide();" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;"><a href="javascript:quickSetTrans(3,0);">Create a Tag Folder</a> (<a href="javascript:void($(\'quickSetDialog\').style.height=\'125px;\'); void($(\'quickSetOrgExplain\').innerHTML=\'QuickSets are always displayed and can have multiple child Tag Folder nested beneath them.\');">?</a>)</div>'
                          + '<div style="padding-bottom:10px;"><a href="javascript:quickSetTrans(2);">Create a sub level Tag Folder</a> (<a href="javascript:void($(\'quickSetDialog\').style.height=\'125px;\'); void($(\'quickSetOrgExplain\').innerHTML=\'Sub level Tag Folder are displayed beneath another QuickSet.\');">?</a>)</div>'
                          + '<div id="quickSetOrgExplain"></div>';
        break;
      case 2: // child ... select parent
        element.style.height  = '85px';
        element.innerHTML = '<div class="bold" style="padding-bottom:5px;">Select Tag Folder location&nbsp;<span class="f_red">(<a href="javascript:quickSetDialogHide();" class="f_red" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;">Select a parent Tag Folder below.</div>'
                          + '<div style="padding-bottom:3px; width:225px; height:125px; overflow:auto;" id="quickSetDialogList">Loading...</div>';
        setTimeout("quickSetLoad(0)", 200);
        break;
      case 3: // enter quick set
        jsFunc = _edit==true?'updateQuickSet':'addQuickSet';
        butText= _edit==true?'save':'add';
        element.style.height  = '135px';
        if(_edit == true)
        {
          extra = extra.replace('edit','');
        }
        element.innerHTML = '<form id="qsForm" onsubmit="void($(\'qsSubmit\').value=\'saving...\'); return '+jsFunc+'($(\'quickSetTags\').value, $(\'quickSetName\').value, extra);">'
                          + '<div class="bold" style="padding-bottom:5px;">'+(_edit==true?'Update your Tag Folder':'Add a new Tag Folder')+'&nbsp;<span class="f_red">(<a href="javascript:quickSetDialogHide();" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;"><div>Folder Name</div><div><input type="text" value="'+(_edit==true?_name:'')+'" id="quickSetName" class="formfield" style="width:140px;" /></div></div>'
                          + '<div style="padding-bottom:3px;"><div>Tag(s)<div class="italic">(separate with commas)</div></div><div><input type="text" autocomplete="off" value="'+(_edit==true?_tags:'')+'" id="quickSetTags" class="formfield" style="width:140px;" /><div id="auto_complete_quickSetTags" class="auto_complete" style="width:140px;"></div></div></div>'
                          + '<div style="padding-bottom:3px;"><input type="submit" id="qsSubmit" value="'+butText+'" class="formbutton" /></div>'
                          + '</form>';
        new Autocompleter.Local("quickSetTags", "auto_complete_quickSetTags", userTags, {tokens: ","});
        break;
      case 4: // close or add another
        manage = 'manage';
        element.style.height  = '100px';
        element.innerHTML = '<div class="bold" style="padding-bottom:10px;">Tag Folders Updated</div>'
                          + '<div style="padding-bottom:3px;" align="center">'
                          + ' <div class="bold" style="padding-bottom:3px;"><a href="javascript:quickSetTrans(1);">Add a QuickSet</a></div>'
                          + ' <div style="padding-bottom:3px;">-- or --</div>'
                          + ' <div class="bold" style="padding-bottom:3px;"><a href="javascript:void(quickSetDialogHide()); void(setTimeout(\'quickSetRefresh(manage)\', 100));">Close this dialog</a></div>';
        break;
      case 5: // prompt to delete quickset
        element.style.width  = '275px';
        element.style.height  = '140px';
        element.innerHTML = '<div class="bold" style="padding-bottom:10px;">Delete a Tag Folder&nbsp;<span class="f_red">(<a href="javascript:quickSetDialogHide();" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;">'
                          + ' <div style="padding-bottom:3px;">You are about to delete a Tag Folder?*</div>'
                          + ' <div style="padding-bottom:6px;" class="bold"><a href="javascript:deleteQuickSet('+extra+');">Continue and delete this Tag Folder</a></div>'
                          + ' <div style="padding-bottom:3px;">-- or --</div>'
                          + ' <div style="padding-bottom:10px;" class="bold"><a href="javascript:quickSetDialogHide();">Cancel</a></div>'
                          + ' <div">*Deleting a Tag Folder deletes all sub Tag Folders</div>';
        break;
      case 100: // display quickset definition
        element.style.width  = '210px';
        element.style.height  = '150px';
        element.innerHTML = '<div class="bold" style="padding-bottom:5px;">What\'s a Tag Folder?&nbsp;<span class="f_red">(<a href="javascript:quickSetDialogHide();" tiele="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:10px;">A Tag Folder is a great way to save your most frequent searches.  By creating a Tag Folder you can use it to perform common searches easily.</div>'
                          + '<div>Your Tag Folders will also appear on your personal page so others can also search for your favorite photos.</div>';
        break;
    }

    if(element.style.display != 'inline')
    {
      element.style.display = 'inline';

      var effect = new fx.Opacity('quickSetDialog', {onComplete: function(){ }} );
      effect.hide();
      effect.toggle();
      //opacity('quickSetDialog', 25, 100, 500, true);
    }
  }
}

function quickSetLoad(parent_id)
{
  getXmlHttp();
  var mouseY = 0; // set div to normal placement - this can only be clicked from one place
  xmlHttpSend('quickSetLoadRsp('+mode+')', '/xml_result', 'action=user_quick_sets&parent_id='+parent_id+'&recurse=0'+'&timestamp='+parseInt(Math.random()*100000));
}

function quickSetLoadRsp()
{
  var pieces = xmlHttpResult.split('~');

  var _html = '<div style="padding-bottom:3px;">'
        + '<select id="setPosition" class="formfield">';

  var counter = 0;
  for(i=1; i<pieces.length; i++)
  {
    if(pieces[i].length > 1)
    {
      internals = pieces[i].split(',');
      _id     = internals[0];
      _parent = internals[1];
      _name   = internals[2].replace('&comma;', ',');
      _name   = _name.replace('&tilde;', '~');
      _order  = internals[3];

      _html += '<option value="'+_id+'">&nbsp;'+_name+'</option>';
    }
  }

  _html +=  '</select>'
        +   '</div>'
        +   '<div><input type="button" class="formbutton" value="continue" onClick=" this.value=\'Please wait...\'; quickSetTrans(3, $(\'setPosition\').value);" /></div>';

  $('quickSetDialogList').innerHTML = _html;
}

function quickSetRefresh()
{
  mode = arguments.length > 0 ? arguments[0] : 'display';
  getXmlHttp();
  xmlHttpSend('quickSetRefreshRsp("'+mode+'")', '/xml_result', 'action=user_quick_sets&parent_id=0&recurse=1'+'&timestamp='+parseInt(Math.random()*100000));
}

// quickSetRefreshRsp is called by numerous functions
function quickSetRefreshRsp()
{
  var mode = arguments.length > 0 ? arguments[0] : 'display';
  var draggables = new Array('quickSetParents');
  var children = new Array();
  var pieces = xmlHttpResult.split('~');
  var _href = '';
  var effect;

  if(pieces[0] == 'true')
  {
    var _html   = '<div id="quickSetContainer">'
            + '<div id="quickSetParents">';
    _br = '';
    _height = 0;
    _prevParentId = pieces.length > 0 ? pieces[0][0] : 0;
    pTrack = cTrack = 0;

    for(i=1; i<pieces.length; i++)
    {
      if(pieces[i].length > 0)
      {
        internals = pieces[i].split(',');
        maxName = mode == 'manage' ? 10 : 13;
        _id     = internals[0];
        _parent = internals[1];
        _name   = internals[2].replace(/&comma;/g, ',');
        _name   = _name.replace(/&tilde;/g, '~');
        _name   = _name.substr(0,maxName) + (_name.length > maxName ? '...' : '');
        _tags   = internals[3].replace(/&comma;/g, ',');
        _tags   = _tags.replace(/&tilde;/g, ',');


        // if not first loop AND
          // if a parent AND previous parent id isn't this id
          // if a child and previous parent id isn't this parent id
        if(i > 1 && ((_parent == 0 && _prevParentId != _id) || (_parent != 0 && _prevParentId != _parent)))
        {
          _html +='</div></div>'; // close child div
        }

        if(_parent == 0)
        {
          //pName = 'quickSetParent'+_id;
          cTrack = 0;
          cName = 'quickSetChildren'+_id;
          if(mode == 'manage')
          {
            _html +=  '<div id="setParent_'+pTrack+'" class="draggerSetParent">' + _br
                  +   ' <div class="quickSetParentRow">' + _br
                  //+   '   <div style="float:left; padding-left:2px; padding-right:3px; cursor: move;" title="click and drag to reorder this Tag Folder"><img src="images/navigation/quick_set_move_dk.gif" width="15" height="15" border="0" class="theDragger" /></div>' + _br
                  +   '   <div style="float:left; padding-left:2px; padding-right:3px;"><img src="images/spacer.gif" width="15" height="15" border="0" /></div>' + _br
                  +   '   <div style="float:left;">'+_name+'</div>' + _br
                  +   '   <div style="float:right; padding-right:3px;">' + _br
                  +   '     <div style="float:left; padding-right:2px;"><a href="javascript:quickSetTrans(3,\'edit'+_id+'\');" onmouseover="mouseY=_getMouseY(event);" title="edit this Tag Folder"><img src="images/navigation/quick_set_edit_dk.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '     <div style="float:left;"><a href="javascript:quickSetTrans(5,\''+_id+'\');" onmouseover="mouseY=_getMouseY(event);" title="delete this Tag Folder"><img src="images/navigation/quick_set_delete_dk.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '   </div>' + _br
                  +   ' </div>' + _br
                  +   ' <div id="'+cName+'" >' + _br; // child div to contain all children
          }
          else
          if(mode == 'display')
          {
            if(location.href.search('fotobox.fotobox_myfotos') > -1)
            {
              _href = "javascript:fbOpts.TAGS='"+_tags+"'; loadFotos(fbOpts);";
            }
            else
            {
              _href = "/?action=fotobox.fotobox_myfotos&TAGS="+escape(_tags);
            }
            _html +=  '<div class="quickSetParentRow">' + _br
                  +   ' <div>' + _br
                  +   '  <div style="float:left; padding-left:2px; padding-right:3px;"><!--'+_id+'_arrow_start--><a href="javascript:qsFunc = function(){ var ef = new fx.Height(\''+cName+'\'); ef.toggle(); _toggle_arrow(\'_arrow_'+_id+'\'); }; qsFunc();" title="show sub Tag Folders"><img src="images/navigation/sub_arrow_close.gif" id="_arrow_'+_id+'" width="15" height="15" border="0" /></a><!--'+_id+'_arrow_end--></div>' + _br
                  +   '  <div style="float:left;"><a href="'+_href+'" class="f_9 f_white bold plain" title="show fotos with tags: '+_tags+'">'+_name+'</a></div>' + _br
                  +   ' </div>' + _br
                  +   '</div>' + _br
                  +   '<div id="'+cName+'">' + _br; // child div to contain all children
          }
          children.push(new Array(_id, 0));
          draggables.push(cName);
          _prevParentId = _id;
          pTrack++;
        }
        else
        {
          if(mode == 'manage')
          {
            _html +=  '<div id="setChild'+_parent+'_'+cTrack+'" class="draggerSetChild">' + _br
                  +   ' <div class="quickSetChildRow">' + _br
                  //+   '   <div style="float:left; padding-left:10px; padding-right:3px; cursor: move;"><img src="images/navigation/quick_set_move_lt.gif" width="15" height="15" border="0" class="theDragger" /></div>' + _br
                  +   '   <div style="float:left; padding-left:10px; padding-right:3px;"><img src="images/spacer.gif" width="15" height="15" border="0" /></div>' + _br
                  +   '   <div style="float:left;">'+_name+'</div>' + _br
                  +   '   <div style="float:right; padding-right:3px;">' + _br
                  +   '     <div style="float:left; padding-right:2px;"><a href="javascript:quickSetTrans(3,\'edit'+_id+'\');"onmouseover="mouseY=_getMouseY(event);" title="edit this Tag Folder"><img src="images/navigation/quick_set_edit_lt.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '     <div style="float:left;"><a href="javascript:quickSetTrans(5,\''+_id+'\');"onmouseover="mouseY=_getMouseY(event);" title="delete this Tag Folder"><img src="images/navigation/quick_set_delete_lt.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '   </div>' + _br
                  +   ' </div>' + _br
                  +   '</div>' + _br;
          }
          else
          if(mode == 'display')
          {
            if(location.href.search('fotobox.fotobox_myfotos') > -1)
            {
              _href = "javascript:fbOpts.TAGS='"+_tags+"'; loadFotos(fbOpts);";
            }
            else
            {
              _href = "/?action=fotobox.fotobox_myfotos&TAGS="+escape(_tags);
            }
            _html +=  '<div class="quickSetChildRow">' + _br
                  +   ' <div style="padding-left:23px;"><a href="'+_href+'" class="childLink plain" title="show fotos with tags: '+_tags+'">'+_name+'</a></div>' + _br
                  +   '</div>' + _br;
          }
          _prevParentId = _parent;
          cTrack++;
          children[children.length-1][1]++;
        }
        _height += 22;
      }
    }

    _html += '</div>' // last quickSetChildren
          +  '</div>' // last quickSetParent
          +  '</div>' // quickSetParents
          +  '</div>';  // quickSetContainer

    if(i<=2)
    {
      /*
      _html = '<div style="height:40px; padding-left:2px; padding-top:15px; padding-bottom:15px;" class="bg_lite">'
            + '<div>You have not created a Tag Folder.  <a href="javascript:quickSetTrans(1);">Create one now</a>.</div>'
            + '<div style="padding-top:5px;"><a href="javascript:quickSetTrans(100);">What is a Tag Folder?</a></div>'
            + '</div>';
      */
      _hasQuickSets = false;
      _html = '<div style="padding-left:12px; padding-top:5px;"><a href="javascript:quickSetTrans(100);" style="color:blue;" class="plain">What is a Tag Folder?</a></div>';
    }
    else
    {
      leftSpacer = cTrack == 0 ? 3 : 14;
      for(i=0;i<children.length;i++)
      {
        if(children[i][1] == 0)
        {
          regex = "\<\!\-\-"+children[i][0]+"_arrow_start\-\-\>.*<\!\-\-"+children[i][0]+"_arrow_end\-\-\>";
          re = new RegExp(regex);
          _html = _html.replace(re, '<img src="images/spacer.gif" width="'+leftSpacer+'" height="14" border="0" />');
        }
      }
      _hasQuickSets = true;
    }

    if(mode == 'display' || mode == 'manage')
    {
      //$('quickSets').style.height = _height+'px';
      $('quickSets').innerHTML=_html;
      /*tmphtml = _html.replace(/\</g, '&lt;');
      tmphtml = tmphtml.replace(/\>/g, '&gt;');
      tmphtml = tmphtml.replace(/br/g, '<br/>');
      $('output').innerHTML=tmphtml;*/
    }

    if(mode == 'manage' || mode == 'relist')
    {
      /*for(m=0; m<draggables.length; m++)
      {
        thisId = draggables[m];
        thisOnly = m > 0 ? 'draggerSetChild' : 'draggerSetParent';
        Sortable.create(thisId,{tag:'div',only:thisOnly,constraint:'vertical',overlap:'vertical',onUpdate:function(request){  new Ajax.Updater('quickSetsAjax', '/xml_result?action=user_quick_set_reorder&id='+request['id']+'&element='+request['id'], {method:'post', postBody:Sortable.serialize(request['id']),asynchronous:true, evalScripts:true}); quickSetDialogWait(); }})
      }*/

      //$('quickSetToggle').innerHTML = 'Display<br/>Tag Folders';
      $('quickSetToggle').href = "javascript:quickSetRefresh('display');";
    }
    else
    {
      for(i=0;i<children.length;i++)
      {
        if(children[i][1] > 0)
        {
          effect = new fx.Height('quickSetChildren'+children[i][0]);
          effect.hide();
        }
      }

      //$('quickSetToggle').innerHTML = 'Manage<br/>Tag Folders';
      if(_hasQuickSets == true)
      {
        $('quickSetToggle').href = "javascript:quickSetRefresh('manage');";
      }
      else
      {
        $('quickSetToggle').href = "javascript:quickSetTrans(1);";
      }
    }
  }
  else
  {
    //alert('error occurred\n' + xmlHttpResult);
  }
}

// refreshTagsRsp is called by numerous functions
function refreshTagsRsp()
{
  $('tagsButton').value = 'Tag';
  $('tagsField').value = '';
}

function removeTag(foto_id, tag)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {method: 'get', parameters: 'action=foto_tags_remove&foto_id='+foto_id+'&tags='+tag, onComplete: function(response){ removeTagRsp(response, 'tag_'+foto_id+tag); }}
  );
}

function removeTagRsp(response, elementId)
{
  var data = response.responseText.parseJSON();
  var effect = new fx.Height(elementId, {duration:200, onComplete: function(){ new Element.remove(elementId); if(data.length == 0 && $('tags') != undefined){ $('tags').innerHTML = '<div class="bold">No Tags</div>'; }}});
  effect.toggle();
}

function removeTags(tag)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=foto_tags_remove&toolbox=1&tags='+tag,
      onComplete: removeTagsRsp
    }
  );
}

function removeTagsRsp()
{
  $('overlayConfirm').innerHTML = 'Tags were removed from your photos.';
  var effect = new fx.Opacity('overlayConfirm', {duration:2000});
  effect.toggle();
  loadFotos(fbOpts);
}

function setPrivacyOnFotos(element)
{
  var i = 0;
  var privacy = 0;
  for(i; i<element.length; i++)
  {
    if(element[i].checked == true)
    {
      switch(element[i].value)
      {
        case 'PERM_PHOTO_PUBLIC':
          privacy += 1;
          break;
        case 'PERM_PHOTO_COMMENT':
          privacy += 2;
          break;
        case 'PERM_PHOTO_DOWNLOAD':
          privacy += 8;
          break;
        case 'PERM_PHOTO_PRINT':
          privacy += 32;
          break;
      }
    }
  }

  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=foto_privacy_set&toolbox=1&privacy='+privacy,
      onComplete: setPrivacyOnFotosRsp
    }
  );
}

function setPrivacyOnFotosRsp(response)
{
  var data = response.responseText.parseJSON();
  if(data == true)
  {
    hideOverlayForm();
  }
}

function shareFotosWithGroup(groupId, fotoIds)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {method: 'post', parameters: 'action=group_fotos_add&groupId='+groupId+'&fotoIds='+fotoIds, onComplete: function(response){ shareFotosWithGroupRsp(response); }}
  );
}

function shareFotosWithGroupRsp(response)
{
  var data = response.responseText.parseJSON();
  if(data.FOTOS != false)
  {
    $('fotoShareOverlayForm').innerHTML = '<div style="width:100%; text-align:center; margin-top:40px;" class="f_14 bold">You successfully shared ' + data.FOTOS.length + ' photos width ' + data.GROUP.G_NAME + '.</div>'
                                        + '<div style="float:right; margin:10px 20px 0px 0px;"><input type="button" value="Close" class="formbutton" onclick="hideShareOverlayForm();" /></div>';
  }
}

function updateDateTaken(id, datetime)
{
  $('dateTimeField').rel = datetime;
  $('dateTimeField').value = 'Saving...';
  $('dateTimeField').disable();
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=foto_update_date_taken&fotoId='+id+'&datetime='+escape(datetime),
      onComplete: updateDateTakenRsp
    }
  );
}

function updateDateTakenRsp(response)
{
  var data = response.responseText.parseJSON();
  if(data > 0)
  {
    setTimeout("$('dateTimeField').value = $('dateTimeField').rel; $('dateTimeField').enable();", '1000');
  }
}

function updateQuickSet(tags, name, edit_id)
{
  var _params = 'action=user_quick_set_update&tags='+tags+'&name='+name+'&set_id='+edit_id+'&timestamp='+parseInt(Math.random()*100000);
  getXmlHttp();
  xmlHttpSend('genericQuickSetRsp()', '/xml_result', _params);
  return false;
}
// updateQuickSet calls genericQuickSetRsp
