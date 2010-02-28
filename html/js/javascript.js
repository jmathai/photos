/* SYSTEM FUNCTIONS */
//_url, _width, _height
function _open()
{
  //_url    = _makeUrl('http://' + _domain + arguments[0]);
  _url    = arguments[0];
  _width  = arguments[1];
  _height = arguments[2];
  _name = arguments.length > 3 ? arguments[3] : '_ff_popup';
  _scrollbars = arguments.length > 4 ? arguments[4] : 0;

  w = screen.width; // screen width
  h = screen.height; // screen height
  winw = _width; //window width
  winh = _height; //window height
  // Where to place the Window
  winl = (w - winw)/2;
  wint = (h - winh)/2;

  _options="toolbar=0,scrollbars="+_scrollbars+",location=0,status=1,menubar=0,resizable=0,width="+winw+",height="+winh+",left="+winl+",top="+wint;
  //_width += 20;
  _ff_win = window.open(_url, _name, _options);
  if(!_ff_win || _ff_win.location == undefined)
  {
    popupBlocked();
  }
  else
  {
    _ff_win.focus();
  }
}

function _confirm(_message, _url)
{
  if( confirm(_message) )
  {
    location.href = _url;
  }
}

function _toggle()
{
  _element = document.getElementById(arguments[0]);
  _vis = _element.style.display;

  if(_vis == 'block' && arguments.length ==1)
  {
    _element.style.display = 'none';
  }
  else
  if(_vis == 'none' && arguments.length == 1)
  {
    _element.style.display = 'block';
  }
  else
  if(arguments.length > 1)
  {
    _element.style.display = arguments[1];
  }
}

function _toggle_arrow()
{
  _element = document.getElementById(arguments[0]);

  _src_array = _element.src.split("/");
  _src = _src_array[_src_array.length - 1];

  if(arguments.length > 1)
  {
    _src_new = arguments[1];
  }
  else
  //if(_src == 'sub_arrow_down.gif')
  if(_src.search('open') > 0)
  {
    _src_new = 'images/navigation/' + _src.replace('open', 'close'); // 'images/navigation/sub_arrow_closed.gif';
  }
  else
  //if(_src == 'sub_arrow_closed.gif')
  if(_src.search('close') > 0)
  {
    _src_new = 'images/navigation/' + _src.replace('close', 'open'); // 'images/navigation/sub_arrow_down.gif';
  }
  else
  {
    _src_new = _src;
  }

  _toggle_image(arguments[0], _src_new);
}

function _toggle_image(_id, _src)
{
  document.images[_id].src = _src;
}

function toggleFade(id)
{
  hide = arguments.length > 1 ? arguments[1] : false;

  var effect = new fx.Opacity(id, {duration:300});

  if(hide)
  {
    effect.hide();
  }
  else
  {
    effect.toggle();
  }
}

function toggleWidth(id)
{
  hide = arguments.length > 1 ? arguments[1] : false;

  var effect = new fx.Width(id, {duration:300});

  if(hide)
  {
    effect.hide();
  }
  else
  {
    effect.toggle();
  }
}

function _getMouseY(e)
{
  return e.clientY;
}

function _getScrollY()
{
  if (top.document.all)
  {
     if (!top.document.documentElement.scrollTop)
        scrollY = top.document.body.scrollTop;
     else
        scrollY = top.document.documentElement.scrollTop;
  }
  else
  {
     scrollY = top.window.pageYOffset;
  }

  return scrollY;
}

// if confirm exit equals true then confirm
var confirmExit = true;
function confirmExit()
{
  return confirm('');
}

function innerHTML(id, text)
{
  $(id).innerHTML = text;
}

function radioValue(element)
{
  value = false;
  for(i=0; i<element.length; i++)
  {
    if(element[i].checked == true)
    {
      value = element[i].value;
      break;
    }
  }
  return value;
}

function checkboxValue(element)
{
  retval = new Array();;
  for (i = 0; i < element.length; i++)
  {
    if (element[i].checked)
    {
      retval.push(element[i].value);
    }
  }

  return retval;
}

function href(id, href)
{
  $(id).href = href;
}

function requireSSL()
{
  if(location.href.search(/^http:/) != -1) // if not in https
  {
    location.href = location.href.replace(/^http:/,'https:'); // force https
  }
}

function formFieldActive(element)
{
  element.value = '';
  element.className = 'formfield';
}

function formFieldInactive(element, text)
{
  if(text != undefined)
  {
    element.value = text;
  }
  element.className = 'formfield_inactive';
}

function selectFotos(count, func)
{
  if(count > 0)
  {
    switch(func)
    {
      case 'tagFotosForm':
        tagFotosForm();
        break;
      case 'unTagFotosForm':
        unTagFotosForm();
        break;
      case 'deleteFotosForm':
        deleteFotosForm();
        break;
      case 'privacyFotosForm':
        privacyFotosForm();
        break;
      case 'sendToFacebookForm':
        sendToFacebookForm();
        break;
      case 'slideshowFromToolbox':
        location.href = '/?action=flix.flix_form&toolbox=1';
        break;
      case 'ciFromToolbox':
        location.href = '/?action=ci.start.act';
        break;
    }
  }
  else
  {
    alert('Please add photos to your Tool Box.');
  }
}

var fotoOverlayType;
function tagFotosForm()
{
  var _state = $('fotoOverlayForm').getStyle('display') == 'none' ? 0 : 1; // 0 means no overlay form - 1 means overlay form exists
  if(fotoOverlayType != 'tag' || _state == 0)
  {
    $('fotoShareOverlayForm').style.display = 'none';
    fotoOverlayType = 'tag';

    var _html = '<div>'
              + ' <div class="bold f_11">Add these tags to photos in your Tool Box.</div>'
              + ' <form style="display:inline;" onsubmit="addTags($(\'tagFotoField\').value); return false;">'
              + '   <div>'
              + '     <div style="padding-top:10px; float:left;"><input autocomplete="off" type="text" id="tagFotoField" class="formfield" style="width:100px;"></div><div class="auto_complete" id="tagFotoField_auto_complete" style="width:100px;"></div>'
              + '     <div style="padding-top:10px; margin-left:10px; float:left;"><input type="button" value="Tag" class="formbutton" class="bold" onclick="addTags($(\'tagFotoField\').value);" /> or <input type="button" value="Close" class="formbutton" onclick="hideOverlayForm();" /></div>'
              + '     <br clear="all" />'
              + '   </div>'
              + ' </form>'
              + ' <div style="padding-top:10px;" class="bold" id="overlayConfirm"></div>'
              + '</div>';

    $('fotoOverlayForm').update(_html);
    $('fotoOverlayForm').setStyle({display:'block'});
    $('toolbarOverlayCeiling').setStyle({borderBottom:'solid 1px #959595'});
    new Autocompleter.Local("tagFotoField", "tagFotoField_auto_complete", userTags, {tokens: ","});
    Element.scrollTo('fotoOverlayForm');
  }
  else
  {
    hideOverlayForm();
  }
}

function unTagFotosForm()
{
  var _state = $('fotoOverlayForm').getStyle('display') == 'none' ? 0 : 1; // 0 means no overlay form - 1 means overlay form exists
  if(fotoOverlayType != 'untag' || _state == 0)
  {
    $('fotoShareOverlayForm').style.display = 'none';
    fotoOverlayType = 'untag';

    var _html = '<div>'
              + ' <div class="bold f_11">Remove these tags from photos in your Tool Box.</div>'
              + ' <form style="display:inline;" onsubmit="removeTags($(\'tagFotoField\').value); return false;">'
              + '   <div>'
              + '     <div style="padding-top:10px; float:left;"><input autocomplete="off" type="text" id="tagFotoField" class="formfield" style="width:100px;"></div><div class="auto_complete" id="tagFotoField_auto_complete" style="width:100px;"></div>'
              + '     <div style="padding-top:10px; margin-left:10px; float:left;"><input type="button" value="Untag" class="formbutton" class="bold" onclick="removeTags($(\'tagFotoField\').value);" /> or <input type="button" value="Close" class="formbutton" onclick="hideOverlayForm();" /></div>'
              + '     <br clear="all" />'
              + '   </div>'
              + ' </form>'
              + ' <div style="padding-top:10px;" class="bold" id="overlayConfirm"></div>'
              + '</div>';
    
    $('fotoOverlayForm').update(_html);
    $('fotoOverlayForm').setStyle({display:'block'});
    $('toolbarOverlayCeiling').setStyle({borderBottom:'solid 1px #959595'});
    new Autocompleter.Local("tagFotoField", "tagFotoField_auto_complete", userTags, {tokens: ","});
    Element.scrollTo('fotoOverlayForm');
  }
  else
  {
    hideOverlayForm();
  }
}

function deleteFotosForm()
{
  var _state = $('fotoOverlayForm').getStyle('display') == 'none' ? 0 : 1; // 0 means no overlay form - 1 means overlay form exists
  if(fotoOverlayType != 'delete' || _state == 0)
  {
    $('fotoShareOverlayForm').style.display = 'none';
    fotoOverlayType = 'delete';
    
    var _html = '<div>'
              + ' <div class="bold f_11">Permanently delete the photos in your Tool Box from your account?</div>'
              + ' <div style="padding-top:5px;">(This action cannot be undone)</div>'
              + ' <div style="padding-top:10px;"><input type="button" value="Yes" class="formbutton" onclick="if(confirm(\'Are you sure?  These photos will be permanently deleted from your account.\')){ deleteFotos(fbOpts); }" /></div>'
              + ' <div style="padding-top:5px;">or</div>'
              + ' <div style="padding-top:5px;"><input type="button" value="Close" class="formbutton" onclick="hideOverlayForm();" /></div>'
              + ' <div style="padding-top:10px;" class="bold" id="overlayConfirm"></div>'
              + '</div>';
    
    $('fotoOverlayForm').update(_html);
    $('fotoOverlayForm').setStyle({display:'block'});
    $('toolbarOverlayCeiling').setStyle({borderBottom:'solid 1px #959595'});
    Element.scrollTo('fotoOverlayForm');
  }
  else
  {
    hideOverlayForm();
  }
}

function privacyFotosForm()
{
  var _state = $('fotoOverlayForm').getStyle('display') == 'none' ? 0 : 1; // 0 means no overlay form - 1 means overlay form exists
  if(fotoOverlayType != 'privacy' || _state == 0)
  {
    $('fotoShareOverlayForm').style.display = 'none';
    fotoOverlayType = 'privacy';

    var _html = '<div>'
              + ' <div class="bold f_11">Set the privacy of photos in your Tool Box.</div>'
              + ' <form id="privacyForm">'
              + ' <div style="padding-left:4px; margin-top:4px;"><input type="checkbox" name="privacySetting" value="PERM_PHOTO_PUBLIC" checked="true" />Allow others to view these photos</div>'
              + ' <div style="padding-left:4px;"><input type="checkbox" name="privacySetting" value="PERM_PHOTO_COMMENT" checked="true" />Allow others to comment on these photos</div>'
              + ' <div style="padding-left:4px;"><input type="checkbox" name="privacySetting" value="PERM_PHOTO_DOWNLOAD" checked="true" />Allow others to download these photos</div>'
              + ' <div style="padding-left:4px;"><input type="checkbox" name="privacySetting" value="PERM_PHOTO_PRINT" checked="true"  />Allow others to print these photos</div>'
              + ' <div style="padding-top:10px;"><input type="button" value="Save" class="formbutton" onclick="setPrivacyOnFotos($(\'privacyForm\').elements[\'privacySetting\']);" /> &nbsp; or &nbsp; <input type="button" value="Close" class="formbutton" onclick="hideOverlayForm();" /></div>'
              + ' <div style="padding-top:10px;" class="bold" id="overlayConfirm"></div>'
              + ' </form>'
              + '</div>';

    $('fotoOverlayForm').update(_html);
    $('fotoOverlayForm').setStyle({display:'block'});
    $('toolbarOverlayCeiling').setStyle({borderBottom:'solid 1px #959595'});
    Element.scrollTo('fotoOverlayForm');
  }
  else
  {
    hideOverlayForm();
  }
}

function sendToFacebookForm()
{
  var _state = $('fotoOverlayForm').getStyle('display') == 'none' ? 0 : 1; // 0 means no overlay form - 1 means overlay form exists
  if(fotoOverlayType != 'privacy' || _state == 0)
  {
    $('fotoShareOverlayForm').style.display = 'none';
    fotoOverlayType = 'privacy';

    var _html = '<div>'
              + ' <div class="bold f_11">Publish these photos to your Facebook.</div>'
              + ' <form id="facebookForm">'
              + '   <div>'
              + '     <div class="bold" style="padding-top:10px;">Enter an album name</div>'
              + '     <div style="padding-top:10px; float:left;"><input type="text" id="albumName" class="formfield" style="width:175px;"></div>'
              + '     <div style="padding-top:10px; margin-left:10px; float:left;"><input type="button" value="Publish" class="formbutton" class="bold" onclick="publishToFacebook($(\'albumName\').value);" /> or <input type="button" value="Close" class="formbutton" onclick="hideOverlayForm();" /></div>'
              + '     <br clear="all" />'
              + '   </div>'
              + ' </form>'
              + ' <div style="padding-top:10px;" class="bold" id="overlayConfirm"></div>'
              + '</div>';

    $('fotoOverlayForm').update(_html);
    $('fotoOverlayForm').setStyle({display:'block'});
    $('toolbarOverlayCeiling').setStyle({borderBottom:'solid 1px #959595'});
    Element.scrollTo('fotoOverlayForm');
  }
  else
  {
    hideOverlayForm();
  }
}

function hideOverlayForm()
{

  $('toolbarOverlayCeiling').setStyle({borderBottom:'solid 1px #3b3a3b'});
  $('fotoOverlayForm').setStyle({display:'none'});
}

function hideShareOverlayForm()
{
  $('fotoOverlayForm').setStyle({display:'block'});
  var effect = new fx.Opacity('fotoShareOverlayForm', {duration:200, onComplete:function(){ $('fotoShareOverlayForm').style.display = 'none'; $('toolbarShareOverlayCeiling').style.borderBottom = 'solid 1px #3b3a3b'; }} );
  effect.toggle();
}

function photoEditOpts(photoId)
{
  var html = '';
  html = '<div style="margin-top:5px;" align="center">'
       + '  <div style="width:16px; padding-left:415px;">'
       + '    <a href="javascript:void(0);" onclick="editOptsEff.toggle();"><img src="images/icons/close_16x16.png" class="png" width="16" height="16" border="0" /></a>'
       + '  </div>'
       + '  <div style="margin-top:0px;">'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="photoEdit(\'rotate\', \'' + photoId + '\', 270);" class="plain"><img src="images/icons/rotate_left_32x32.png" class="png" width="32" height="32" border="0" vspace="5" /><br />Rotate</a>'
       + '    </div>'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="photoEdit(\'rotate\', \'' + photoId + '\', 90);" class="plain"><img src="images/icons/rotate_right_32x32.png" class="png" width="32" height="32" border="0" vspace="5" /><br />Rotate</a>'
       + '    </div>'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="photoEdit(\'sepia\', \'' + photoId + '\');" class="plain"><img src="images/buttons/sepia.gif" width="34" height="32" border="0" vspace="5" /><br />Sepia</a>'
       + '    </div>'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="photoEdit(\'greyscale\', \'' + photoId + '\');" class="plain"><img src="images/buttons/blackwhite.gif" width="34" height="32" border="0" vspace="5" /><br />B&amp;W</a>'
       + '    </div>'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="photoEdit(\'blur\', \'' + photoId + '\');" class="plain"><img src="images/buttons/blur.gif" width="34" height="32" border="0" vspace="5" /><br />Blur</a>'
       + '    </div>'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="photoEdit(\'sharpen\', \'' + photoId + '\');" class="plain"><img src="images/buttons/sharpen.gif" width="32" height="32" border="0" vspace="5" hspace="8" /><br />Sharpen</a>'
       + '    </div>'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="javascript:_open(\'/photo_edit?action=image_crop&image_id=' + photoId + '\', 520, 450);" class="plain"><img src="images/icons/crop_32x32.png" class="png" width="32" height="32" border="0" vspace="5" hspace="0" /><br />Crop</a>'
       + '    </div>'
       + '    <div style="text-align:center; width:40px; padding-left:7px; padding-right:7px; float:left;">'
       + '      <a href="javascript:void(0);" onclick="photoEdit(\'restore\', \'' + photoId + '\');" class="plain"><img src="images/icons/loop_32x32.png" class="png" width="32" height="32" border="0" vspace="5" /><br />Restore</a>'
       + '    </div>'
       + '  </div>'
       + '  <br clear="all"/>'
       + '</div>';

  $('editOpts').innerHTML = html;
  editOptsEff.toggle();
}

function ifCheckedDisable(obj1, obj2)
{
  if(obj1.checked == true)
  {
    obj2.disabled = true;
  }
  else
  {
    obj2.disabled = false;
  }
}

function groupQuickSetDialogHide()
{
  var effect = new fx.Opacity('groupQuickSetDialog', {onComplete: function(){ }} );
  effect.toggle();
}

function quickSetDialogHide()
{
  var effect = new fx.Opacity('quickSetDialog', {onComplete: function(){ }} );
  effect.toggle();
}

function quickSetDialogWait()
{
  document.getElementById('quickSetDialog').style.position = 'absolute';
  document.getElementById('quickSetDialog').style.height = '65px';
  document.getElementById('quickSetDialog').style.border = '1px solid #404040';
  document.getElementById('quickSetDialog').style.backgroundColor = '#fefef6';
  document.getElementById('quickSetDialog').style.paddingLeft = '5px';
  document.getElementById('quickSetDialog').style.paddingTop = '10px';
  document.getElementById('quickSetDialog').style.width = '185px';
  document.getElementById('quickSetDialog').innerHTML = 'Please wait...';
  document.getElementById('quickSetDialog').style.display = 'inline';

  var effect = new fx.Opacity('quickSetDialog', {onComplete: function(){ }} );
  effect.toggle();
}

function quickSetTrans()
{
  document.getElementById('quickSetDialog').style.position = 'absolute';
  document.getElementById('quickSetDialog').style.height = '65px';
  document.getElementById('quickSetDialog').style.border = '1px solid #404040';
  document.getElementById('quickSetDialog').style.backgroundColor = '#fefef6';
  document.getElementById('quickSetDialog').style.paddingLeft = '5px';
  document.getElementById('quickSetDialog').style.paddingTop = '10px';
  document.getElementById('quickSetDialog').style.width = '185px';
  document.getElementById('quickSetDialog').innerHTML = 'Loading...';
  document.getElementById('quickSetDialog').style.display = 'inline';

  ySet = false;
  if(arguments[0] >= 3 && arguments[0] <= 5)
  {
    // position div relative to mouse pointer for certain edit/delete actions
    if(mouseY != undefined && mouseY > 200)
    {
      if( (
            (arguments[0] == 2 || arguments[0] == 3)
            &&
            arguments[1] != 0
          )
          ||
          arguments[0] > 3
        )
      {
        document.getElementById('quickSetDialog').style.top = (mouseY + _getScrollY()) + 'px';
        ySet = true;
      }
    }
  }

  if(ySet == false)
  {
    document.getElementById('quickSetDialog').style.top = null;
  }

  if(document.getElementById('quickSetDialog').style.opacity == 0)
  {
    var effect = new fx.Opacity('quickSetDialog', {onComplete: function(){ }} );
    effect.hide();
    effect.toggle();
  }

  if(arguments.length == 1) // default load quickset pane
  {
    setTimeout("quickSetDialog("+arguments[0]+")", 100);
  }
  else
  if(arguments.length > 1) // either edit or add child where arguments[1] is parent_id
  {
    setTimeout("quickSetDialog("+arguments[0]+", '"+arguments[1]+"')", 100);
  }
}

function groupQuickSetTrans()
{
  // argument #2 is group_id

  document.getElementById('groupQuickSetDialog').style.position = 'absolute';
  document.getElementById('groupQuickSetDialog').style.height = '65px';
  document.getElementById('groupQuickSetDialog').style.border = '1px solid #404040';
  document.getElementById('groupQuickSetDialog').style.backgroundColor = '#fefef6';
  document.getElementById('groupQuickSetDialog').style.paddingLeft = '5px';
  document.getElementById('groupQuickSetDialog').style.paddingTop = '10px';
  document.getElementById('groupQuickSetDialog').style.width = '185px';
  document.getElementById('groupQuickSetDialog').innerHTML = 'Loading...';
  document.getElementById('groupQuickSetDialog').style.display = 'inline';

  ySet = false;
  if(arguments[0] >= 3 && arguments[0] <= 5)
  {
    // position div relative to mouse pointer for certain edit/delete actions
    if(mouseY != undefined && mouseY > 200)
    {
      if( (
            (arguments[0] == 2 || arguments[0] == 3)
            &&
            arguments[2] != 0
          )
          ||
          arguments[0] > 3
        )
      {
        document.getElementById('groupQuickSetDialog').style.top = (mouseY + _getScrollY()) + 'px';
        ySet = true;
      }
    }
  }

  if(ySet == false)
  {
    document.getElementById('groupQuickSetDialog').style.top = null;
  }

  if(document.getElementById('groupQuickSetDialog').style.opacity == 0)
  {
    var effect = new fx.Opacity('groupQuickSetDialog', {onComplete: function(){ }} );
    effect.hide();
    effect.toggle();
  }

  if(arguments.length == 2) // default load quickset pane
  {
    setTimeout("groupQuickSetDialog("+arguments[0]+", "+arguments[1]+")", 100);
  }
  else
  if(arguments.length > 2) // either edit or add child where arguments[1] is parent_id
  {
    setTimeout("groupQuickSetDialog("+arguments[0]+", "+arguments[1]+", '"+arguments[2]+"')", 100);
  }
}

function blogIdent(_type, _username, _password)
{
  if(_type == 'Blogger' || _type == 'TypePad' || _type == 'MovableType' || _type == 'WordPress')
  {
    _params = 'action=blog_retrieve_id&blog='+_type+'&username='+_username+'&password='+_password;
    if(_type == 'MovableType' || _type == 'WordPress')
    {
      if(document.forms['_blogNew'].elements['ub_endPoint'].value.match(/^http:\/\/.+\//) != null)
      {
        _params += '&endpoint='+escape(document.forms['_blogNew'].elements['ub_endPoint'].value);
      }
      else
      {
        _path = _type == 'MovableType' ? '/cgi-bin/mt/mt-xmlrpc.cgi' : '/wordpress/xmlrpc.php';
        alert('The path to your endpoint must be a full Url\n(For example: http://www.example.com/cgi-bin/mt/mt-xmlrpc.cgi'+_path+')');
      }
    }

    //showLoading();

    getXmlHttp();
    xmlHttpSend('checkAndSubmitBlog()', '/xml_result', _params);
  }
  else
  {
    document.forms['_blogNew'].elements['ub_blogId'].value = '~';
    checkAndSubmitBlog('bypass');
  }
}

function postToExisting()
{
  if(document.forms['_blogContent'].elements['title'].value == '')
  {
    alert('Please enter a title for your post.');
    document.forms['_blogContent'].elements['title'].focus();
  }
  else
  {
    showLoading();
    document.forms['_blogExisting'].elements['title'].value = document.forms['_blogContent'].elements['title'].value;
    document.forms['_blogExisting'].elements['post'].value = document.forms['_blogContent'].elements['post'].value;
    document.forms['_blogExisting'].submit();
  }
}

function adjustForm(_blog)
{
  switch(_blog)
  {
    case 'MovableType':
      document.getElementById('endPointUrl').innerHTML = 'http://www.example.com/cgi-bin/mt/mt-xmlrpc.cgi';
      document.getElementById('_form_endPoint').style.display='block';
      break;
    case 'WordPress':
      document.getElementById('endPointUrl').innerHTML = 'http://www.example.com/wordpress/xmlrpc.php';
      document.getElementById('_form_endPoint').style.display='block';
      break;
    default:
      document.getElementById('_form_endPoint').style.display='none';
      break;
  }
}

function checkAndSubmitBlog()
{
  hideLoading();
  if(arguments[0] == 'bypass')
  {
    /* THIS CODE IS DUPLICATED */
    if(document.forms['_blogContent'].elements['title'].value == '')
    {
      alert('Please enter a title for your post.');
      document.forms['_blogContent'].elements['title'].focus();
    }
    else
    if(document.forms['_blogNew'].elements['ub_blogId'].value == '')
    {
      alert('Please select a blog.');
      document.forms['_blogNew'].elements['ub_blogId'].focus();
    }
    else
    {
      document.forms['_blogNew'].elements['title'].value = document.forms['_blogContent'].elements['title'].value;
      document.forms['_blogNew'].elements['post'].value = document.forms['_blogContent'].elements['post'].value;
      document.forms['_blogNew'].submit();
    }
  }
  else
  {
    _pieces = xmlHttpResult.split('~');
    if(_pieces[0] == 'success')
    {
      if(_pieces.length == 2)
      {
        _blogs = _pieces[1].split('`');
        _name  = _blogs[2];
        _url   = _blogs[1];
        _id    = _blogs[0];
        document.getElementById('blogIdText').innerHTML = '<div><input type="hidden" name="ub_blogId" value="'+_id+'"/></div>';
        if(_url != '')
        {
          document.forms['_blogNew'].elements['ub_url'].value = _url;
        }
        _return = true;
      }
      else
      if(_pieces.length > 2)
      {
        _return = true;
        _selectedIndex = document.forms['_blogNew'].elements['ub_blogId'].value != '' ? document.forms['_blogNew'].elements['ub_blogId'].selectedIndex : 0;

        _select = '<div>Blog:</div><div><select name="ub_blogId"><option value="">Please select a blog</option>';
        for(i=1; i<_pieces.length; i++)
        {
          _isSelected = _selectedIndex == i ? ' selected="true" ' : '';
          _thisBlog = _pieces[i].split('`');
          _select += '<option value="'+_thisBlog[0]+'"'+_isSelected+'>'+_thisBlog[2]+'</option>';
        }
        _select += '</select></div>';

        if(_selectedIndex == 0)
        {
          alert('You have more than one blog.  Please select one.');
          _return = false;
        }

        document.getElementById('blogIdText').innerHTML = _select;
      }

      /* PERFORM POST IF EVERYTHING CHECKS OUT */
      if(_return == true)
      {
        /* THIS CODE IS DUPLICATED */
        if(document.forms['_blogContent'].elements['title'].value == '')
        {
          alert('Please enter a title for your post.');
          document.forms['_blogContent'].elements['title'].focus();
        }
        else
        if(document.forms['_blogNew'].elements['ub_blogId'].value == '')
        {
          alert('Please select a blog.');
          document.forms['_blogNew'].elements['ub_blogId'].focus();
        }
        else
        {
          document.forms['_blogNew'].elements['title'].value = document.forms['_blogContent'].elements['title'].value;
          document.forms['_blogNew'].elements['post'].value = document.forms['_blogContent'].elements['post'].value;
          document.forms['_blogNew'].submit();
        }
      }
    }
    else
    {
      if(_pieces[1] == 'invalid_credentials')
      {
        alert('The username/password you specified was incorrect.');
        return false;
      }
      else
      if(_pieces[1] == 'invalid_login')
      {
        alert('The username you specified was incorrect.');
        return false;
      }
      if(_pieces[1] == 'file_not_found')
      {
        alert('The service specified returned an error saying that the file could not be found.\nIf you entered an endpoint then verify that it is correct and try again.');
      }
      else
      if(_pieces[0] == 'failure_custom')
      {
        alert('The server replied: ' + _pieces[1]);
      }
      else
      {
        alert('An unexected problem occured.  Please try again.');
      }
    }
  }
}

function findUploader()
{
  var _app = navigator.appVersion;
  if(_app.search("Windows") != -1 || _app.search("Macintosh") != -1)
  {
    location.href = '/?action=fotobox.upload_form';
  }
  else
  {
    location.href = '/?action=fotobox.upload_form_html';
  }
}

function opacity(id, opacStart, opacEnd, millisec, hide) {
    //speed for each frame
    var speed = Math.round(millisec / 100);
    var timer = 0;

    //determine the direction for the blending, if start and end are the same nothing happens
    if(opacStart > opacEnd) {
        for(i = opacStart; i >= opacEnd; i--) {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            if(i == 0 && hide == true){ setTimeout("document.getElementById('"+id+"').style.display='none'; changeOpac(100,'" + id + "')",(timer * speed)); }
            timer++;
        }
    } else if(opacStart < opacEnd) {
        for(i = opacStart; i <= opacEnd; i++)
        {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    }
}

function changeOpac(opacity, id) {
    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";

    doHide = arguments.length > 2 ? arguments.length[2] : false;
    if(doHide == true && opacity == 0)
    {
      object.display = 'none';
    }
}

function shiftOpacity(id, millisec) {
    //if an element is invisible, make it visible, else make it ivisible
    if(document.getElementById(id).style.opacity == 0) {
        opacity(id, 0, 100, millisec);
    } else {
        opacity(id, 100, 0, millisec);
    }
}


function pm_newMessage(div_id, who)
{
  _layer = document.getElementById(div_id);

  _layer.style.position = 'absolute';
  _layer.style.border = '1px solid #404040';
  _layer.style.backgroundColor = '#faf5ea';
  _layer.style.paddingLeft = '5px';
  _layer.style.paddingTop = '1px';
  _layer.style.width = '165px';
  _layer.style.height = '140px';

  ran = Math.random();
  html = '<div style="padding-left:10px; z-index:100;">';
  html += '<div style="text-align:right;"><span class="f_red">(<a href="javascript:opacity(\'' + div_id + '\', 100, 0, 500, true);" title="close this dialog" class="f_red">x</a>)</span></div>';
  html += '<div style="padding-bottom:3px;" class="f_8 bold">Subject:</div>';
  html += '<div style="padding-bottom:5px;"><input id="_subject_' + ran + '" type="text" class="formfield" /></div>';
  html += '<div style="padding-bottom:3px;" class="f_8 bold">Message:</div>';
  html += '<div><textarea id="_message_' + ran + '" class="formfield"></textarea></div>';
  html += '<div style="padding-top:3px;"><input type="submit" id="_pmSubmit" class="formfield bold" value="Send" onclick="javascript:void(pm_validate(\'' + div_id + '\', ' + who + ', \'_subject_' + ran + '\', \'_message_' + ran + '\'));" /></div>';
  html += '</div>';

  _layer.innerHTML = html;

  _layer.style.display = 'block';
  setTimeout("document.getElementById('_subject_" + ran + "').focus()", '250');
  changeOpac(1, div_id);
  opacity(div_id, 1, 100, 500);
}

function pm_validate(div_id, who, subject_id, message_id)
{
  if( document.getElementById(subject_id).value == '' )
  {
    alert('Please enter a subject');
    document.getElementById(subject_id).focus();
    highlightBorder(subject_id, '#ff0000' );
    return false;
  }
  else if( document.getElementById(message_id).value == '' )
  {
    alert('Please enter a message');
    document.getElementById(message_id).focus();
    highlightBorder(message_id, '#ff0000' );
    return false;
  }
  else
  {
    pm_send(div_id, who, document.getElementById(subject_id).value, document.getElementById(message_id).value);
  }

  return true;
}

function embedSwf()
{
  var data = arguments[0];

	  _html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="//download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="'+data.WIDTH+'" height="'+data.HEIGHT+'">'
	        + '<param name="movie" value="'+data.SRC+'" />'
	        + '<param name="menu" value="false" />'
	        + '<param name="quality" value="high" />'
	        + '<param name="wmode" value="transparent" />'
	        + '<param name="allowScriptAccess" value="always">'
	        + '<param name="bgcolor" value="'+data.BGCOLOR+'" />'
	        + '<embed src="'+data.SRC+'" menu="false" quality="high" bgcolor="'+data.BGCOLOR+'" width="'+data.WIDTH+'" height="'+data.HEIGHT+'" wmode="transparent" type="application/x-shockwave-flash" pluginspage="//www.macromedia.com/go/getflashplayer" allowScriptAccess="always"></embed>'
	        + '</object>';

  if(arguments.length == 1)
  {
    document.write(_html);
  }
  else
  {
    $(arguments[1]).update(_html);
  }
}

var Pager = Class.create();

Pager.prototype = {
  current: null, // current page
  total: null, // total number of pages
  pagesDisplay: null, // number of pages to display
  itemsPerPage: null, // number of items per page
  varStart: null, // name of start variable
  varLimit: null, // name of limit variable
  jsFunc: null, // name of javascript function to execute
  opts: null, // additional parameters to pass into jsFunc

  first: function()
  {
    var _html = '';

    if(this.current > 1)
    {
      var string = arguments.length == 1 ? arguments[0] : '<img src="images/paging_first.gif" width="15" height="15" border="0" align="absmiddle" />';
      _html = '<a href="javascript:'+this.varStart+' = 0; '+this.jsFunc+'('+this.opts+');">'+string+'</a>';
    }

    return _html;
  },

  generate: function()
  {
    var threshHold = Math.floor(this.pagesDisplay / 2); // determine the threshhold ... the padding on each side of the current page
    var start = (this.current - threshHold) < 1 ? 1 : (this.current - threshHold); // determine the starting page
    if((threshHold + this.current) > this.total) // if the start page is near the end then pad more pages to the left
    {
      start -= threshHold + this.current - this.total - 1;
      if(start < 1)
      {
        start = 1;
      }
    }
    var end = start + this.pagesDisplay;
    var _html = '';

    var stepper = 0;
    for(i=start; i<=end; i++)
    {
      if(i <= this.total)
      {
        //alert('this.varStart = ' + eval(this.current) + '\ni = ' + i);
        if(eval(this.current) != i)
        {
          var step = parseInt((i - this.current) * this.itemsPerPage);
          _html += '<a href="javascript:'+this.varStart+' = ('+this.varStart + '+' + step +'); '+this.jsFunc+'('+this.opts+');">'+i+'</a> ';
        }
        else
        {
          _html += i + ' ';
        }
      }
    }

    //this.info();

    return _html;
  },

  last: function()
  {
    var _html = '';

    if(this.current < this.total)
    {
      var string = arguments.length == 1 ? arguments[0] : '<img src="images/paging_last.gif" width="15" height="15" border="0" align="absmiddle" />';
      _html = '<a href="javascript:'+this.varStart+' = '+((this.total-1)*this.itemsPerPage)+'; '+this.jsFunc+'('+this.opts+');">'+string+'</a>';
    }

    return _html;
  },

  next: function()
  {
    var _html = '';

    if(this.current < this.total)
    {
      var string = arguments.length == 1 ? arguments[0] : '<img src="images/paging_next.gif" width="15" height="15" border="0" align="absmiddle" />';
      _html = '<a href="javascript:'+this.varStart+' = '+this.varStart+'+'+(this.itemsPerPage)+'; '+this.jsFunc+'('+this.opts+');">'+string+'</a>';
    }

    return _html;
  },

  previous: function()
  {
    var _html = '';

    if(this.current > 1)
    {
      var string = arguments.length == 1 ? arguments[0] : '<img src="images/paging_previous.gif" width="15" height="15" border="0" align="absmiddle" />';
      _html = '<a href="javascript:'+this.varStart+' = '+this.varStart+'+'+(this.itemsPerPage*-1)+'; '+this.jsFunc+'('+this.opts+');">'+string+'</a>';
    }

    return _html;
  },

  info: function()
  {
    alert("total " + this.total + "\n" +
          "current " + this.current + "\n"
         );
  },

  initialize: function(obj)
  {
    for(key in obj)
    {
      this[key] = obj[key];
    }
  }
}

function groupMemberDeleteConfirmation(div_id, who, group_id, numberOfMembers)
{
  _layer = document.getElementById(div_id);

  _layer.style.position = 'absolute';
  _layer.style.border = '1px solid #404040';
  _layer.style.backgroundColor = '#faf5ea';
  _layer.style.paddingLeft = '5px';
  _layer.style.paddingTop = '1px';
  _layer.style.width = '165px';
  _layer.style.height = '90px';

  ran = Math.random();
  html = '<div style="padding-left:10px;">';
  html += '<div style="text-align:right;"><span class="f_red">(<a href="javascript:opacity(\'' + div_id + '\', 100, 0, 500, true);" title="close this dialog" class="f_red">x</a>)</span></div>';
  html += '<div style="padding-top:5px;">Are you sure you want to delete this member?</div>';
  html += '<div style="float:left; padding-top:10px; padding-right:10px;"><input type="submit" id="_deleteMemberYes" class="formfield bold" value="Yes" onclick="javascript:void(groupMemberDelete(\'' + div_id + '\', ' + who + ', ' + group_id + ', ' + numberOfMembers + '));" /></div>';
  html += '<div style="padding-top:10px;"><input type="submit" id="_deleteMemberNo" class="formfield bold" value="No" onclick="javascript:opacity(\'' + div_id + '\', 100, 0, 500, true);" /></div>';
  html += '</div>';

  _layer.innerHTML = html;

  _layer.style.display = 'inline';
  setTimeout("document.getElementById('_subject_" + ran + "').focus()", '250');
  changeOpac(1, div_id);
  opacity(div_id, 1, 100, 500);
}

function groupMemberAddConfirmation(div_id, who, group_id, numberOfMembers)
{
  _layer = document.getElementById(div_id);

  _layer.style.position = 'absolute';
  _layer.style.border = '1px solid #404040';
  _layer.style.backgroundColor = '#faf5ea';
  _layer.style.paddingLeft = '5px';
  _layer.style.paddingTop = '1px';
  _layer.style.width = '165px';
  _layer.style.height = '90px';

  ran = Math.random();
  html = '<div style="padding-left:10px;">';
  html += '<div style="text-align:right;"><span class="f_red">(<a href="javascript:opacity(\'' + div_id + '\', 100, 0, 500, true);" title="close this dialog" class="f_red">x</a>)</span></div>';
  html += '<div style="padding-top:5px;">Send a member request to this user?</div>';
  html += '<div style="float:left; padding-top:10px; padding-right:10px;"><input type="submit" id="_AddMemberYes" class="formfield bold" value="Yes" onclick="javascript:void(groupMemberAdd(\'' + div_id + '\', ' + who + ', ' + group_id + ', ' + numberOfMembers + '));" /></div>';
  html += '<div style="padding-top:10px;"><input type="submit" id="_addMemberNo" class="formfield bold" value="No" onclick="javascript:opacity(\'' + div_id + '\', 100, 0, 500, true);" /></div>';
  html += '</div>';

  _layer.innerHTML = html;

  _layer.style.display = 'inline';
  changeOpac(1, div_id);
  opacity(div_id, 1, 100, 500);
}

function scrapbook_item_mouseover(div_id)
{
  $(div_id).style.cursor = 'move';
}

function groupShareToggleTabs( str, group_id, moderator, user_id )
{
  switch( str )
  {
    case 'Photos':

      document.getElementById('_tabPhotos').style.backgroundColor = '#ffffff';
      document.getElementById('_tabPhotos').style.borderBottom = '1px solid white';
      document.getElementById('_searchBoxPhotos').style.display = 'block';
      document.getElementById('_groupShareDataPhotos').style.display = 'block';
      document.getElementById('_groupSharePagingPhotos').style.display = 'block';
      $('_groupShareSearchBoxPhotos').focus();

      document.getElementById('_tabSlideshows').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabSlideshows').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxSlideshows').style.display = 'none';
      document.getElementById('_groupShareDataSlideshows').style.display = 'none';
      document.getElementById('_groupSharePagingSlideshows').style.display = 'none';

      break;

    case 'Slideshows':

      document.getElementById('_tabPhotos').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabPhotos').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxPhotos').style.display = 'none';
      document.getElementById('_groupShareDataPhotos').style.display = 'none';
      document.getElementById('_groupSharePagingPhotos').style.display = 'none';

      document.getElementById('_tabSlideshows').style.backgroundColor = '#ffffff';
      document.getElementById('_tabSlideshows').style.borderBottom = '1px solid white';
      document.getElementById('_searchBoxSlideshows').style.display = 'block';
      document.getElementById('_groupShareDataSlideshows').style.display = 'block';
      document.getElementById('_groupSharePagingSlideshows').style.display = 'block';
      $('_groupShareSearchBoxSlideshows').focus();

      break;
  }

  groupShareGetItems(group_id, moderator, user_id);
}

function groupShareGetItems(group_id, moderator, user_id)
{
  if($('_searchBoxPhotos').style.display == 'block')
  {
    groupShareGetPhotos( document.getElementById('_groupShareSearchBoxPhotos').value, group_id, moderator );
  }
  else
  if($('_searchBoxSlideshows').style.display == 'block')
  {
    groupShareGetSlideshows( document.getElementById('_groupShareSearchBoxSlideshows').value, group_id, moderator, user_id );
  }

  return false;
}

function pageShareToggleTabs(str)
{
  switch( str )
  {
    case 'Photos':

      document.getElementById('_tabPhotos').style.backgroundColor = '#ffffff';
      document.getElementById('_tabPhotos').style.borderBottom = '1px solid white';
      document.getElementById('_searchBoxPhotos').style.display = 'block';
      document.getElementById('_pageShareDataPhotos').style.display = 'block';
      document.getElementById('_pageSharePagingPhotos').style.display = 'block';
      $('_pageShareSearchBoxPhotos').focus();

      document.getElementById('_tabSlideshows').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabSlideshows').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxSlideshows').style.display = 'none';
      document.getElementById('_pageShareDataSlideshows').style.display = 'none';
      document.getElementById('_pageSharePagingSlideshows').style.display = 'none';

      document.getElementById('_tabVideos').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabVideos').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxVideos').style.display = 'none';
      document.getElementById('_pageShareDataVideos').style.display = 'none';
      document.getElementById('_pageSharePagingVideos').style.display = 'none';

      break;

    case 'Slideshows':

      document.getElementById('_tabPhotos').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabPhotos').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxPhotos').style.display = 'none';
      document.getElementById('_pageShareDataPhotos').style.display = 'none';
      document.getElementById('_pageSharePagingPhotos').style.display = 'none';

      document.getElementById('_tabSlideshows').style.backgroundColor = '#ffffff';
      document.getElementById('_tabSlideshows').style.borderBottom = '1px solid white';
      document.getElementById('_searchBoxSlideshows').style.display = 'block';
      document.getElementById('_pageShareDataSlideshows').style.display = 'block';
      document.getElementById('_pageSharePagingSlideshows').style.display = 'block';
      $('_pageShareSearchBoxSlideshows').focus();

      document.getElementById('_tabVideos').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabVideos').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxVideos').style.display = 'none';
      document.getElementById('_pageShareDataVideos').style.display = 'none';
      document.getElementById('_pageSharePagingVideos').style.display = 'none';

      break;

    case 'Videos':

      document.getElementById('_tabPhotos').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabPhotos').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxPhotos').style.display = 'none';
      document.getElementById('_pageShareDataPhotos').style.display = 'none';
      document.getElementById('_pageSharePagingPhotos').style.display = 'none';

      document.getElementById('_tabSlideshows').style.backgroundColor = '#d4d4d4';
      document.getElementById('_tabSlideshows').style.borderBottom = '1px solid gray';
      document.getElementById('_searchBoxSlideshows').style.display = 'none';
      document.getElementById('_pageShareDataSlideshows').style.display = 'none';
      document.getElementById('_pageSharePagingSlideshows').style.display = 'none';

      document.getElementById('_tabVideos').style.backgroundColor = '#ffffff';
      document.getElementById('_tabVideos').style.borderBottom = '1px solid white';
      document.getElementById('_searchBoxVideos').style.display = 'block';
      document.getElementById('_pageShareDataVideos').style.display = 'block';
      document.getElementById('_pageSharePagingVideos').style.display = 'block';
      $('_pageShareSearchBoxSlideshows').focus();

      break;
  }

  pageShareGetItems();
}

function pageShareGetItems()
{
  if($('_searchBoxPhotos').style.display == 'block')
  {
    pageOpts['TAGS'] = $('_pageShareSearchBoxPhotos').value;
    pageShareGetPhotos();
  }
  else if($('_searchBoxSlideshows').style.display == 'block')
  {
    pageShareGetSlideshows($('_pageShareSearchBoxSlideshows').value);
  }
  else if($('_searchBoxVideos').style.display == 'block')
  {
    pageShareGetVideos($('_pageShareSearchBoxVideos').value);
  }

  return false;
}

function writeEmail(username)
{
  document.write('<a href="' + 'mail' + 'to:' + username + '@photagious' + '.com">' + username + '@photagious' + '.com</a>');
}

function writeString()
{
  for(i=0; i<arguments.length; i++)
  {
    document.write(arguments[i]);
  }
}

function str_mid(str, length)
{
  return str.substring(0, length);
}

function customImage(path, key, hash, width, height)
{
  src  = '/photos' + path;
  dtmp = src.split('/');
    dtmp[2] = 'custom';
  newsrc = dtmp.join('/');
  filename = newsrc.substr(newsrc.lastIndexOf('/')+1);
  dirname  = newsrc.substr(0, newsrc.lastIndexOf('/'));
  filenamePrefix = filename.substr(0, filename.lastIndexOf('.'));
  filenameSuffix = filename.substr(filename.lastIndexOf('.')+1);
  filenameReturn = filenamePrefix + '_' + width + '_' + height + '.' + filenameSuffix;
  retval = dirname + '/' + filenameReturn + '?' + key;

  return retval;
}

function customImageLock(path, key, hash, oWidth, oHeight, dWidth, dHeight, rotation)
{
  if(rotation == 90 || rotation == 270)
  {
    tmpWidth = oWidth;
    oWidth = oHeight;
    oHeight= tmpWidth;
  }

  srcRatio = parseInt(oWidth) / parseInt(oHeight);
  destRatio= dWidth / dHeight;

  if(destRatio > srcRatio) // height is maxed
  {
    factor = dHeight / oHeight;
    finalHeight = dHeight;
    finalWidth  = Math.ceil(oWidth * factor);
  }
  else
  if(destRatio < srcRatio) // width is maxed
  {
    factor = dWidth / oWidth;
    finalWidth  = dWidth;
    finalHeight = Math.ceil(oHeight * factor);
  }
  else
  {
    finalWidth  = dWidth;
    finalHeight = dHeight;
  }

  //alert('oWidth: ' + oWidth + '\noHeight: ' + oHeight + '\ndWidth: ' + dWidth + '\ndHeight: ' + dHeight + '\nfinalWidth: ' + finalWidth + '\nfinalHeight: ' + finalHeight);

  src  = '/photos' + path;
  dtmp = src.split('/');
    dtmp[2] = 'custom';
  newsrc = dtmp.join('/');
  filename = newsrc.substr(newsrc.lastIndexOf('/')+1);
  dirname  = newsrc.substr(0, newsrc.lastIndexOf('/'));
  filenamePrefix = filename.substr(0, filename.lastIndexOf('.'));
  filenameSuffix = filename.substr(filename.lastIndexOf('.')+1);
  filenameReturn = filenamePrefix + '_' + finalWidth + '_' + finalHeight + '.' + filenameSuffix;
  retval = dirname + '/' + filenameReturn + '?' + key;

  return retval;
}

function doHeaderSearch(username, tags)
{
  if(arguments.length == 2 || true)
  {
    location.href = '/users/' + username + '/tags/' + escape(tags.replace(/ /g,'').replace(/%2C/g,',')) + '/';
  }
  else
  if(arguments.length == 3)
  {
    location.href = '/users/' + username + '/network/tags/' + escape(tags.replace(/ /g,'').replace(/%2C/g,',')) + '/';
  }
  return false;
}

function getPrivacyForm(pref)
{
  /* <?php echo empty($fotoPref) || ($fotoPref & PERM_PHOTO_PUBLIC) == PERM_PHOTO_PUBLIC ? ' checked="true" ' : ''; ?>
  define('PERM_PHOTO_PRIVATE', 0);
  define('PERM_PHOTO_PUBLIC', 1);
  define('PERM_PHOTO_COMMENT', 2);
  define('PERM_PHOTO_TAG', 4);
  define('PERM_PHOTO_DOWNLOAD', 8);
  define('PERM_PHOTO_COPY', 16);
  define('PERM_PHOTO_PRINT', 32);
  */
  pref = parseInt(pref);
  var _private = 0;
  var _public = 1;
  var _comment = 2;
  var _tag = 4;
  var _download = 8;
  var _copy = 16;
  var _print = 32;
  
  var _html = '<div><input onclick="togglePrivacy(\'private\'); setPrivacyForm(this);" type="radio" name="privacySetting" value="'+_private+'" ' + (pref == 0 ? ' checked="true" ' : '') + ' />Only I can view my photos</div>'
            + '<div><input onclick="togglePrivacy(\'public\'); setPrivacyForm(this);" type="radio" name="privacySetting" value="'+_public+'" ' + ((pref & _public) == _public ? ' checked="true" ' : '') + ' />Allow others to view my photos</div>'
            + '<div style="padding-left:20px;"><input type="checkbox" name="privacySettingComment" id="privacySettingComment" value="'+_comment+'" ' + ((pref & _comment) == _comment ? ' checked="true" ' : '') + ' onClick="setPrivacyForm(this);" />Allow others to comment on my photos</div>'
            + '<div style="padding-left:20px;"><input type="checkbox" name="privacySettingTag" id="privacySettingTag" value="'+_tag+'" ' + ((pref & _tag) == _tag ? ' checked="true" ' : '') + ' onClick="setPrivacyForm(this);" />Allow others to tag my photos</div>'
            + '<div style="padding-left:20px;"><input type="checkbox" name="privacySettingDownload" id="privacySettingDownload" value="'+_download+'" ' + ((pref & _download) == _download ? ' checked="true" ' : '') + ' onClick="setPrivacyForm(this);" />Allow others to download my photos</div>'
            + '<div style="padding-left:20px;"><input type="checkbox" name="privacySettingPrint" id="privacySettingPrint" value="'+_print+'"' + ((pref & _print) == _print ? ' checked="true" ' : '') + '  onClick="setPrivacyForm(this);" />Allow others to print my photos</div>'
            + '<input type="hidden" name="privacySettingComputed" id="privacySettingComputed" value="' + pref + '" />';
  
  if(pref == 0)
  {
    _html += "<script> togglePrivacy('private'); </script>";
  }
  return _html;
}

function inviteFriendForm(username, element)
{
  var divId = 'friendMessage_' + element.readAttribute('id');
  if($(divId) == undefined)
  {
    var messageId = 'requestMessage_' + element.readAttribute('id');
    var html = '<span id="' + divId + '" style="position:absolute; padding:5px; margin:12px; background-color:#efefef;" class="border_lite">'
             + '  <h4>Enter a message to ' + username + '</h4>'
             + '  <div>'
             + '    <textarea id="' + messageId + '" class="formfield" rows="5" cols="30">Add me as a friend to be notified when I add photos, slideshows, videos or comments.</textarea>'
             + '  </div>'
             + '  <div>'
             + '    <a href="javascript:void(0);" onclick="inviteFriendFormAct(\'' + username +  '\', $F(\'' + messageId + '\'), \'' + divId + '\');" class="plain bold"><img src="/images/icons/mail_alt_2_16x16.png" class="png" width="16" height="16" vspace="5" border="0" align="absmiddle" />Send request</a> &nbsp; '
             + '    or <a href="javascript:void(0);" onclick="$(\'' + divId + '\').remove();" class="plain">close</a>'
             + '  </div>'
             + '</span>'
             + '<script>';
    new Insertion.After(element, html);
  }
}

function inviteFriendFormAct(username, message, divId)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'post', 
    parameters: 'action=fotopage_add_friend&username='+username+'&message='+escape(message), 
    onComplete: function(response){ inviteFriendFormActRsp(response, divId); }
    });
}

function inviteFriendFormActRsp(response, divId)
{
  var data = response.responseText.parseJSON();
  if(data == true)
  {
    $(divId).update('Request sent. <a href="javascript:void(0);" onclick="$(\'' + divId + '\').remove();">Close this message</a>.');
  }
}

function setPrivacyForm(field)
{
  var computed = $('privacySettingComputed');
  switch(field.value)
  {
    case '0':
      computed.value = 0;
      break;
    case '1':
    case '2':
    case '4':
    case '8':
    case '32':
      if(field.checked)
      {
        computed.value = computed.value | field.value; // perform or to turn bits on
      }
      else
      {
        computed.value = computed.value ^ field.value; // perform exclusive or to turn bits off
      }
      break;
  }
}

function togglePrivacy(value)
{
  switch(value)
  {
    case 'private':
      $('privacySettingComment').disabled = true;
      $('privacySettingComment').checked = false;
      $('privacySettingTag').disabled = true;
      $('privacySettingTag').checked = false;
      $('privacySettingDownload').disabled = true;
      $('privacySettingDownload').checked = false;
      $('privacySettingPrint').disabled = true;
      $('privacySettingPrint').checked = false;
      break;
      
    case 'public':
      $('privacySettingComment').disabled = false;
      $('privacySettingComment').checked = false;
      $('privacySettingTag').disabled = false;
      $('privacySettingTag').checked = false;
      $('privacySettingDownload').disabled = false;
      $('privacySettingDownload').checked = false;
      $('privacySettingPrint').disabled = false;
      $('privacySettingPrint').checked = false;
      break;
  }
}

function addTagToForm(tag)
{
  var field = $('autoCompleter');
  var value = field.value;
  
  if(value.length > 0 && value.search(/\,$/) == -1)
  {
    value += ',';
  }
  
  value += tag;
  
  field.value = value;
}

/* FLASH DETECTION */
var flashInstalled = 0;
var flashVersion = 0;
if (navigator.plugins && navigator.plugins.length)
{
	x = navigator.plugins["Shockwave Flash"];
	if (x)
	{
		flashInstalled = 2;
		if (x.description)
		{
			y = x.description;
			flashVersion = y.charAt(y.indexOf('.')-1);
		}
	}
	else
		flashinstalled = 1;
	if (navigator.plugins["Shockwave Flash 2.0"])
	{
		flashInstalled = 2;
		flashVersion = 2;
	}
}
else if (navigator.mimeTypes && navigator.mimeTypes.length)
{
	x = navigator.mimeTypes['application/x-shockwave-flash'];
	if (x && x.enabledPlugin)
		flashInstalled = 2;
	else
		flashInstalled = 1;
}
else
{
	for(var i=10; i>0; i--){
		flashVersion = 0;
		try{
			var flash = new ActiveXObject("ShockwaveFlash.ShockwaveFlash." + i);
			flashVersion = i;
			break;
		}
		catch(e){
		}
	}
}
