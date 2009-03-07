/* XML HTTP FUNCTIONS */
var xmlHttpResult;
var xmlHttpFunction;
var xmlHttp;
var xmlDoc;

function xmlHttpResponse()
{
  if (xmlHttp.readyState == 4)
  {
    xmlHttpResult = xmlHttp.responseText;
    if(xmlHttpFunction != '')
    {
      eval(xmlHttpFunction);
    }
  }
}

function xmlHttpSend(_func, _url, _params)
{
  xmlHttpFunction = _func;
  xmlHttp.open("GET", _url + "?" + _params, true);
  //prompt('', _url + '?' + _params);
  xmlHttp.onreadystatechange = xmlHttpResponse;
  xmlHttp.send(null);
}

function getXmlHttp()
{
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlHttp = false;
      }
    }
  @else
  xmlHttp = false;
  @end @*/
  if (!xmlHttp && typeof XMLHttpRequest != 'undefined')
  {
    try
    {
      xmlHttp = new XMLHttpRequest();
    }
    catch (e)
    {
      xmlHttp = false;
    }
  }
  return xmlHttp;
}

/*(function () {
    var m = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        s = {
            array: function (x) {
                var a = ['['], b, f, i, l = x.length, v;
                for (i = 0; i < l; i += 1) {
                    v = x[i];
                    f = s[typeof v];
                    if (f) {
                        v = f(v);
                        if (typeof v == 'string') {
                            if (b) {
                                a[a.length] = ',';
                            }
                            a[a.length] = v;
                            b = true;
                        }
                    }
                }
                a[a.length] = ']';
                return a.join('');
            },
            'boolean': function (x) {
                return String(x);
            },
            'null': function (x) {
                return "null";
            },
            number: function (x) {
                return isFinite(x) ? String(x) : 'null';
            },
            object: function (x) {
                if (x) {
                    if (x instanceof Array) {
                        return s.array(x);
                    }
                    var a = ['{'], b, f, i, v;
                    for (i in x) {
                        v = x[i];
                        f = s[typeof v];
                        if (f) {
                            v = f(v);
                            if (typeof v == 'string') {
                                if (b) {
                                    a[a.length] = ',';
                                }
                                a.push(s.string(i), ':', v);
                                b = true;
                            }
                        }
                    }
                    a[a.length] = '}';
                    return a.join('');
                }
                return 'null';
            },
            string: function (x) {
                if (/["\\\x00-\x1f]/.test(x)) {
                    x = x.replace(/([\x00-\x1f\\"])/g, function(a, b) {
                        var c = m[b];
                        if (c) {
                            return c;
                        }
                        c = b.charCodeAt();
                        return '\\u00' +
                            Math.floor(c / 16).toString(16) +
                            (c % 16).toString(16);
                    });
                }
                return '"' + x + '"';
            }
        };

    Object.prototype.toJSONString = function () {
        return s.object(this);
    };

    Array.prototype.toJSONString = function () {
        return s.array(this);
    };
})();*/

String.prototype.parseJSON = function () {
    try {
        return !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(
                this.replace(/"(\\.|[^"\\])*"/g, ''))) &&
            eval('(' + this + ')');
    } catch (e) {
        return false;
    }
};

/* ------------------------------------- START REQUEST CALLS AND HANDLERS ------------------------------------- */
/*
  invocation functions are named as normal
  handler functions are named the same as the invocation function with an Rsp at the end
  i.e.
    invocation function: getTags()
    handler function: getTagsRsp()
*/

function addFlixTags(flix_id, tags)
{
  getXmlHttp();
  xmlHttpSend('genericFlixTagsRsp('+flix_id+')', '/xml_result', 'action=flix_tags_add&flix_id='+flix_id+'&tags='+escape(tags)+'&timestamp='+parseInt(Math.random()*100000));
}
// addFlixTags calls genericFlixTagsRsp

function blogFlix(flix_id)
{
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=blog_flix' + '&us_id=' + flix_id, 
    onComplete:function(response){ blogFlixRsp(response); }
  });
}

function blogFlixRsp(response)
{
  var data = response.responseText.parseJSON();
  window.location = '/?action=flix.flix_post&us_key=' + data['FLIX_DATA']['US_KEY'];
}

function changeAvatar(tags)
{
  _tags = tags; // this variable is accessed from outside this function
  var _offset = arguments.length > 2 ? arguments[1] : 0;
  var _limit = arguments.length > 3 ? arguments[2] : 12;
  var _privacy = 1;
  
  var element = $('avatarBlank');
  element.style.position = 'absolute';
  element.style.border = 'solid 1px #dddddd';
  element.style.width = '350px';
  element.style.height = '310px';
  element.style.paddingTop = element.style.paddingLeft = element.style.paddingRight = element.style.paddingBottom = '10px';
  element.style.backgroundColor = '#f5f5f5';
  element.innerHTML = '<div style="float:left;" class="f_8 bold">Change Photo</div>'
                    + '<div style="float:right;"><a href="javascript:void(0);" onclick="avatarEffect.toggle();" title="close this dialog"><img src="images/icons/close_16x16.png" class="png" width="16" height="16" border="0" /></a></div>'
                    + '<br clear="all" />'
                    + '<form action="" style="display:inline;" id="searchByTagForm" onsubmit="return changeAvatar( $(\'searchBox\').value);">'
                    + '<div style="float:left; padding-left:5px; padding-top:8px;">'
                    + '<img src="images/tag_search_icon.gif" width="11" height="16" border="0" style="float:left; padding-right:5px;">'
                    + '<input id="searchBox" class="formfield" type="text" style="float:left; display:block; width:80px;" value="'+_tags+'" />'
                    + '</div>'
                    + '<div id="auto_complete_searchBox" class="auto_complete" style="float:left; width:80px; z-index:75;"></div>'
                    + '<div style="float:left; padding-left:3px; padding-top:10px;"><a href="javascript:void($(\'searchByTagForm\').onsubmit());"><img src="images/icons/search_16x16.png" class="png" width="16" height="16" border="0" hspace="2" /></a></div>'
                    + '</form>'
                    + '<br clear="all" />'
                    + '<div id="avatarResults">'
                    + ' <div style="width:85px; margin:auto; padding-top:100px;">'
                    + '   <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>'
                    + '   <div style="float:left;">Loading...</div>'
                    + '   <br/>'
                    + ' </div>'
                    + '</div>';
  

  if(avatarEffect.current() == 0)
  {
    avatarEffect.toggle();
  }
  
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'get', 
    parameters: 'action=fotos_search&TAGS='+tags+'&OFFSET='+_offset+'&LIMIT='+_limit+'&privacy='+_privacy, 
    onComplete: function(response){ changeAvatarRsp(response); }
    });
  return false;
}

function changeAvatarRsp(response,elementName)
{
  var data = response.responseText.parseJSON();
  var hasFotos = false;
  var element = $('avatarResults');
  var _html = '';
    
  _html += '<div style="min-height:232px; _height:232px;">';
  
  var _k = 0;
  for( i in data )
  {
    if(i != 'MISC' && data[i].P_THUMB_PATH != '')
    {
      i = parseInt(i);
      // id, key, thumbnail src
      _html += '<div style="float:left; padding-right:5px; padding-top:5px;"><a href="javascript:void(0);" onclick="updateAvatar('+data[i].P_ID+');" title="Click to change avatar"><img src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></a></div>';
      hasFotos = true;
    }
  }
  
  _html += '</div>';
    
  if( hasFotos == false )
  {
    _html += '<div style="padding-top:15px;" class="f_8">No photos available to set as your avatar.  Please refine your tag search or upload photos.</div>';
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
    _html += '<a href="javascript:void(0);" onclick="changeAvatar( $(\'searchBox\').value,' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLink">Previous</a>';
  }
  
  _html += '&nbsp;</div>'
        +  '<div style="float:left; text-align:right; width:170px;">&nbsp;';
  
  // Next Link
  if(data.MISC.TOTAL_ROWS > _lastFoto)
  {
    _html += '<a href="javascript:void(0);" onclick="changeAvatar( $(\'searchBox\').value,' + (_offset+_limit) + ',' + _limit + ');" id="PageNextLink">Next</a>';
  }
  
  _html += '  </div>'
        +  '</div>';
  
  element.innerHTML = _html;
  new Autocompleter.Local("searchBox", "auto_complete_searchBox", userTags, {tokens: ","});
}

function deleteReport(r_id)
{ 
  getXmlHttp();
  xmlHttpSend('deleteReportRsp()', '/xml_result', 'action=report_delete&r_id=' + r_id);
  
  return false;
}

function deleteReportRsp()
{
  var result = eval(xmlHttpResult);
  $('_' + result[0]).style.display = 'none';
}

function duplicateSlideshow(slideshowId)
{
  $('slideshowConfirmMessage').innerHTML = 'Please wait...';
  editOptsConfirm.toggle();
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=flix_duplicate&slideshowId='+slideshowId, 
      onComplete: function(response){ duplicateSlideshowRsp(response); }
    });
}

function duplicateSlideshowRsp(response)
{
  var data = response.responseText.parseJSON();
  
  $('slideshowConfirmMessage').innerHTML = 'Your slideshow was duplicated.';
  setTimeout("editOptsConfirm.toggle();", 3000);
}


function editSubAccountPerms(sa_id)
{
  getXmlHttp();
  xmlHttpSend('editSubAccountPermsRsp('+sa_id+')', '/xml_result', 'action=sub_account_permissions&sa_id=' + sa_id);
}

function editSubAccountPermsRsp(sa_id)
{
  var perms = xmlHttpResult.parseJSON();
  
  var _html = '';
  for(type in perms)
  {
    _html +='<div style="width:330px; height:20px; text-align:left;">'
          + '<div style="float:left; width:90px; text-transform:capitalize;">' + type + '</div>'
          + '<div style="float:left; width:60px; text-align:center;" id="R__'+type+'__'+sa_id+'"><input type="checkbox" value="'+sa_id+'-'+type+'-R" onclick="setSubAccountPerm(this.checked, this.value);" '+(perms[type].R == true ? 'checked="true"' : '')+' style="margin-left:2px;" /></div>'
          + '<div style="float:left; width:60px; text-align:center;" id="C__'+type+'__'+sa_id+'"><input type="checkbox" value="'+sa_id+'-'+type+'-C" onclick="setSubAccountPerm(this.checked, this.value);" '+(perms[type].C == true ? 'checked="true"' : '')+' style="margin-left:2px;" /></div>'
          + '<div style="float:left; width:60px; text-align:center;" id="U__'+type+'__'+sa_id+'"><input type="checkbox" value="'+sa_id+'-'+type+'-U" onclick="setSubAccountPerm(this.checked, this.value);" '+(perms[type].U == true ? 'checked="true"' : '')+' style="margin-left:2px;" /></div>'
          + '<div style="float:left; width:60px; text-align:center;" id="D__'+type+'__'+sa_id+'"><input type="checkbox" value="'+sa_id+'-'+type+'-D" onclick="setSubAccountPerm(this.checked, this.value);" '+(perms[type].D == true ? 'checked="true"' : '')+' style="margin-left:2px;" /></div>'
          + '<br />'
          + '</div>';
  }
  
  $('subAccountPermsText'+sa_id).innerHTML = _html;
  
  effect = eval('saPermShow'+sa_id);
  effect.toggle();
}

function embedFlix(flix_id)
{
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=embed_flix' + '&us_id=' + flix_id, 
    onComplete:function(response){ embedFlixRsp(response); }
  });
}

function embedFlixRsp(response)
{
  var data = response.responseText.parseJSON();
  
  $('_embedCode').innerHTML = '<div>Copy and paste this code into web page</div><div><textarea id="_embedCodeResult" rows="2" cols="35">&lt;script type="text/javascript" src="http://' + data['MISC']['SERVER'] + '/js/slideshow_remote/' + data['FLIX_DATA']['US_KEY'] + '/"></script></textarea></div>';
  $('_embedCodeResult').focus();
  $('_embedCodeResult').select();
}

function flixSchedule(tags)
{
  $('scheduleContent').style.display = 'none';
  $('scheduleLoading').style.display = 'block';
  $('scheduleLoading').innerHTML = '<div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>'
                                 + '<div style="float:left;">Loading...</div>'
                                 + '<br/>';
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=flix_schedule_search&tags='+tags, 
      onComplete:function(response){ flixScheduleRsp(response); }
    });
}

function flixScheduleRsp(response)
{
  var data = response.responseText.parseJSON();
  var _html = '';
  
  _html += '<div id="scheduleItems" class="schedule_items">';
  
  for(i = 0; i < data['FLIX_DATA'].length; i++)
  {
    if(data['FLIX_DATA'][i] != null)
    { 
      _html += '<div style="margin-bottom:5px; padding:5px;" class="' + (i % 2 == 0 ? 'bg_lite' : 'bg_white') + '">';
      
      _title = data['FLIX_DATA'][i]['US_NAME'];
      if(_title.length > 10)
      {
        _title = _title.substring(0, 10) + '...';
      }
      
      _us_id = parseInt(data['FLIX_DATA'][i]['US_ID']);
      _thumb = data['FLIX_DATA'][i]['P_THUMB_PATH'];
      _number_photos = parseInt(data['FLIX_DATA'][i]['US_FOTO_COUNT']);
      _number_views = parseInt(data['FLIX_DATA'][i]['US_VIEWS']);
      _link  = '';
      
      _date = new Date();
      
      _html += '<div>'
            +  '  <div style="float:left;">'
            +  '    <div class="flix_border"><img src="/photos' + _thumb + '" /></div>'
            +  '  </div>'
            +  '  <div style="float:left;">'
            +  '    <div style="margin-bottom:2px;">Begin showing on</div><div><input type="text" id="startDate_' + _us_id + '" value="' + (_date.getMonth()+1) + '/' + _date.getDate() + '/' + _date.getFullYear() + '" class="formfield" size="10" /></div>'
            +  '    <div style="margin-top:10px; margin-bottom:2px;">Stop showing on</div><div><input type="text" id="endDate_' + _us_id + '" value="" class="formfield" size="10" /></div>'
            +  '    <div class="bold" style="margin-top:5px;"><a href="javascript:void(0);" onclick="setFlixSchedule(' + _us_id + ', $(\'startDate_' + _us_id + '\').value, $(\'endDate_' + _us_id + '\').value);" class="plain">Schedule this Slideshow</a><span id="_' + _us_id + '_saving"></span></div>'
            +  '  </div>'
            +  '  <br clear="all" />'
            +  '</div>'
            +  '<div id="schedule_item_current_items_' + _us_id + '">';
      if(data['FLIX_DATA'][i]['SCHEDULE'].length > 0)
      {
        for(j = 0; j < data['FLIX_DATA'][i]['SCHEDULE'].length; j++)
        {
          if(data['FLIX_DATA'][i]['SCHEDULE'][j] != null)
          {
            fromDate  = data['FLIX_DATA'][i]['SCHEDULE'][j]['USS_START_DATE'] != '12/31/1969' ? data['FLIX_DATA'][i]['SCHEDULE'][j]['USS_START_DATE'] : 'N/A';
            toDate    = data['FLIX_DATA'][i]['SCHEDULE'][j]['USS_END_DATE'] != '12/31/1969' ? data['FLIX_DATA'][i]['SCHEDULE'][j]['USS_END_DATE'] : 'N/A';
            _html += '<div style="margin-left:25px;" id="schedule_' + data['FLIX_DATA'][i]['SCHEDULE'][j]['USS_ID'] + '"><img src="images/icons/history_16x16.png" width="16 height="16" hspace="5" border="0" align="absmiddle" />From ' + fromDate + ' to ' + toDate + ' (<a href="javascript:void(0);" onclick="unsetFlixSchedule(' + data['FLIX_DATA'][i]['SCHEDULE'][j]['USS_ID'] + ')">remove</a>)</div>';
          }
        }
      }
      _html += '</div>';
      
      /*_html += '</div>';
      _html += '</div>';
      
      _html += '</div>';*/
      _html += '<br clear="all" />';
      _html += '</div>'; // div for bg color
    }
  }
  
  _html += '</div>';
  
  var effect = new fx.Opacity('scheduleLoading', {duration:100, onComplete: function()
                      { 
                        $('scheduleContent').style.display = 'block';
                        $('scheduleLoading').style.display = 'none';
                        var onCompleteEffect = new fx.Opacity('scheduleContent', {duration:100}); }
                      }
                     );
  effect.toggle();
 
  $('scheduleContent').innerHTML = _html;
}

function flixNameUpdate(_id, _name, _desc)
{
  getXmlHttp();
  xmlHttpSend('flixNameUpdateRsp("'+_name+'","'+_desc+'")', '/xml_result', 'action=flix_name_update&flix_id='+_id+'&name='+escape(_name)+'&desc='+escape(_desc)+'&timestamp='+parseInt(Math.random()*100000));
}

function flixNameUpdateRsp(name, desc)
{
  top.$('flixNameDesc').innerHTML = '<div style="width:200px; padding-bottom:2px;">Name: ' + (name != '' > 0 ? name : '<span class="italic">None entered</span>') + '</div>';
  _toggle("flixNameUpdateDiv");
  $('flixUpdateFormSub').value = 'Update';
}

// genericBulkTagsRsp is called by numerous functions
function genericBulkTagsRsp(display)
{
  var pieces = xmlHttpResult.split(',');
  if(pieces[0] == 'true')
  {
    $('tagForm').innerHTML = '<div style="font-size:16px; margin-top:25px;" class="bold" align="center">Fotos '+display+'</div>';
    setTimeout("opacity('tagForm', 100, 0, 1000, true)", 1000);
  }
  else
  {
    $('tagsBulkSub').value='Error...Try Again';
  }
}

// genericFlixTagsRsp is called by numerous functions
function genericFlixTagsRsp(flix_id)
{
  setTimeout('getFlixTags('+flix_id+')', 500);
}

function getFlixTags(tagId)
{
  getXmlHttp();
  xmlHttpSend('getFlixTagsRsp('+tagId+')', '/xml_result', 'action=flix_tags&flix_id='+tagId+'&timestamp='+parseInt(Math.random()*100000));
}

function getFlixTagsRsp(flix_id)
{
  var pieces = xmlHttpResult.split(',');
  if(pieces[0] == 'true')
  {
    _hasTags = false;
    var _html = '';
    for(i=1; i<pieces.length; i++)
    {
      if(pieces[i] != '')
      {
        _html += '<div id="flix_remove_tag_'+flix_id+'_'+i+'">(<a href="javascript:void(removeFlixTags('+flix_id+',\''+pieces[i]+'\'));" onclick="$(\'flix_remove_tag_'+flix_id+'_'+i+'\').style.backgroundColor=\'yellow\';" title="untag from flix ('+pieces[i]+')">x</a>)'+pieces[i]+'</div>';
        _hasTags = true;
      }
    }

    if(_hasTags == true)
    {
      _html = _html.substr(0,_html.length-2);
      $('flix_tags_existing_'+flix_id).innerHTML = _html;
    }
    else
    {
      $('flix_tags_existing_'+flix_id).innerHTML = '<span class="bold">No Tags</span>';
    }
  }
}

function postItFotos()
{
  var _sessid = arguments[0];
  if(arguments.length == 1)
  {
    baseUrl = "/cgi-bin/progress.cgi";
    baseParams = "iTotal=" + escape("-1");
    baseParams += "&iRead=0";
    baseParams += "&iStatus=1";
    baseParams += "&sessionid=" + _sessid + '&timestamp='+parseInt(Math.random()*100000);
    $('uploadProgressDiv').className='border_medium';
    //$('uploadProgressDiv').innerHTML = '<div style="width:100%; text-align:center; padding-top:20px;" class="bold">Initializing...</div>';
  }
  else
  {
    urlTotal  = arguments[1];
    urlParts  = urlTotal.split('?');
    baseUrl   = urlParts[0];
    baseParams= urlParts[1];
  }
  
  getXmlHttp();
  xmlHttpSend('postItFotosRsp("'+_sessid+'")', baseUrl, baseParams);
}

function postItFotosRsp(_sessid)
{
  var result  = xmlHttpResult.replace(/^\s*|\s*$/g,"");
  //$('wtf').innerHTML = $('wtf').innerHTML + '<br/><br/>xmlHttpResult:<br/>' + xmlHttpResult;
  if(result.length > 0)
  {
    _pieces = xmlHttpResult.split('~');
    success = _pieces[0].replace(/^\s*|\s*$/g,"");
    theStatus  = _pieces[1].replace(/^\s*|\s*$/g,"");
    newUrl  = _pieces[2].replace(/^\s*|\s*$/g,"");
    newPercent = _pieces[3];
    newWidth = _pieces[4];
    if(theStatus == 'progress')
    {
      $('uploadProgressPercent').innerHTML = newPercent.toString();
      $('uploadProgressWidth').width = newWidth;
    }
    //$('wtf').innerHTML = '<br/>' + $('wtf').innerHTML + '<br/><br/>NewUrl - ' + newUrl;

    if(theStatus != 'complete')
    {
      //$('uploadProgressDiv').innerHTML = _pieces[3];
      setTimeout('postItFotos("'+_sessid+'", "'+newUrl+'")', 2500);
    }
  }
  else
  {
    setTimeout('postItFotos("'+_sessid+'")', 2500);
  }
}

function postItMp3()
{
  var _sessid = arguments[0];
  if(arguments.length == 1)
  {
    baseUrl = "/cgi-bin/progress.cgi";
    baseParams = "iTotal=" + escape("-1");
    baseParams += "&iRead=0";
    baseParams += "&iStatus=1";
    baseParams += "&sessionid=" + _sessid + '&timestamp='+parseInt(Math.random()*100000);
    $('uploadProgressDiv').className='border_medium';
    //$('uploadProgressDiv').innerHTML = '<div style="width:100%; text-align:center; padding-top:20px;" class="bold">Initializing...</div>';
  }
  else
  {
    urlTotal  = arguments[1];
    urlParts  = urlTotal.split('?');
    baseUrl   = urlParts[0];
    baseParams= urlParts[1];
  }
  //prompt('baseParams', 'http://demo.fotoflix.com/' + baseUrl+'?'+baseParams);
  //t = new Date();

  //$('wtf').innerHTML = t.toString() + '<br/>http://demo.fotoflix.com/' + baseUrl+'?'+baseParams;
  //self.status = baseParams;
  getXmlHttp();
  xmlHttpSend('postItMp3Rsp("'+_sessid+'")', baseUrl, baseParams);
}

function postItMp3Rsp(_sessid)
{
  var result  = xmlHttpResult.replace(/^\s*|\s*$/g,"");
  //$('wtf').innerHTML = $('wtf').innerHTML + '<br/><br/>xmlHttpResult:<br/>' + xmlHttpResult;
  if(result.length > 0)
  {
    _pieces = xmlHttpResult.split('~');
    success = _pieces[0].replace(/^\s*|\s*$/g,"");
    theStatus  = _pieces[1].replace(/^\s*|\s*$/g,"");
    newUrl  = _pieces[2].replace(/^\s*|\s*$/g,"");
    newPercent = _pieces[3];
    newWidth = _pieces[4];
    
    if(theStatus == 'progress')
    {
      $('uploadProgressPercent').innerHTML = newPercent.toString();
      $('uploadProgressWidth').width = newWidth;
    }
    //$('wtf').innerHTML = '<br/>' + $('wtf').innerHTML + '<br/><br/>NewUrl - ' + newUrl;

    if(theStatus != 'complete')
    {
      //$('uploadProgressDiv').innerHTML = _pieces[3];
      setTimeout('postItMp3("'+_sessid+'", "'+newUrl+'")', 2500);
    }
  }
  else
  {
    setTimeout('postItMp3("'+_sessid+'")', 2500);
  }
}

function postItVideo()
{
  var _sessid = arguments[0];
  if(arguments.length == 1)
  {
    baseUrl = "/cgi-bin/progress.cgi";
    baseParams = "iTotal=" + escape("-1");
    baseParams += "&iRead=0";
    baseParams += "&iStatus=1";
    baseParams += "&sessionid=" + _sessid + '&timestamp='+parseInt(Math.random()*100000);
    //$('uploadProgressDiv').className='border_medium';
    //$('uploadProgressDiv').innerHTML = '<div style="width:100%; text-align:center; padding-top:20px;" class="bold">Initializing...</div>';
  }
  else
  {
    urlTotal  = arguments[1];
    urlParts  = urlTotal.split('?');
    baseUrl   = urlParts[0];
    baseParams= urlParts[1];
  }
  
  getXmlHttp();
  xmlHttpSend('postItVideoRsp("'+_sessid+'")', baseUrl, baseParams);
}

function postItVideoRsp(_sessid)
{
  var result  = xmlHttpResult.replace(/^\s*|\s*$/g,"");
  //$('wtf').innerHTML = $('wtf').innerHTML + '<br/><br/>xmlHttpResult:<br/>' + xmlHttpResult;
  if(result.length > 0)
  {
    _pieces = xmlHttpResult.split('~');
    success = _pieces[0].replace(/^\s*|\s*$/g,"");
    theStatus  = _pieces[1].replace(/^\s*|\s*$/g,"");
    newUrl  = _pieces[2].replace(/^\s*|\s*$/g,"");
    newPercent = _pieces[3];
    newWidth = _pieces[4];
    totalSize= _pieces[5];
    
    if(totalSize != -1)
    {
      if(theStatus == 'progress')
      {
        $('uploadProgressPercent').innerHTML = newPercent.toString();
        $('uploadProgressWidth').width = newWidth;
      }
      //$('wtf').innerHTML = '<br/>' + $('wtf').innerHTML + '<br/><br/>NewUrl - ' + newUrl;
  
      if(theStatus != 'complete')
      {
        //$('uploadProgressDiv').innerHTML = _pieces[3];
        setTimeout('postItVideo("'+_sessid+'", "'+newUrl+'")', 2500);
      }
    }
    else
    {
      location.href= '/?action=video.upload_form&message=fileTooLarge';
    }
  }
  else
  {
    setTimeout('postItVideo("'+_sessid+'")', 2500);
  }
}

function removeFlixTags(flix_id, tags)
{
  getXmlHttp();
  xmlHttpSend('genericFlixTagsRsp('+flix_id+')', '/xml_result', 'action=flix_tags_remove&flix_id='+flix_id+'&tags='+escape(tags)+'&timestamp='+parseInt(Math.random()*100000));
}
// removeFlixTags calls genericFlixTagsRsp

function replyFrmGetFlix( tags, user_id )
{
  var _offset = arguments.length > 2 ? arguments[2] : 0;
  var _limit = arguments.length > 3 ? arguments[3] : 10;
  var _privacy = 1;
  
  _layer = $('dataFieldFlix');
  _layer.innerHTML = 'Loading...';
  
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=flix_by_tags&TAGS=' + tags + '&OFFSET=' + _offset + '&LIMIT=' + _limit + '&PERMISSION=' + _privacy,
      onComplete: function(response){ replyFrmGetFlixRsp(response) }
    }
  );
}

function replyFrmGetFlixRsp(response)
{
  var data = response.responseText.parseJSON();
  var _proceed = false;
  
  var _html = '';
  for( i = 0; i < data['FLIX_DATA'].length; i++ )
  {
    if(data['FLIX_DATA'][i] != null)
    {
      // id, key, thumbnail src
      
      _title = data['FLIX_DATA'][i]['US_NAME'];
      if(_title.length > 10)
      {
        _title = _title.substring(0, 10) + '...';
      }
      _link  = "insertFlix('" + data['FLIX_DATA'][i]['US_KEY'] + "');"
      _html +=  '<div style="background:url(images/board_flix_bg.gif) repeat-x; margin-bottom:3px; height:85px;">'
            +   ' <div style="float:left; padding-top:5px; padding-bottom:5px; padding-left:1px;">'
            +   '   <a href="javascript:void(0);" onclick="' + _link + '" title="Click to insert Flix">'
            +   '     <div style="padding-right:4px;"><img src="/photos' + data['FLIX_DATA'][i]['P_THUMB_PATH'] + '" border="0" width="75" height="75" class="border_medium" /></div>'
            +   '   </a>'
            +   ' </div>'
            +   ' <div style="float:left; padding-top:10px;">'
            +   '   <div class="bold">'
            +   '     <a href="javascript:void(0);" onclick="' + _link + '" title="Click to insert slideshow">' + _title + '</a>'
            +   '   </div>'
            +   '   <div>' + data['FLIX_DATA'][i]['US_FOTO_COUNT'] + ' photos</div>'
            +   '   <div>' + data['FLIX_DATA'][i]['US_VIEWS'] + ' views</div>'
            +   ' </div>'
            +   ' <br clear="all" />'
            +   '</div>';
      
      _proceed = true;
    }
  }
    
  _layer = $('dataFieldFlix');
  
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
      $('PagePreviousFlix').innerHTML = '<a href="javascript:void(0);" onclick="replyFrmGetFlix( $(\'searchBoxFlix\').value,' + _user_id + ',' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLinkFlix">Previous</a>';
    }
    else
    {
      $('PagePreviousFlix').innerHTML = '&nbsp;';
    }
    
    // Next Link
    if(_elements[1] > _lastFlix)
    {
      $('PageNextFlix').innerHTML = '<a href="javascript:void(0);" onclick="replyFrmGetFlix( $(\'searchBoxFlix\').value,' + _user_id + ',' + (_offset+_limit) + ',' + _limit + ');" id="PageNextLinkFlix">Next</a>';
    }
    else
    {
      $('PageNextFlix').innerHTML = '&nbsp;';
    }
  }
  else
  {
    _layer.innerHTML = 'Sorry no slideshows.';
  }
}

function replyFrmGetFotos( tags )
{
  var _offset = arguments.length > 2 ? arguments[1] : 0;
  var _limit = arguments.length > 3 ? arguments[2] : 10;
  var _privacy = 1;
  
  _layer = $('dataField');
  _layer.innerHTML = 'Loading...';
  
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=fotos_search&TAGS=' + tags + '&OFFSET=' + _offset + '&LIMIT=' + _limit + '&PERMISSION=' + _privacy,
      onComplete: function(response){ replyFrmGetFotosRsp(response) }
    }
  );
}

function replyFrmGetFotosRsp(response)
{
  var data = response.responseText.parseJSON();
  var hasFotos = false;
  
  var _html = '<div align="center">';
  _k = 0;
  
  for( i in data )
  {
    if(i != 'MISC')
    {
      i = parseInt(i);
      _html += '<div style="float:left; width:85px; padding-left:10px; padding-bottom:5px;"><a href="javascript:void(0);" onclick="insertFoto(\'' + data[i].P_KEY + '\');" title="Click to insert photo"><img src="/photos' + data[i].P_THUMB_PATH + '" border="0" class="border_medium" width="75" height="75" /></a></div>';
      if( _k % 2 != 0 )
      {
        _html += '<br clear="all" />';
      }
      
      _k++;
      hasFotos = true;
    }
  }
  _html += '</div>';
    
  _layer = $('dataField');
  
  if(hasFotos == true)
  {
    _layer.innerHTML = _html;
    _offset = parseInt(data.MISC.OFFSET);
    _limit = parseInt(data.MISC.LIMIT);
    _lastFoto = parseInt(_offset + _limit);
    
    // Previous Link
    if(_offset > 0)
    {
      $('PagePrevious').innerHTML = '<a href="javascript:void(0);" onclick="replyFrmGetFotos( $(\'searchBox\').value,' + (_offset-_limit) + ',' + _limit + ');" id="PagePreviousLink">Previous</a>';
    }
    else
    {
      $('PagePrevious').innerHTML = '&nbsp;';
    }
    
    // Next Link
    if(data.MISC.TOTAL_ROWS > _lastFoto)
    {
      $('PageNext').innerHTML = '<a href="javascript:void(0);" onclick="replyFrmGetFotos( $(\'searchBox\').value,' + (_offset+_limit) + ',' + _limit + ');" id="PageNextLink">Next</a>';
    }
    else
    {
      $('PageNext').innerHTML = '&nbsp;';
    }
  }
  else
  {
    _layer.innerHTML = 'Sorry no photos.';
  }
}

function setFlixSchedule(us_id, startDate, endDate)
{
  $('_' + us_id + '_saving').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" align="absmiddle" />';
  
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=flix_schedule_set' + '&us_id=' + us_id + '&startDate=' + startDate + '&endDate=' + endDate, 
      onComplete:function(response){ setFlixScheduleRsp(response); }
    });
}

function setFlixScheduleRsp(response)
{
  var data = response.responseText.parseJSON();
  
  var _html = '';
  data['START_DATE'] = data['START_DATE'] != 0 ? data['START_DATE'] : 'N/A';
  data['END_DATE'] = data['END_DATE'] != 0 ? data['END_DATE'] : 'N/A';
  _html += '<div style="margin-left:25px;" id="schedule_' + data['ID'] + '"><img src="images/icons/history_16x16.png" width="16 height="16" hspace="5" border="0" align="absmiddle" />From ' + data['START_DATE'] + ' to ' + data['END_DATE'] + ' (<a href="javascript:void(0);" onclick="unsetFlixSchedule(' + data['ID'] + ');">remove</a>)</div>';
  $('schedule_item_current_items_' + data['US_ID']).innerHTML += _html;
  
  var effect = new fx.Opacity('_' + data['US_ID'] + '_saving', {duration:800, onComplete: function()
                      { 
                        $('_' + data['US_ID'] + '_saving').innerHTML = '';
                        var onCompleteEffect = new fx.Opacity('_' + data['US_ID'] + '_saving', {duration:800}); }
                      }
                     );
  effect.toggle();
}

function setSubAccountDetails(sa_id, name, email, password)
{
  getXmlHttp();
  xmlHttpSend('setSubAccountDetailsRsp('+sa_id+')', '/xml_result', 'action=sub_account_details_set&sa_id=' + sa_id + '&name=' + name + '&email=' + email + '&password=' + password);
}

function setSubAccountDetailsRsp(sa_id)
{
  var result = xmlHttpResult.parseJSON();
  
  if(result == 'true')
  {
    //subAccount
    new Effect.Highlight('subAccountDetails' + sa_id, {restorecolor:'#ffffff'});
  }
  else
  {
    alert('huh');
  }
}

function setSubAccountPerm(toggle, value)
{
  var tmp = value.split('-');
  
  sa_id = tmp[0];
  permAction = tmp[1];
  permBit = tmp[2];
  permValue  = toggle == true ? 1 : 0;
  
  getXmlHttp();
  xmlHttpSend('setSubAccountPermRsp('+sa_id+', "'+permAction+'", "'+permBit+'")', '/xml_result', 'action=sub_account_permission_set&sa_id=' + sa_id + '&permAction=' + permAction + '&permBit=' + permBit + '&permValue=' + permValue);
}

function setSubAccountPermRsp(sa_id, permAction, permBit)
{
  new Effect.Highlight(permBit + '__' + permAction + '__' + sa_id, {restorecolor:'#ffffff'});
}

function shareFlix(flix_id, value)
{
  switch(value)
  {
    case 'photoPage':
      setFlixPrivacy(flix_id, 1);
      break;
    case 'embed':
      embedFlix(flix_id);
      $('_saving').style.display = 'none';
      break;
    case 'blog':
      blogFlix(flix_id);
      break;
  }
}

function showFlixComments(flix_id)
{
  var force = arguments.length <= 1 ? false : arguments[1];
  
  var _params = 'action=flix_comments&flix_id='+flix_id;
  
  if(force == true)
  {
    _params += '&timestamp='+parseInt(Math.random()*100000);
  }
  
  getXmlHttp();
  xmlHttpSend('showFlixCommentsRsp()', '/xml_result', 'action=flix_comments&flix_id='+flix_id);
}

function showFlixCommentsRsp()
{
  var _pieces = xmlHttpResult.split('~');
  var _show = false;
  if(_pieces[0] == 'true')
  {
    var _html = '';
    var _counter = 0;
    for(i=1; i<_pieces.length; i++)
    {
      if(_pieces[i] != '')
      {
        comment = _pieces[i].split(',');
        c_username = comment[0];
        c_time = comment[1].replace(/\&comma\;/g, ',');
        c_avatar = comment[2];
        c_comment = comment[3].replace(/\&comma\;/g, ',');
          c_comment = c_comment.replace(/\&tilde\;/g, '~')
          
        _html +='<div>'
              + ' <div style="float:left; width:41px;">'
              + '   <div style="padding-right:5px;"><a href="/users/' + c_username + '/"><img src="'+c_avatar+'" width="38" height="38" border="0" /></a></div>'
              + ' </div>'
              + ' <div style="float:left; width:475px;">'
              + '   <div class="bold" style="padding-bottom:3px;"><a href="/users/' + c_username + '/">' + c_username + '</a> on ' + c_time + '</div>'
              + '   <div>' + c_comment + '</div>'
              + ' </div>'
              + ' <br clear="all" />'
              + '</div>'
              + '<div style="padding-top:10px; border-bottom:solid 1px #eeeeee;"></div>'
              + '<div style="padding-bottom:10px;"></div>';
        _counter ++;
        _show = true;
      }
    }
    
    if(_show == true)
    {
      $('commentDisplay').innerHTML = _html;
    }
    else
    {
      $('commentDisplay').innerHTML = '<div class="bold">Sorry no comments for this Flix.</div>';
    }
  }
  else
  {
    $('commentDisplay').innerHTML = '<div class="bold">Error occured.</div>';
  }
}

function showGalleryCode(perPage)
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'post', parameters: 'action=flix_gallery_generator&perPage='+perPage, onComplete:function(response){ showGalleryCodeRsp(response); } }
  );
}

function showGalleryCodeRsp(response)
{
  $('codeContentSlideshow').innerHTML = response.responseText;
  effSlideshow.toggle();
}

function showMediaCode()
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'post', parameters: 'action=flix_media_generator', onComplete:function(response){ showMediaCodeRsp(response); } }
  );
}

function showMediaCodeRsp(response)
{
  $('codeContentMedia').innerHTML = response.responseText;
  effMedia.toggle();
}

function slideshowUpdatePerms(flix_id)
{
  $('slideshowPermsConfirm').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" border="0" align="absmiddle" />&nbsp;Updating...';
  var privacy = 0;
  if($('ssPerm0').checked)
  {
    privacy = privacy | $('ssPerm0').value;
  }
  
  if($('ssPerm1').checked)
  {
    privacy = privacy | $('ssPerm1').value;
  }
  
  if($('ssPerm2').checked)
  {
    privacy = privacy | $('ssPerm2').value;
  }
  
  if($('ssPerm3').checked)
  {
    privacy = privacy | $('ssPerm3').value;
  }
  
  var myAjax = new Ajax.Request(
    '/xml_result',
    {method: 'post', parameters: 'action=flix_privacy_set&flix_id=' + flix_id + '&privacy=' + privacy, onComplete:function(response){ slideshowUpdatePermsRsp(response); } }
  );
}
  
function slideshowUpdatePermsRsp(response)
{
  var data = response.responseText.parseJSON();
  if(data == true)
  {
    $('slideshowPermsConfirm').innerHTML = '<img src="images/icons/checkmark_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;Saved';
  }
  else
  {
    alert('We could not save your settings.');
  }
}

function unsetFlixSchedule(us_id)
{
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=flix_schedule_unset&id='+us_id, onComplete:function(response){ unsetFlixScheduleRsp(response); } }
  );
}

function unsetFlixScheduleRsp(response)
{
  var data = response.responseText.parseJSON();
  var eff = new fx.Height('schedule_' + data, {onComplete: function(){ new Element.remove('schedule_' + data); } });
  eff.toggle();
}

function updateAvatar(id)
{
  $('avatarIcon').src = 'images/icons/refresh_24x24.png';
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'get', parameters: 'action=avatar_update&id='+id, onComplete:function(response){ updateAvatarRsp(response); } }
  );
}

function updateAvatarRsp(response)
{
  var _pieces = response.responseText.split(',');
  if(_pieces[0] == 'true')
  {
    if(_pieces[1] != 'null')
    {
      $('profileAvatar').src = _pieces[1];
      $('profileAvatar').onload = function(){ $('avatarIcon').src = 'images/icons/vcard_24x24.png'; };
      avatarEffect.toggle(); // defined in header.dsp.php
    }
  }
}