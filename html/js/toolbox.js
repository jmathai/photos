var Toolbox = Class.create();
Toolbox.prototype = {
  
  // properties
  itemsP: [], // photo ids
  itemsT: [], // toolbox ids
  
  tWidth: 40,
  tHeight: 40,
  type: null,
  
  // methods
  add: function(id)
  {
    var isAdded   = this.exists(id);
    var highlight = arguments.length > 1 ? arguments[1] : true;
    
    if(isAdded == false)
    {
      switch(this.type)
      {
        case 'foto':
          return this.addFoto(id);
          break;
        case 'flix':
          return this.addFlix(id);
          break;
      }
    }
  },
  
  addAll: function() // had trouble combining into one http request and query since each T_ID was needed back...could combine into one request and multiple queries though (not sure that's worth it atm)
  {
    for(i=0; i<arguments.length; i++)
    {
      this.add(arguments[i], false); // do not highlight on dup
    }
  },
  
  addFlix: function(id)
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=toolbox_add&itemId='+id+'&itemType=flix', onComplete: this.addRsp.bindAsEventListener(this)}
     );
  },
  
  addFoto: function(id)
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=toolbox_add&itemId='+id+'&itemType=foto', onComplete: this.addRsp.bindAsEventListener(this)}
     );
  },
  
  addRsp: function(response)
  {
    var data = response.responseText.parseJSON();
    
    if(data.T_ID > 0)
    {
      this.itemsP.push(data.FOTO.P_ID);
      this.itemsT.push(data.T_ID);
      this.addToList(this.formatItem(data.T_ID, data.FOTO.P_THUMB_PATH), data.T_ID);
      this.updateCounter();
    }
  },
  
  addToList: function(str, id)
  {
    new Insertion.After($('myToolboxStart'), str);
  },
  
  clear: function()
  {
    var elements = document.getElementsByClassName('toolboxItem');
    
    for(i=0; i<elements.length; i++)
    {
      new Element.remove(elements[i].id);
    }
    
    this.itemsP = [];
    this.itemsT = [];
    this.updateCounter();
  },
  
  deleteAll: function()
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=toolbox_delete_all&itemType='+this.type, onComplete: this.clear.bindAsEventListener(this)}
     );
  },
  
  exists: function(checkValue)
  {
    var status = this.itemsP.detect(function(value, index){ return value == checkValue ? true : false; }); // check if id is in this.itemsP array
    return status == undefined ? false : true;
  },
  
  formatItem: function(id, path)
  {
    return '<div style="margin:5px; float:left;" id="toolbox'+id+'" class="toolboxItem"><img src="/photos'+path+'" width="'+this.tWidth+'" height="'+this.tHeight+'" /><br/><a href="javascript:tb.remove('+id+');">remove</a></div>';
  },
  
  load: function()
  {
    var myAjax = new Ajax.Request(
      '/xml_result',
      {method: 'get', parameters: 'action=toolbox_load&type='+this.type+'&timestamp='+parseInt(Math.random()*100000), onComplete: this.loadRsp.bindAsEventListener(this)}
    );
  },
  
  loadRsp: function(response)
  {
    var data = response.responseText.parseJSON();
    
    var _html = '';
    
    for(i=0; i<data.length; i++)
    {
      foto = data[i];
      _html += this.formatItem(foto.T_ID, foto.P_THUMB_PATH);
      this.itemsP.push(foto.P_ID);
      this.itemsT.push(foto.T_ID);
    }
    
    this.updateCounter();
    new Insertion.After($('myToolboxStart'), _html);
  },
  
  mapPtoT: function(id)
  {
    var index = this.itemsP.indexOf(id);
    return this.itemsT[index];
  },
  
  mapTtoP: function(id)
  {
    var index = this.itemsT.indexOf(id);
    return this.itemsP[index];
  },
  
  reload: function()
  {
    this.clear();
    this.load(this.type);
  },
  
  remove: function(id)
  {
    switch(this.type)
    {
      case 'foto':
        return this.removeFoto(id);
        break;
      case 'flix':
        return this.removeFlix(id);
        break;
    }
  },
  
  removeRsp: function(response)
  {
    var data = response.responseText.parseJSON();
    if(data != false)
    {
      this.itemsP = this.itemsP.reject(function(value, index){ return value == data.P_ID ? true : false; }); // remove item(s) from toolbox with p_id
      this.itemsT = this.itemsT.reject(function(value, index){ return value == data.T_ID ? true : false; }); // remove item(s) from toolbox with p_id
      elementId = 'toolbox'+data.T_ID;
      var effect = new fx.Width(elementId, {onComplete: function(){ new Element.remove(elementId); tb.updateCounter(); }});
      effect.toggle();
    }
  },
  
  removeFoto: function(id)
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=toolbox_remove&type=foto&id='+id, onComplete: this.removeRsp.bindAsEventListener(this)}
     );
  },
  
  removeFlix: function(id)
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=toolbox_remove&type=flix&id='+id, onComplete: this.removeRsp.bindAsEventListener(this)}
     );
  },
  
  setBanner: function()
  {
    new Insertion.After($('myToolboxStart'), '<div id="toolboxBanner" style="text-align:center; width:350px; padding-top:15px; margin:auto; display:none;" class="f_13 bold">You have 0 items in your Tool Box</div>');
    new Effect.Appear('toolboxBanner', { duration: 1.0 });
  },
  
  updateCounter: function()
  {
    $('toolboxCount').update(this.itemsP.length.toString());
    
    if(this.itemsP.length > 0 && $('toolboxBanner') != undefined)
    {
      new Element.remove('toolboxBanner');
    }
    else
    if(this.itemsP.length == 0 && $('toolboxBanner') == undefined)
    {
      this.setBanner();
    }
  },
  
  initialize: function(type)
  {
    this.type = type;
  }
}

var fotoShareOverlayType;
function toolboxShare(doCase)
{
  var doTransition =  function(doCase, response)
                      {
                        data = response.responseText.parseJSON();
                        switch(doCase)
                        {
                          case 'fotoPage':
                            $('fotoOverlayForm').style.display = 'none';
                            var _state = $('fotoShareOverlayForm').style.display == 'none' ? 0 : 1; // 0 means no overlay form - 1 means overlay form exists  
                            var _html;
                            
                            _html = '<div class="bold f_11" style="padding-bottom:10px;">These photos have been added to your Photo Page.</div>'
                                  + '<div></div>'
                                  + '<div style="float:right; padding-right:25px;"><input type="button" value="Close" class="formbutton" onclick="hideShareOverlayForm();" /></div>';
                            
                            $('fotoShareOverlayForm').innerHTML = _html;
                            $('fotoShareOverlayForm').style.display = 'block';
                            
                            if(_state == 0)
                            {
                              var effect = new fx.Opacity('fotoShareOverlayForm', {duration:200});
                              effect.hide();
                              effect.toggle();
                              
                              $('toolbarOverlayCeiling').style.borderBottom = 'solid 1px #3b3a3b'; // reset other border if needed
                            }
                            break;
                        }
                      }
  switch(doCase)
  {
    case 'createFlix':
      location.href = '/?action=flix.flix_form&toolbox=1';
      break;
    case 'addToFlix':
      var myAjax = new Ajax.Request(
         '/xml_result',
         {method: 'get', parameters: 'action=flix_list', onComplete:function(response){ toolboxShareRsp(doCase, response); } }
      );
      break;
    case 'createGroup':
      location.href = '/?action=fotogroup.group_share_form&toolbox=1';
      break;
    case 'addToGroup':
      var myAjax = new Ajax.Request(
         '/xml_result',
         {method: 'get', parameters: 'action=group_list', onComplete:function(response){ toolboxShareRsp(doCase, response); } }
      );
      break;
    case 'fotoPage':
      var myAjax = new Ajax.Request(
         '/xml_result',
         {method: 'get', parameters: 'action=foto_privacy_set&toolbox=1', onComplete:function(response){ doTransition(doCase, response); } }
      );
      break;
  }
}

function toolboxShareRsp(doCase, response)
{
  var data = response.responseText.parseJSON();
  $('fotoOverlayForm').style.display = 'none';
  
  var _state = $('fotoShareOverlayForm').style.display == 'none' ? 0 : 1; // 0 means no overlay form - 1 means overlay form exists  
  var doTransition =  function(_html, _state)
                      {
                        _html += '<div style="float:right; padding-right:25px;"><input type="button" value="Close" class="formbutton" onclick="hideShareOverlayForm();" /></div>';
                        
                        $('fotoShareOverlayForm').innerHTML = _html;
                        $('fotoShareOverlayForm').style.display = 'block';
                        
                        if(_state == 0)
                        {
                          var effect = new fx.Opacity('fotoShareOverlayForm', {duration:200});
                          effect.hide();
                          effect.toggle();
                          
                          $('toolbarOverlayCeiling').style.borderBottom = 'solid 1px #3b3a3b'; // reset other border if needed
                        }
                      }
  
  switch(doCase)
  {
    case 'addToFlix':
      var _html = '';
      var _flix = '';
      
      for(i=0; i<data.length; i++)
      {
        flix = data[i];
        if(flix != undefined)
        {
          _flix += '<div style="width:45%; margin-bottom:6px; float:left;"><a href="/?action=flix.flix_update.act&prune=false&uf_id='+flix.A_ID+'" class="f_white bold plain"><img src="/images/add.gif" width="14" height="14" border="0" hspace="4" align="absmiddle" />' + flix.A_NAME + '</a></div>';
        }
      }
      
      if(fotoShareOverlayType != 'flix' || _state == 0)
      {
        fotoShareOverlayType = 'flix';
        
        var _html = '<div>'
                  + ' <div class="bold f_11" style="padding-bottom:3px;">Click on the Slideshow you want to add these photos to.</div>'
                  + ' <div style="overflow:auto; height:90px;">' + _flix + '</div>'
                  + '</div>';
        doTransition(_html, _state);
      }
      break;
    case 'addToGroup':
      var _html = '';
      var _groups = '';
      
      for(i=0; i<data.length; i++)
      {
        group = data[i];
        if(group != undefined)
        {
          _groups += '<div style="width:45%; margin-bottom:6px; float:left;"><a href="javascript:shareFotosWithGroup('+group.G_ID+', \''+tb.itemsP.join(',')+'\')" class="f_white bold plain"><img src="/images/add.gif" width="14" height="14" border="0" hspace="4" align="absmiddle" />' + group.G_NAME + '</a></div>';
        }
      }
      

      if(fotoShareOverlayType != 'group' || _state == 0)
      {
        fotoShareOverlayType = 'group';
        
        var _html = '<div>'
                  + ' <div class="bold f_11" style="padding-bottom:3px;">Click on the Group you want to share these photos with.</div>'
                  + ' <div style="overflow:auto; height:90px;">' + _groups + '</div>'
                  + '</div>';
        doTransition(_html, _state);
      }
      break;
  }
  
  $('toolbarShareOverlayCeiling').style.borderBottom = 'solid 1px #565659';
  Element.scrollTo('fotoShareOverlayForm');
}