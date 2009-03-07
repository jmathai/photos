function groupAddQuickSet(tags, name, parent, group_id)
{
  _params = 'action=group_quick_set_add&tags='+tags+'&name='+name+'&parent='+parent+'&group_id='+group_id+'&timestamp='+parseInt(Math.random()*100000);
  getXmlHttp();
  xmlHttpSend('groupGenericQuickSetRsp()', '/xml_result', _params);
  return false;
}
// groupAddQuickSet calls groupGenericQuickSetRsp

function groupDeleteQuickSet(id, group_id)
{
  _params = 'action=group_quick_set_delete&set_id='+id+'&group_id='+group_id+'&timestamp='+parseInt(Math.random()*100000);
  getXmlHttp();
  xmlHttpSend('groupGenericQuickSetRsp()', '/xml_result', _params);
}

function groupGenericQuickSetRsp()
{
  _pieces = xmlHttpResult.split(',');
  if(_pieces[0] == 'true')
  {
    setTimeout("groupQuickSetRefresh('display', "+_pieces[1]+")", 100);
    setTimeout("groupQuickSetDialogHide()", 150);
  }
}

function groupQuickSetLoad(parent_id, group_id)
{
  getXmlHttp();
  mouseY = 0; // set div to normal placement - this can only be clicked from one place
  xmlHttpSend('groupQuickSetLoadRsp('+mode+','+group_id+')', '/xml_result', 'action=group_quick_sets&parent_id='+parent_id+'&group_id='+group_id+'&recurse=0'+'&timestamp='+parseInt(Math.random()*100000));
}

function groupQuickSetLoadRsp()
{
  pieces = xmlHttpResult.split('~');
  group_id = arguments[1];

  var _html = '<div style="padding-bottom:3px;">'
        + '<select id="setPosition" class="formfield">';

  counter = 0;
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
        +   '<div><input type="button" class="formbutton" value="continue" onClick=" this.value=\'Please wait...\'; groupQuickSetTrans(3, '+group_id+', $(\'setPosition\').value);" /></div>';

  $('groupQuickSetDialogList').innerHTML = _html;
}

function groupQuickSetRefresh()
{
  mode = arguments.length > 0 ? arguments[0] : 'display';
  group_id = arguments[1];
  getXmlHttp();
  xmlHttpSend('groupQuickSetRefreshRsp("'+mode+'", '+group_id+')', '/xml_result', 'action=group_quick_sets&parent_id=0&recurse=1'+'&group_id='+group_id+'&timestamp='+parseInt(Math.random()*100000));
}

function groupQuickSetRefreshRsp()
{
  mode = arguments.length > 0 ? arguments[0] : 'display';
  group_id = arguments[1];
  draggables = new Array('groupQuickSetParents');
  children = new Array();
  pieces = xmlHttpResult.split('~');
  
  if(pieces[0] == 'true')
  {
    var _html   = '<div id="groupQuickSetContainer">'
            + '<div id="groupQuickSetParents">';
    _br = '';
    _height = 0;
    _prevParentId = pieces.length > 0 ? pieces[0][0] : 0;
    pTrack = cTrack = 0;
    
    for(i=1; i<pieces.length&& i < 10; i++)
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
          cName = 'groupQuickSetChildren'+_id;
          if(mode == 'manage')
          {
            _html +=  '<div id="setParent_'+pTrack+'" class="draggerSetParent">' + _br
                  +   ' <div class="groupQuickSetParentRow">' + _br
                  +   '   <div style="float:left; padding-left:2px; padding-right:3px; cursor: move;" title="click and drag to reorder this Tag Folder"><img src="images/navigation/quick_set_move_dk.gif" width="15" height="15" border="0" class="theDragger" /></div>' + _br
                  +   '   <div style="float:left;">'+_name+'</div>' + _br
                  +   '   <div style="float:right; padding-right:3px;">' + _br
                  +   '     <div style="float:left; padding-right:2px;"><a href="javascript:groupQuickSetTrans(3,'+group_id+',\'edit'+_id+'\');" onmouseover="mouseY=_getMouseY(event);" title="edit this Tag Folder"><img src="images/navigation/quick_set_edit_dk.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '     <div style="float:left;"><a href="javascript:groupQuickSetTrans(5,'+group_id+',\''+_id+'\');" onmouseover="mouseY=_getMouseY(event);" title="delete this Tag Folder"><img src="images/navigation/quick_set_delete_dk.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '   </div>' + _br
                  +   ' </div>' + _br
                  +   ' <div id="'+cName+'" >' + _br; // child div to contain all children
          }
          else
          if(mode == 'display')
          {
            _html +=  '<div class="groupQuickSetParentRow">' + _br
                  +   ' <div>' + _br
                  +   '  <div style="float:left; padding-left:2px; padding-right:3px;"><!--'+_id+'_arrow_start--><a href="javascript:_toggle(\''+cName+'\'); _toggle_arrow(\'_arrow_'+_id+'\');" title="show sub QuickSets"><img src="images/navigation/sub_arrow_close.gif" id="_arrow_'+_id+'" width="15" height="15" border="0" /></a><!--'+_id+'_arrow_end--></div>' + _br
                  +   '  <div style="float:left;"><a href="/fotobox?tags='+escape(_tags)+'" class="f_9 f_white bold" style="text-decoration:none;" target="_fotobox" onclick="top.frames[\'_fotobox\'].location.href=this.href; return false;" title="show fotos with tags: '+_tags+'">'+_name+'</a></div>' + _br
                  +   ' </div>' + _br
                  +   '</div>' + _br
                  +   '<div id="'+cName+'" style="display:none;">' + _br; // child div to contain all children
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
                  +   ' <div class="groupQuickSetChildRow">' + _br
                  +   '   <div style="float:left; padding-left:10px; padding-right:3px; cursor: move;"><img src="images/navigation/quick_set_move_lt.gif" width="15" height="15" border="0" class="theDragger" /></div>' + _br
                  +   '   <div style="float:left;">'+_name+'</div>' + _br
                  +   '   <div style="float:right; padding-right:3px;">' + _br
                  +   '     <div style="float:left; padding-right:2px;"><a href="javascript:groupQuickSetTrans(3,'+group_id+',\'edit'+_id+'\');"onmouseover="mouseY=_getMouseY(event);" title="edit this Tag Folder"><img src="images/navigation/quick_set_edit_lt.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '     <div style="float:left;"><a href="javascript:groupQuickSetTrans(5,'+group_id+',\''+_id+'\');"onmouseover="mouseY=_getMouseY(event);" title="delete this Tag Folder"><img src="images/navigation/quick_set_delete_lt.gif" width="14" height="14" border="0" /></a></div>' + _br
                  +   '   </div>' + _br
                  +   ' </div>' + _br
                  +   '</div>' + _br;
          }
          else
          if(mode == 'display')
          {
            _html +=  '<div class="groupQuickSetChildRow">' + _br
                  +   ' <div style="padding-left:23px;"><a href="/fotobox?tags='+escape(_tags)+'" onclick="top.frames[\'_fotobox\'].location.href=this.href; return false;" class="childLink" target="_fotobox" title="show fotos with tags: '+_tags+'">'+_name+'</a></div>' + _br
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
      _html = '<div style="padding-left:12px; padding-top:5px;"><a href="javascript:groupQuickSetTrans(100,'+group_id+');" style="color:blue;" class="plain">What is a QuickSet?</a></div>';
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
      $('groupQuickSets').innerHTML=_html;
      /*tmphtml = _html.replace(/\</g, '&lt;');
      tmphtml = tmphtml.replace(/\>/g, '&gt;');
      tmphtml = tmphtml.replace(/br/g, '<br/>');
      $('output').innerHTML=tmphtml;*/
    }

    if(mode == 'manage' || mode == 'relist')
    {
      for(m=0; m<draggables.length; m++)
      {
        thisId = draggables[m];
        thisOnly = m > 0 ? 'draggerSetChild' : 'draggerSetParent';
        Sortable.create(thisId,{tag:'div',only:thisOnly,constraint:'vertical',overlap:'vertical',handle:'theDragger',onUpdate:function(request){  new Ajax.Updater('groupQuickSetsAjax', '/xml_result?action=group_quick_set_reorder&group_id='+group_id+'&id='+request['id']+'&element='+request['id'], {method:'post', postBody:Sortable.serialize(request['id']),asynchronous:true, evalScripts:true}); quickSetDialogWait(); }})
      }

      //$('quickSetToggle').innerHTML = 'Display<br/>Tag Folders';
      $('groupQuickSetToggle').href = "javascript:groupQuickSetRefresh('display', "+group_id+");";
      $('groupQuickSetToggle').title= "display Tag Folders so they are clickable";
    }
    else
    {
      //$('quickSetToggle').innerHTML = 'Manage<br/>Tag Folders';
      if(_hasQuickSets == true)
      {
        $('groupQuickSetToggle').href = "javascript:groupQuickSetRefresh('manage', "+group_id+");";
        $('groupQuickSetToggle').title= "manage Tag Folders :: edit, delete and reorder";
      }
      else
      {
        $('groupQuickSetToggle').href = "javascript:groupQuickSetTrans(1,"+group_id+");";
      }
    }
  }
  else
  {
    //alert('error occurred\n' + xmlHttpResult);
  }
}

function groupMemberAdd(div_id, who, group_id, numberOfMembers)
{
  getXmlHttp();
  xmlHttpSend('groupMemberAddRsp()', '/xml_result', 'action=groupMemberAdd&div_id=' + div_id + '&who=' + who + '&group_id=' + group_id + '&numberOfMembers=' + numberOfMembers);
  
  return false;
}

function groupMemberAddRsp()
{
  var result = eval(xmlHttpResult);
  div_id = result[0];
  who = result[1];
  numberOfMembers = parseInt(result[2]);
  
  _layer = $(div_id);
  _layer.innerHTML = '<div style="float:left; padding-top:38px; padding-left:25px;" class="f_8 bold">Request Sent</div>';
  _layer.style.display = 'inline';
  setTimeout("opacity(div_id, 100, 0, 500, true)", 1000);
  
  $('_addMemberIcon_' + who).innerHTML = '';
  $('_status_' + who).innerHTML = 'Pending';
}

function groupMemberDelete(div_id, who, group_id, numberOfMembers)
{
  getXmlHttp();
  xmlHttpSend('groupMemberDeleteRsp()', '/xml_result', 'action=groupMemberDelete&div_id=' + div_id + '&who=' + who + '&group_id=' + group_id + '&numberOfMembers=' + numberOfMembers);
  
  return false;
}

function groupMemberDeleteRsp()
{
  var result = eval(xmlHttpResult);
  div_id = result[0];
  who = result[1];
  numberOfMembers = parseInt(result[2]);
  numberOfMembers--;
  
  _layer = $(div_id);
  _layer.innerHTML = '<div style="float:left; padding-top:38px; padding-left:25px;" class="f_8 bold">Member Deleted</div>';
  _layer.style.display = 'inline';
  setTimeout("opacity(div_id, 100, 0, 500, true)", 1000);
  
  setTimeout("opacity('_member_" + who + "', 100, 0, 500, true)", 1000);
  
  $('_membersTotal').innerHTML = 'Total Members ' + numberOfMembers;
}

function groupMemberSearch(mlOpts)
{
  var params= arguments[0];
  
  var sendParams = '';
  for(i in params)
  {
    switch(i)
    {
      case 'DIV_ID':
        sendParams += '&DIV_ID=' + params[i];
        showCriteria = true;
        break;
      case 'GROUP_ID':
        sendParams += '&GROUP_ID=' + params[i];
        showCriteria = true;
        break;
      case 'OFFSET':
        sendParams += '&OFFSET=' + params[i];
        break;
      case 'LIMIT':
        sendParams += '&LIMIT=' + params[i];
        break;  
      case 'SEARCH_ITEM':
        sendParams += '&SEARCH_ITEM=' + params[i];
        break;
    }
  }
  
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=groupMemberSearch' + sendParams, 
    onComplete:function(response){ groupMemberSearchRsp(response, params['MODERATOR']); }
  });
}

function groupMemberSearchRsp(response, moderator)
{
  //var result = eval(xmlHttpResult);
  var result = response.responseText.parseJSON();
  params = result['PARAMS'];
  rs = result['RESULT'];

  if(rs.length != 0)
  {
    var _html = '';
    var color = '#D3D3D3';
    var count = 1;
    var d = new Date();
    
    for(i = 0; i < rs.length; i++ )
    {
      if(count % 2 == 0)
      {
        count = 1;
        color = '#EFEFEF';
      }
      else 
      {
        count++;
        color = '#D3D3D3';
      }
      
      var month=new Array(12)
      month[0]="January"
      month[1]="February"
      month[2]="March"
      month[3]="April"
      month[4]="May"
      month[5]="June"
      month[6]="July"
      month[7]="August"
      month[8]="September"
      month[9]="October"
      month[10]="November"
      month[11]="December"
  
      d.setTime(parseInt(rs[i]['U_JOINED']*1000));
      dateStr = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
      
      if(rs[i]['AVATAR'] != '')
      {
        avatarSrc = params['PATH_FOTO'] + rs[i]['AVATAR'];
      }
      else
      {
        avatarSrc = 'images/avatar.jpg';
      }

      _html += '<div id="_member_' + rs[i]['U_ID'] + '" style="height:50px; overflow:hidden; background-color:' + color + '; border-bottom:2px solid white;">';
      _html += '<div style="float:left; width:45px; height:100%; border-right:1px solid white;">';
      _html += '<div style="float:left; padding-top:15px; height:100%; padding-left:3px; padding-right:3px; border-right:1px solid white;">';
      _html += '<div style="width:16px;"><a href="javascript:void(pm_newMessage(\'_newMessage_' + rs[i]['U_ID'] + '\', ' + rs[i]['U_ID'] + '));"><img src="/images/icons/chat_bubble_16x16.png" class="png" width="16" height="16" border="0" title="Send private message" /></a></div>';
      _html += '<div id="_newMessage_' + rs[i]['U_ID'] + '" style="display:block;"></div>';
      _html += '</div>';
      
      if(moderator == true)
      {
      _html += '<div style="float:left; padding-top:15px; padding-left:3px; padding-right:3px;">';
      _html += '<div><a href="javascript:void(groupMemberDeleteConfirmation(\'_deleteMember_' + rs[i]['U_ID'] + '\', ' + rs[i]['U_ID'] + ', ' + params['GROUP_ID'] + ', ' + params['TOTAL_ROWS'] + '));" title="Delete member"><img src="/images/icons/delete_16x16.png" class="png" width="16" height="16" border="0" /></a></div>';
      _html += '<div id="_deleteMember_' + rs[i]['U_ID'] + '" style="display:block; z-index:75;"></div>';
      _html += '</div>';
      }
      
      _html += '</div>';
      _html += '<div style="float:left; padding-top:5px; padding-left:15px;"><a href="http://' + params['FF_SERVER_NAME'] + '/users/' + rs[i]['U_USERNAME'] + '"><img src="' + avatarSrc + '" border="0" width="40" height="40" /></a></div>';
      _html += '<div style="padding-top:17px;">';
      _html += '<div style="float:left; width:300px; padding-left:15px;"><a href="http://' + params['FF_SERVER_NAME'] + '/users/' + rs[i]['U_USERNAME'] + '">' + rs[i]['U_USERNAME'] + '</a></div>';
      _html += '<div style="float:left; width:175px;">' + dateStr + '</div>';
      _html += '<div>' + rs[i]['NUMBER_FOTOS'] + '</div>';
      _html += '</div>';
      _html += '</div>';
    }
    
    var page = parseInt((params['OFFSET'] / params['LIMIT']) + 1);
    var totalRows = Math.ceil(params['TOTAL_ROWS']/params['LIMIT']);
    var pg = new Pager({current:page,total:totalRows,pagesDisplay:6,itemsPerPage:parseInt(params['LIMIT']),varStart:'mlOpts[\'OFFSET\']',varLimit:'mlOpts[\'LIMIT\']',jsFunc:'groupMemberSearch',opts:'mlOpts'});
    
    if(pg.generate() == 1)
    {
      _html += '<br clear="all" />';
      _html += '<div style="float:right;">';
      _html += '<span style="float:left">Showing ' + params['TOTAL_ROWS'] + ' members</span>';
      _html += '</div>';
    }
    else
    {
      _html += '<br clear="all" />';
      _html += '<div style="float:right;">';
      _html += '<span style="float:left">Pages: &nbsp;</span>';
      _html += '<span style="float:left;">' + pg.first() + '</span>';
      _html += '<span style="float:left; padding-left:3px;">' + pg.previous() + '</span>';
      _html += '<span style="float:left; padding-right:3px; padding-left:3px;">' + pg.generate() + '</span>';
      _html += '<span style="float:left; padding-right:3px;">' + pg.next() + '</span>';
      _html += '<span>' + pg.last() + '</span>';
      _html += '</div>';
    }
  }
  else
  {
    _html = '<div style="padding-left:10px; padding-top:10px;" class="f_8">There are no members to show</div>';
  }
  
  $(params['DIV_ID']).innerHTML = _html;
}

function groupShareGetPhotos( tags, group_id, moderator )
{
  var _offset = arguments.length > 3 ? arguments[3] : 0;
  var _limit = arguments.length > 4 ? arguments[4] : 18;
  
  if(moderator == true)
  {
    groupShare = 2;
  }
  else
  {
    groupShare = 1;
  }
  
  _layer = $('_groupShareDataPhotos');
  _layer.innerHTML = 'Loading...';
  
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=fotos_search&TAGS=' + tags + '&OFFSET=' + _offset + '&LIMIT=' + _limit + '&GROUP_ID=' + group_id + '&GROUP_SHARE=' + groupShare, 
      onComplete: function(response){ groupShareGetPhotosRsp(response, group_id, moderator); refreshTagsRsp(); }
    });
}

function groupShareGetPhotosRsp(response, group_id, moderator)
{
  var data = response.responseText.parseJSON();
  var hasFotos = false;
  
  var _html = '<div align="center">';
  _k = 1;
  
  for( i in data )
  {
    if(i != 'MISC')
    {
      i = parseInt(i);
     
      if(data[i].P_ORIG_ID != null)
      {
        _html += '<div id="_photo_' + data[i].P_ORIG_ID + '" style="float:left; opacity:.5; width:85px; padding-right:20px; padding-bottom:30px;">';
        _html += '<div><img src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></div>';
        _html += '<div style="text-align:center;" class="f_red">Shared</div>';
        _html += '</div>';
      }
      else
      {
        _html += '<div id="_photo_' + data[i].P_ID + '" style="float:left; width:85px; padding-right:20px; padding-bottom:30px;">';
        _html += '<div><a href="javascript:void(0);" onclick="groupSharePhoto(' + data[i].P_ID + ', ' + group_id + ')" title="Click to share photo"><img src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></a></div>';
        _html += '<div style="text-align:center;">Not shared</div>';
        _html += '</div>';
      }
      
      _html += '</div>';
      
      if( _k % 6 == 0 )
      {
        _html += '<br clear="left" />';
        _k = 0;
      }
      
      _k++;
      hasFotos = true;
    }
  }
  _html += '</div>';
    
  _layer = $('_groupShareDataPhotos');
  
  if(hasFotos == true)
  {
    _layer.innerHTML = _html;
    _offset = parseInt(data.MISC.OFFSET);
    _limit = parseInt(data.MISC.LIMIT);
    _lastFoto = parseInt(_offset + _limit);
    
    // Previous Link
    if(_offset > 0)
    {
      $('_groupSharePagingPhotos').innerHTML = '<a href="javascript:void(0);" onclick="groupShareGetPhotos( $(\'_groupShareSearchBoxPhotos\').value,' + group_id + ',' + moderator + ',' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLink">Previous</a>&nbsp;&nbsp;';
    }
    else
    {
      $('_groupSharePagingPhotos').innerHTML = '&nbsp;&nbsp;';
    }
    
    // Next Link
    if(data.MISC.TOTAL_ROWS > _lastFoto)
    {
      $('_groupSharePagingPhotos').innerHTML += '<a href="javascript:void(0);" onclick="groupShareGetPhotos( $(\'_groupShareSearchBoxPhotos\').value,' + group_id + ',' + moderator + ',' + (_offset+_limit) + ',' + _limit + ');" id="PageNextLink">Next</a>';
    }
    else
    {
      $('_groupSharePagingPhotos').innerHTML += '&nbsp;&nbsp;';
    }
  }
  else
  {
    _layer.innerHTML = 'Sorry no photos.';
  }
}

function groupShareGetSlideshows( tags, group_id, moderator, user_id )
{
  var _offset = arguments.length > 4 ? arguments[4] : 0;
  var _limit = arguments.length > 5 ? arguments[5] : 4;
  
  _layer = $('_groupShareDataSlideshows');
  _layer.innerHTML = 'Loading...';
  
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=flix_by_tags&TAGS=' + tags + '&group_id=' + group_id + '&user_id=' + user_id + '&MODERATOR=' + moderator + '&OFFSET=' + _offset + '&LIMIT=' + _limit, 
    onComplete: function(response){ groupShareGetSlideshowsRsp(response, group_id, moderator); refreshTagsRsp(); }
  });
}

function groupShareGetSlideshowsRsp(response, group_id, moderator)
{ 
  var data = response.responseText.parseJSON();
  var _proceed = false;
  
  var _html = '';
  for( i = 0; i < data['MISC']['TOTAL_FLIX']; i++ )
  {
    if(data['FLIX_DATA'][i] != null)
    { 
      _title = data['FLIX_DATA'][i]['US_NAME'];
      if(_title.length > 10)
      {
        _title = _title.substring(0, 10) + '...';
      }
      _link  = "groupShareSlideshow(" + data['FLIX_DATA'][i]['US_ID'] + ", " + group_id + ");";
      
      if(data['FLIX_DATA'][i]['US_UF_ORIG_ID'] != null)
      {
        _html += '<div id="_slideshow_' + data['FLIX_DATA'][i]['US_ID'] + '" style="opacity:.5;">';
        _html +=  '<div style="background:url(images/board_flix_bg.gif) repeat-x; margin-bottom:3px; height:85px;">'
              +   ' <div style="float:left; padding-top:5px; padding-bottom:5px; padding-left:1px;">'
              +   '   <div style="padding-right:4px;"><img src="/photos' + data['FLIX_DATA'][i]['P_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" /></div>'
              +   ' </div>'
              +   ' <div style="float:left; padding-top:10px;">'
              +   '   <div class="bold">'
              +   _title
              +   '   </div>'
              +   '   <div>' + data['FLIX_DATA'][i]['US_FOTO_COUNT'] + ' photos</div>'
              +   '   <div>' + data['FLIX_DATA'][i]['US_VIEWS'] + ' views</div>'
              +   '   <div class="f_red">Shared</div>'
              +   ' </div>'
              +   ' <br clear="all" />'
              +   '</div>';
        _html += '</div>';
      }
      else
      {
        _html += '<div id="_slideshow_' + data['FLIX_DATA'][i]['US_ID'] + '">';
        _html +=  '<div style="background:url(images/board_flix_bg.gif) repeat-x; margin-bottom:3px; height:85px;">'
              +   ' <div style="float:left; padding-top:5px; padding-bottom:5px; padding-left:1px;">'
              +   '   <a href="javascript:void(0);" onclick="' + _link + '" title="Click to share slideshow">'
              +   '     <div style="padding-right:4px;"><img src="/photos' + data['FLIX_DATA'][i]['P_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" /></div>'
              +   '   </a>'
              +   ' </div>'
              +   ' <div style="float:left; padding-top:10px;">'
              +   '   <div class="bold">'
              +   '     <a href="javascript:void(0);" onclick="' + _link + '" title="Click to share slideshow">' + _title + '</a>'
              +   '   </div>'
              +   '   <div>' + data['FLIX_DATA'][i]['US_FOTO_COUNT'] + ' photos</div>'
              +   '   <div>' + data['FLIX_DATA'][i]['US_VIEWS'] + ' views</div>'
              +   ' </div>'
              +   ' <br clear="all" />'
              +   '</div>';
        _html += '</div>';
      }

      _proceed = true;
    }
  }
    
  _layer = $('_groupShareDataSlideshows');
  
  if(_proceed)
  {
    _layer.innerHTML = _html;
    _offset = parseInt(data['MISC']['OFFSET']);
    _limit = parseInt(data['MISC']['LIMIT']);
    _user_id = parseInt(data['MISC']['USER_ID']);
    _lastFlix = parseInt(_offset + _limit);
    
    // Previous Link
    if(_offset > 0)
    {
      _prevOffset = _offset - _limit ;
      $('_groupSharePagingSlideshows').innerHTML = '<a href="javascript:void(0);" onclick="groupShareGetSlideshows( $(\'_groupShareSearchBoxSlideshows\').value,' + group_id + ',' + moderator + ',' + _prevOffset + ',' + _limit + ');" id="PagePreviousLinkFlix">Previous</a>&nbsp;&nbsp;';
    }
    else
    {
      $('_groupSharePagingSlideshows').innerHTML = '&nbsp;&nbsp;';
    }
    
    // Next Link
    if(data['MISC']['TOTAL_FLIX'] > _lastFlix)
    {
      _nextOffset = _offset + _limit;
      $('_groupSharePagingSlideshows').innerHTML += '<a href="javascript:void(0);" onclick="groupShareGetSlideshows( $(\'_groupShareSearchBoxSlideshows\').value,' + group_id + ',' +  moderator + ',' + _nextOffset + ',' + _limit + ');" id="PageNextLinkFlix">Next</a>';
    }
    else
    {
      $('_groupSharePagingSlideshows').innerHTML += '';
    }
  }
  else
  {
    _layer.innerHTML = 'Sorry no slideshows.';
  }
}

function groupSharePhoto( photo_id, group_id )
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=share_foto&photo_id=' + photo_id + '&group_id=' + group_id, 
      onComplete: function(response){ groupSharePhotoRsp(response, photo_id); refreshTagsRsp(); }
    });
}

function groupSharePhotoRsp(response, photo_id)
{
  var data = response.responseText.parseJSON();
  
  _html = '';
  _html += '<div><img src="/photos' + data['P_THUMB_PATH'] + '" border="0" class="border_medium" width="75" height="75" /></div>';
  _html += '<div style="text-align:center;" class="f_red">Shared</div>';
        
  $('_photo_' + photo_id).innerHTML = _html;
  $('_photo_' + photo_id).style.opacity = .5;
}

function groupShareSlideshow(slideshow_id, group_id)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=share_slideshow&slideshow_id=' + slideshow_id + '&group_id=' + group_id, 
      onComplete: function(response){ groupShareSlideshowRsp(response, group_id, slideshow_id); refreshTagsRsp(); }
    });
}

function groupShareSlideshowRsp(response, group_id, slideshow_id)
{
  var data = response.responseText.parseJSON();
  
  _title = data['FLIX_DATA'][0]['US_NAME'];
  if(_title.length > 10)
      {
        _title = _title.substring(0, 10) + '...';
      }
      
  _html = '';
  _html +=  '<div style="background:url(images/board_flix_bg.gif) repeat-x; margin-bottom:3px; height:85px;">'
      +   ' <div style="float:left; padding-top:5px; padding-bottom:5px; padding-left:1px;">'
      +   '   <div style="padding-right:4px;"><img src="/photos' + data['FLIX_DATA'][0]['P_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" /></div>'
      +   ' </div>'
      +   ' <div style="float:left; padding-top:10px;">'
      +   '   <div class="bold">'
      +   _title
      +   '   </div>'
      +   '   <div>' + data['FLIX_DATA'][0]['US_FOTO_COUNT'] + ' photos</div>'
      +   '   <div>' + data['FLIX_DATA'][0]['US_VIEWS'] + ' views</div>'
      +   '   <div class="f_red">Shared</div>'
      +   ' </div>'
      +   ' <br clear="all" />'
      +   '</div>';
        
  $('_slideshow_' + slideshow_id).innerHTML = _html;
  $('_slideshow_' + slideshow_id).style.opacity = .5;
}

function groupUpdateQuickSet(tags, name, edit_id, group_id)
{
  _params = 'action=group_quick_set_update&tags='+tags+'&name='+name+'&set_id='+edit_id+'&group_id='+group_id+'&timestamp='+parseInt(Math.random()*100000);
  getXmlHttp();
  xmlHttpSend('groupGenericQuickSetRsp()', '/xml_result', _params);
  return false;
}
// groupUpdateQuickSet calls groupGenericQuickSetRsp

function groupQuickSetDialog()
{
  mode = arguments.length > 0 ? arguments[0] : 1;
  group_id = arguments[1];
  
  getXmlHttp();

  if(arguments.length > 2)
  {
    extra = arguments[2];
    if(extra.toString().search(/edit/) != -1) // edit quickset
    {
      editId = extra.replace(/edit/,'');
      xmlHttpSend('groupQuickSetDialogRsp('+mode+', '+group_id+',extra)', '/xml_result', 'action=group_quick_set_specific&set_id='+editId+'&group_id='+group_id+'&timestamp='+parseInt(Math.random()*100000));
    }
    else // add child where extra is parent_id
    {
      xmlHttpSend('groupQuickSetDialogRsp('+mode+', '+group_id+',extra)', '/xml_result', 'action=group_quick_set_count'+'&group_id='+group_id+'&timestamp='+parseInt(Math.random()*100000));
    }
  }
  else
  {
    xmlHttpSend('groupQuickSetDialogRsp('+mode+', '+group_id+')', '/xml_result', 'action=group_quick_set_count'+'&group_id='+group_id+'&timestamp='+parseInt(Math.random()*100000));
  }

}

function groupQuickSetDialogRsp()
{
  var pieces= xmlHttpResult.split(',');
  mode  = arguments.length > 0 ? arguments[0] : 1;
  group_id = arguments[1];
  extra = arguments.length > 2 ? arguments[2] : false;
  
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

    element = $('groupQuickSetDialog');
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
        element.innerHTML = '<div class="bold" style="padding-bottom:5px;">Create a Tag Folder&nbsp;<span class="f_red">(<a href="javascript:groupQuickSetDialogHide();" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;"><a href="javascript:groupQuickSetTrans(3,'+group_id+',0);">Create a QuickSet</a> (<a href="javascript:void($(\'quickSetDialog\').style.height=\'125px;\'); void($(\'quickSetOrgExplain\').innerHTML=\'Tag Folders are always displayed and can have multiple child Tag Folders nested beneath them.\');">?</a>)</div>'
                          + '<div style="padding-bottom:10px;"><a href="javascript:groupQuickSetTrans(2,'+group_id+');">Create a sub level Tag Folder</a> (<a href="javascript:void($(\'quickSetDialog\').style.height=\'125px;\'); void($(\'quickSetOrgExplain\').innerHTML=\'Sub level Tag Folders are displayed beneath another Tag Folder.\');">?</a>)</div>'
                          + '<div id="quickSetOrgExplain"></div>';
        break;
      case 2: // child ... select parent
        element.style.height  = '85px';
        element.innerHTML = '<div class="bold" style="padding-bottom:5px;">Select Tag Folder location&nbsp;<span class="f_red">(<a href="javascript:groupQuickSetDialogHide();" class="f_red" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;">Select a parent Tag Folder below.</div>'
                          + '<div style="padding-bottom:3px; width:225px; height:125px; overflow:auto;" id="groupQuickSetDialogList">Loading...</div>';
        setTimeout("groupQuickSetLoad(0,"+group_id+")", 200);
        break;
      case 3: // enter quick set
        jsFunc = _edit==true?'groupUpdateQuickSet':'groupAddQuickSet';
        butText= _edit==true?'save':'add';
        element.style.height  = '135px';
        if(_edit == true)
        {
          extra = extra.replace('edit','');
        }
        element.innerHTML = '<form id="qsForm" onsubmit="void($(\'qsSubmit\').value=\'saving...\'); return '+jsFunc+'($(\'quickSetTags\').value, $(\'quickSetName\').value, extra, '+group_id+');">'
                          + '<div class="bold" style="padding-bottom:5px;">'+(_edit==true?'Update your QuickSet':'Add a new Tag Folder')+'&nbsp;<span class="f_red">(<a href="javascript:groupQuickSetDialogHide();" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;"><div>Tag Folder Name</div><div><input type="text" value="'+(_edit==true?_name:'')+'" id="quickSetName" class="formfield" style="width:140px;" /></div></div>'
                          + '<div style="padding-bottom:3px;"><div>Tag(s)<div class="italic">(separate with commas)</div></div><div><input type="text" autocomplete="off" value="'+(_edit==true?_tags:'')+'" id="quickSetTags" class="formfield" style="width:140px;" /><div id="auto_complete_quickSetTags" class="auto_complete" style="width:140px; z-index:99;"></div></div></div>'
                          + '<div style="padding-bottom:3px;"><input type="submit" id="qsSubmit" value="'+butText+'" class="formbutton" /></div>'
                          + '</form>';
        new Autocompleter.Local("quickSetTags", "auto_complete_quickSetTags", userTags, {tokens: ","});
        break;
      case 4: // close or add another
        manage = 'manage';
        element.style.height  = '100px';
        element.innerHTML = '<div class="bold" style="padding-bottom:10px;">Tag Folder(s) updated</div>'
                          + '<div style="padding-bottom:3px;" align="center">'
                          + ' <div class="bold" style="padding-bottom:3px;"><a href="javascript:quickSetTrans(1);">Add a Tag Folder</a></div>'
                          + ' <div style="padding-bottom:3px;">-- or --</div>'
                          + ' <div class="bold" style="padding-bottom:3px;"><a href="javascript:void(groupQuickSetDialogHide()); void(setTimeout(\'quickSetRefresh(manage)\', 100));">Close this dialog</a></div>';
        break;
      case 5: // prompt to delete quickset
        element.style.width  = '275px';
        element.style.height  = '140px';
        element.innerHTML = '<div class="bold" style="padding-bottom:10px;">Delete a Tag Folder&nbsp;<span class="f_red">(<a href="javascript:groupQuickSetDialogHide();" title="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:3px;">'
                          + ' <div style="padding-bottom:3px;">You are about to delete a Tag Folder?*</div>'
                          + ' <div style="padding-bottom:6px;" class="bold"><a href="javascript:groupDeleteQuickSet('+extra+','+group_id+');">Continue and delete this QuickSet</a></div>'
                          + ' <div style="padding-bottom:3px;">-- or --</div>'
                          + ' <div style="padding-bottom:10px;" class="bold"><a href="javascript:groupQuickSetDialogHide();">Cancel</a></div>'
                          + ' <div">*Deleting a Tag Folder deletes all sub Tag Folders</div>';
        break;
      case 100: // display quickset definition
        element.style.width  = '210px';
        element.style.height  = '150px';
        element.innerHTML = '<div class="bold" style="padding-bottom:5px;">What\'s a Tag Folder?&nbsp;<span class="f_red">(<a href="javascript:groupQuickSetDialogHide();" tiele="close this dialog" class="f_red">x</a>)</span></div>'
                          + '<div style="padding-bottom:10px;">A Tag Folder is a great way to organize your most important tags.  You can assign any number of tags to a Tag Folder and order them as you wish.</div>'
                          + '<div>All your hard work will also show up as a navigation on your Personal Page so others can quickly find your most prized photos.</div>';
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

function poolMemberSearch(pOpts)
{
  var params= arguments[0];
  
  var sendParams = '';
  for(i in params)
  {
    switch(i)
    {
      case 'DIV_ID':
        sendParams += '&DIV_ID=' + params[i];
        break;
      case 'GROUP_ID':
        sendParams += '&GROUP_ID=' + params[i];
        break;
      case 'OFFSET':
        sendParams += '&OFFSET=' + params[i];
        break;
      case 'LIMIT':
        sendParams += '&LIMIT=' + params[i];
        break;  
      case 'SEARCH_ITEM':
        sendParams += '&SEARCH_ITEM=' + params[i];
        break;
    }
  }
  
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=poolMemberSearch' + sendParams, 
    onComplete:function(response){ poolMemberSearchRsp(response); }
  });
}

function poolMemberSearchRsp(response)
{
  //var result = eval(xmlHttpResult);
  var result = response.responseText.parseJSON();
  params = result['PARAMS'];
  rs = result['RESULT'];
  
  if(rs.length != 0)
  {
    var _html = '';
    var color = '#D3D3D3';
    var count = 1;
    var d = new Date();

    for(i = 0; i < rs.length; i++ )
    {
      if(count % 2 == 0)
      {
        count = 1;
        color = '#EFEFEF';
      }
      else 
      {
        count++;
        color = '#D3D3D3';
      }

      var month=new Array(12)
      month[0]="January"
      month[1]="February"
      month[2]="March"
      month[3]="April"
      month[4]="May"
      month[5]="June"
      month[6]="July"
      month[7]="August"
      month[8]="September"
      month[9]="October"
      month[10]="November"
      month[11]="December"
      
      d.setTime(parseInt(rs[i]['U_DATECREATED']*1000));
      dateStr = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
      
      status = 'Not a member';
      if(rs[i]['U_GROUP_ID'] != null)
      {
        status = 'Member';
      }
      else if(rs[i]['GI_STATUS'] == 'Declined')
      {
        status = 'Declined';
      }
      else if(rs[i]['GI_G_ID'] != null)
      {
        status = 'Pending';
      }
      _html += '<div id="_user_' + rs[i]['U_ID'] + '" style="height:50px; background-color:' + color + '; border-bottom:2px solid white;">';
      _html += '<div style="float:left; width:45px; height:100%; border-right:1px solid white;">';
      _html += '<div style="float:left; padding-top:15px; padding-left:15px; padding-right:3px;">';
      
      if(status == 'Not a member')
      {
        _html += '<div id="_addMemberIcon_' + rs[i]['U_ID'] + '"><a href="javascript:void(groupMemberAddConfirmation(\'_addMember_' + rs[i]['U_ID'] + '\', ' + rs[i]['U_ID'] + ', ' + params['GROUP_ID'] + '));"><img src="/images/icons/first_aid_16x16.png" class="png" border="0" width="16" height="16" title="Request to add member" /></a></div>';
      }
      else
      {
        _html += '<div></div>';
      }
      
      if(rs[i]['AVATAR'] != '')
      {
        avatarSrc = params['PATH_FOTO'] + rs[i]['AVATAR'];
      }
      else
      {
        avatarSrc = 'images/avatar.jpg';
      }
      
      _html += '<div id="_addMember_' + rs[i]['U_ID'] + '" style="display:block; z-index:75;"></div>';
      _html += '</div>';
      _html += '</div>';
      _html += '<div style="float:left; padding-top:5px; padding-left:15px;"><a href="http://' + params['FF_SERVER_NAME'] + '/users/' + rs[i]['U_USERNAME'] + '"><img src="' + avatarSrc + '" border="0" width="40" height="40" /></a></div>';
      _html += '<div style="padding-top:17px;">';
      _html += '<div style="float:left; width:300px;; padding-left:15px;"><a href="http://' + params['FF_SERVER_NAME'] + '/users/' + rs[i]['U_USERNAME'] + '">' + rs[i]['U_USERNAME'] + '</a></div>';
      _html += '<div style="float:left; width:145px;">' + dateStr + '</div>';
      _html += '<div id="_status_' + rs[i]['U_ID'] + '">' + status + '</div>';
      _html += '</div>';
      _html += '</div>';
    }
    
    var page = parseInt((params['OFFSET'] / params['LIMIT']) + 1);
    var totalRows = Math.ceil(params['TOTAL_ROWS']/params['LIMIT']);
    var pg = new Pager({current:page,total:totalRows,pagesDisplay:6,itemsPerPage:parseInt(params['LIMIT']),varStart:'pOpts[\'OFFSET\']',varLimit:'pOpts[\'LIMIT\']',jsFunc:'poolMemberSearch',opts:'pOpts'});
    
    if(pg.generate() == 1)
    {
      _html += '<br clear="all" />';
      _html += '<div style="float:right;">';
      _html += '<span style="float:left">Showing ' + params['TOTAL_ROWS'] + ' users</span>';
      _html += '</div>';
    }
    else
    {
      _html += '<br clear="all" />';
      _html += '<div style="float:right;">';
      _html += '<span style="float:left">Pages: &nbsp;</span>';
      _html += '<span style="float:left;">' + pg.first() + '</span>';
      _html += '<span style="float:left; padding-left:3px;">' + pg.previous() + '</span>';
      _html += '<span style="float:left; padding-right:3px; padding-left:3px;">' + pg.generate() + '</span>';
      _html += '<span style="float:left; padding-right:3px;">' + pg.next() + '</span>';
      _html += '<span>' + pg.last() + '</span>';
      _html += '</div>';
    }
  }
  else
  {
    _html = '<div style="padding-left:10px; padding-top:10px;" class="f_8">There are no users to display</div>';
  }
  
  $(params['DIV_ID']).innerHTML = _html;
}

function changeHeaderPhoto(group_id)
{
  var _tags = arguments.length > 1 ? arguments[1] : '';
  var _offset = arguments.length > 2 ? arguments[2] : 0;
  var _limit = arguments.length > 3 ? arguments[3] : 8;
  
  var element = $('headerBlank');
  element.style.position = 'absolute';
  element.style.border = 'solid 1px #dddddd';
  element.style.width = '350px';
  element.style.height = '230px';
  element.style.paddingTop = element.style.paddingLeft = element.style.paddingRight = element.style.paddingBottom = '10px';
  element.style.backgroundColor = '#f5f5f5';
  element.innerHTML = '<div style="float:left;" class="f_8 bold">Change Header Photo</div>'
                    + '<div style="float:right;"><a href="javascript:void(0);" onclick="headerEffect.toggle();" title="close this dialog"><img src="images/icons/close_16x16.png" class="png" width="16" height="16" border="0" /></a></div>'
                    + '<br clear="all" />'
                    + '<form action="" style="display:inline;" id="searchByTagForm" onsubmit="return changeHeaderPhoto(' + group_id + ', $(\'searchBox\').value);">'
                    + '<div style="float:left; padding-left:5px; padding-top:8px;">'
                    + '<img src="images/tag_search_icon.gif" width="11" height="16" border="0" style="float:left; padding-right:5px;">'
                    + '<input id="searchBox" class="formfield" type="text" style="float:left; display:block; width:80px;" value="'+_tags+'" />'
                    + '</div>'
                    + '<div id="auto_complete_searchBox" class="auto_complete" style="float:left; width:80px; z-index:75;"></div>'
                    + '<div style="float:left; padding-left:3px; padding-top:10px;"><a href="javascript:void($(\'searchByTagForm\').onsubmit());"><img src="images/icons/search_16x16.png" class="png" width="16" height="16" border="0" hspace="2" /></a></div>'
                    + '</form>'
                    + '<br clear="all" />'
                    + '<div id="headerResults">'
                    + ' <div style="width:85px; margin:auto; padding-top:100px;">'
                    + '   <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>'
                    + '   <div style="float:left;">Loading...</div>'
                    + '   <br/>'
                    + ' </div>'
                    + '</div>';
                   
  if(headerEffect.current() == 0)
  {
    headerEffect.toggle();
  }
        
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=change_header_photo&group_id=' + group_id + '&tags=' + _tags + '&offset=' + _offset + '&limit=' + _limit, 
    onComplete:function(response){ changeHeaderPhotoRsp(response, group_id); }
  });
}

function changeHeaderPhotoRsp(response, group_id)
{
  var data = response.responseText.parseJSON();
  var hasFotos = false;
  var element = $('headerResults');
  var _html = '';
    
  _html += '<div>';
  
  var _k = 0;
  for( i in data )
  {
    if(i != 'MISC' && data[i].P_THUMB_PATH != '')
    {
      i = parseInt(i);
      // id, key, thumbnail src
      _html += '<div style="float:left; padding-right:5px; padding-top:5px;"><a href="javascript:void(0);" onclick="updateHeader('+data[i].P_ID+', ' + group_id + ');" title="Click to change header photo"><img src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></a></div>';
      hasFotos = true;
    }
  }
  
  _html += '</div>';
    
  if( hasFotos == false )
  {
    _html += '<div style="padding-top:15px;" class="f_8">No photos available to set as your header.  Please refine your tag search or upload photos.</div>';
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
    _html += '<a href="javascript:void(0);" onclick="changeHeaderPhoto(' + group_id + ', $(\'searchBox\').value,' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLink">Previous</a>';
  }
  
  _html += '&nbsp;</div>'
        +  '<div style="float:left; text-align:right; width:170px;">&nbsp;';
  
  // Next Link
  if(data.MISC.TOTAL_ROWS > _lastFoto)
  {
    _html += '<a href="javascript:void(0);" onclick="changeHeaderPhoto(' + group_id + ', $(\'searchBox\').value,' + (_offset+_limit) + ',' + _limit + ');" id="PageNextLink">Next</a>';
  }
  
  _html += '  </div>'
        +  '</div>';

  element.innerHTML = _html;

  new Autocompleter.Local("searchBox", "auto_complete_searchBox", userTags, {tokens: ","});
}

function updateHeader(id, group_id)
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=header_update&id='+id+'&group_id='+group_id, onComplete:function(response){ updateHeaderRsp(response); } }
  );
}

function updateHeaderRsp(response)
{
  var _pieces = response.responseText.split(',');
  if(_pieces[0] == 'true')
  {
    if(_pieces[1] != 'null')
    {
      $('headerPhoto').src = _pieces[1];
      $('_headerPhotoImg').src = _pieces[1];
      headerEffect.toggle(); // defined in header.dsp.php
    }
  }
}

function updateSettings(group_id, headerTitle, headerDescription, rightColumnTitle, rightColumnTags, p_colors )
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=update_group_settings&group_id=' + group_id + '&_headerTitle=' + headerTitle + '&_headerDescription=' + headerDescription + '&_rightColumnTitle=' + rightColumnTitle + '&_rightColumnTags=' + rightColumnTags + '&p_colors=' + p_colors, onComplete:function(response){ updateSettingsRsp(response, headerTitle, headerDescription); } }
  );
}

function updateSettingsRsp(response, headerTitle, headerDescription)
{
  var _pieces = response.responseText.split(',');
  if(_pieces[0] == 'success')
  {
    messageEffect.toggle();
    var effect = new fx.Opacity('message', {duration:3000, onComplete: function(){ messageEffect.hide();}} );
    effect.toggle();
    
    $('groupHeaderTitle').innerHTML = headerTitle;
    $('groupHeaderDescription').innerHTML = headerDescription;
  }
}

function approvePhoto(group_id, photo_id, photo_orig_id, u_orig_id)
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=approve_photo&group_id=' + group_id + '&photo_id=' + photo_id + '&photo_orig_id=' + photo_orig_id + '&u_orig_id=' + u_orig_id, onComplete:function(response){ approvePhotoRsp(response); } }
  );
}

function approvePhotoRsp(response)
{
  var data = response.responseText.parseJSON();
  window.location.reload();
}


function rejectPhoto(group_id, photo_id, photo_orig_id, u_orig_id)
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=reject_photo&group_id=' + group_id + '&photo_id=' + photo_id + '&photo_orig_id=' + photo_orig_id + '&u_orig_id=' + u_orig_id, onComplete:function(response){ rejectPhotoRsp(response); } }
  );
}

function rejectPhotoRsp(response)
{
  var data = response.responseText.parseJSON();
  window.location.reload();
}


function approveSlideshow(group_id, slideshow_id, slideshow_orig_id, u_orig_id)
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=approve_slideshow&group_id=' + group_id + '&slideshow_id=' + slideshow_id + '&slideshow_orig_id=' + slideshow_orig_id + '&u_orig_id=' + u_orig_id, onComplete:function(response){ approveSlideshowRsp(response); } }
  );
}

function approveSlideshowRsp(response)
{
  var data = response.responseText.parseJSON();
  window.location.reload();
}


function rejectSlideshow(group_id, slideshow_id, slideshow_orig_id, u_orig_id)
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=reject_slideshow&group_id=' + group_id + '&slideshow_id=' + slideshow_id + '&slideshow_orig_id=' + slideshow_orig_id + '&u_orig_id=' + u_orig_id, onComplete:function(response){ rejectSlideshowRsp(response); } }
  );
}

function rejectSlideshowRsp(response)
{
  var data = response.responseText.parseJSON();
  window.location.reload();
}